<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Funcionario_model', 'funcionario');
        $this->load->model('Cliente_model', 'cliente');
    }

    public function login()
    {
        $post = json_decode(file_get_contents("php://input"));
        if (empty($post->email) || empty($post->password)) {
            $this->output
                ->set_status_header(400)
                ->set_output(json_encode(array('status' => false, 'error' => 'Preencha todos os campos'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } else {
            $login = $this->funcionario->get(array('email' => $post->email, 'password' => $post->password));
            $usuario = $this->cliente->getUsuario(array('email' => $post->email, 'password' => $post->password));
            if ($login) {
                $this->output
                    ->set_status_header(200)
                    ->set_output(json_encode(array('id' => $login->id, 'nome' => $login->nome, 'email' => $login->email, 'status' => $login->status, 'token' => $login->apikey), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            } else if ($usuario) {
                $this->output
                    ->set_status_header(200)
                    ->set_output(json_encode(array('id' => $usuario->id, 'nome' => $usuario->nome, 'email' => $usuario->email, 'status' => $usuario->status), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            } else {
                $this->output
                    ->set_status_header(400)
                    ->set_output(json_encode(array('status' => false, 'error' => 'Usuário não encontrado'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }
        }
    }

    public function loginphone()
    {
        $post = json_decode(file_get_contents("php://input"));
        if (empty($post->telefone)) {
            $this->output
                ->set_status_header(400)
                ->set_output(json_encode(array('status' => false, 'error' => 'Preencha todos os campos'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } else {
            $login = $this->funcionario->getphone(array('telefone' => $post->telefone));
            $usuario = $this->cliente->getUsuarioPhone(array('telefone' => $post->telefone));
            if ($login) {
                $this->output
                    ->set_status_header(200)
                    ->set_output(json_encode(array('id' => $login->id, 'nome' => $login->nome, 'email' => $login->email, 'status' => $login->status, 'token' => $login->apikey), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            } else if ($usuario) {
                $this->output
                    ->set_status_header(200)
                    ->set_output(json_encode(array('id' => $usuario->id, 'nome' => $usuario->nome, 'email' => $usuario->email, 'status' => $usuario->status), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            } else {
                $this->output
                    ->set_status_header(400)
                    ->set_output(json_encode(array('status' => false, 'error' => 'Número não encontrado'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }
        }
    }

    public function cadastro()
    {
        $post = json_decode(file_get_contents("php://input"));
        if (empty($post->nome) || empty($post->email) || empty($post->password) || empty($post->telefone) || empty($post->cpf)) {
            $this->output
                ->set_status_header(400)
                ->set_output(json_encode(array('status' => false, 'error' => 'Preencha todos os campos'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } else {
            $insert = $this->funcionario->insert(array('nome' => $post->nome, 'email' => $post->email, 'password' => $post->password, 'telefone' => $post->telefone, 'cpf' => $post->cpf));
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
}
