<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class Funcionario extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Funcionario_model', 'funcionario');
        $this->load->model('Cliente_model', 'cliente');
    }

    //////////////////////////////////////////////////////////
    public function login()
    {
        $post = json_decode(file_get_contents("php://input"));
        if (empty($post->email) || empty($post->password)) {
            $this->output
                ->set_status_header(400)
                ->set_output(json_encode(array('status' => false, 'error' => 'Preencha todos os campos'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } else {
            $login = $this->funcionario->get(array('email' => $post->email, 'password' => $post->password));
            $usuario = $this->cliente->getUsuario(
                $this->input->post('email'),
                $this->input->post('password')
            );
            if ($login || $usuario) {
                $this->output
                    ->set_status_header(200)
                    ->set_output(json_encode(array('id' => $login->id, 'nome' => $login->nome, 'email' => $login->email, 'status' => $login->status, 'token' => $login->apikey), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                $this->output
                    ->set_status_header(200)
                    ->set_output(json_encode(array('id' => $usuario->id, 'nome' => $usuario->nome, 'email' => $usuario->email), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            } else {
                $this->output
                    ->set_status_header(400)
                    ->set_output(json_encode(array('status' => false, 'error' => 'Usuário não encontrado'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }
        }
    }

    public function cadastro()
    {
        $post = json_decode(file_get_contents("php://input"));
        if (empty($post->nome) || empty($post->email) || empty($post->telefone) || empty($post->cpf) || empty($post->endereco)) {
            $this->output
                ->set_status_header(400)
                ->set_output(json_encode(array('status' => false, 'error' => 'Preencha todos os campos'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } else {
            //gerar string aleatório
            $this->load->helper('string');
            $randpass = random_string('alnum', 8);
            $insert = $this->funcionario->insert(array('nome' => $post->nome, 'email' => $post->email, 'password' => $randpass, 'telefone' => $post->telefone, 'cpf' => $post->cpf, 'endereco' => $post->endereco));
            if ($insert > 0) {
                $newToken = md5('salt' . $insert);
                $this->funcionario->insertApiKey(array('cd_funcionario' => $insert, 'apikey' => $newToken));
                $this->output
                    ->set_status_header(200)
                    ->set_output(json_encode(
                        array(
                            'id' => "$insert",
                            'email' => $post->email,
                            'nome' => $post->nome,
                            'token' => $newToken
                        ),
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                    ));
            } else {
                $this->output
                    ->set_status_header(400)
                    ->set_output(json_encode(array('status' => false, 'error' => 'Falha no cadastro'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }
        }
    }

    //////////////////////////////////////////////////////////
    public function index_delete()
    {
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $this->set_response([
                'status' => false,
                'error' => 'Parâmetros obrigatórios não fornecidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        if ($this->funcionario->delete($id)) {
            $this->set_response([
                'status' => true,
                'message' => 'Funcionário deletado com successo !'
            ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao deletar funcionário'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }
    public function index_put()
    {
        $id = (int) $this->get('id');
        if ((!$this->put('nome')) || (!$this->put('email')) || (!$this->put('password')) || (!$this->put('telefone')) || (!$this->put('cpf')) || (!$this->put('endereco')) || ($id <= 0)) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'nome' => $this->put('nome'),
            'email' => $this->put('email'),
            'password' => $this->put('password'),
            'telefone' => $this->put('telefone'),
            'cpf' => $this->put('cpf'),
            'endereco' => $this->put('endereco')
        );
        if ($this->funcionario->update($id, $data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Funcionário alterado com successo !'
            ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao alterar funcionário'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }
}
