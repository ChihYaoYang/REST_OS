<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class Pedido extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pedido_model', 'pedido');
        $this->load->model('Cliente_model', 'cliente');
        $this->load->model('Servico_model', 'servicos');
    }
    public function index_get()
    {
        $token = $this->input->get_request_header("token");
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $data = $this->pedido->get($token);
        } else {
            $data = $this->pedido->getOne($id, $token);
        }
        $this->set_response($data, REST_Controller_Definitions::HTTP_OK);
    }
    public function index_post()
    {
        //Cadastro cliente
        if ((!$this->post('nome')) || (!$this->post('email')) || (!$this->post('telefone')) || (!$this->post('cpf')) || (!$this->post('endereco'))) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        //gerar string aleatório
        $this->load->helper('string');
        $randpass = random_string('alnum', 8);

        $dados = array(
            'nome' => $this->post('nome'),
            'email' => $this->post('email'),
            'password' => $randpass,
            'telefone' => $this->post('telefone'),
            'cpf' => $this->post('cpf'),
            'endereco' => $this->post('endereco')
        );
        $insert = $this->cliente->insert($dados);
        /////////////////////////////////////////////////////////////////////
        if ($insert > 0) {
            if ((!$this->post('cd_tipo')) || (!$this->post('cd_Status')) || (!$this->post('cd_funcionario')) || (!$this->post('marca')) || (!$this->post('modelo')) || (!$this->post('defeito')) || (!$this->post('descricao'))) {
                $this->set_response([
                    'status' => false,
                    'error' => 'Campo não preenchidos'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }
            $data = array(
                'cd_cliente' => "$insert",
                'cd_tipo' => $this->post('cd_tipo'),
                'cd_Status' => $this->post('cd_Status'),
                'cd_funcionario' => $this->post('cd_funcionario'),
                'cd_servicos' => $this->post('cd_servicos'),
                'marca' => $this->post('marca'),
                'modelo' => $this->post('modelo'),
                'defeito' => $this->post('defeito'),
                'descricao' => $this->post('descricao'),
                'data_pedido' => date('Y-m-d H:i:s')
            );
            if ($this->pedido->insert($data)) {
                $this->set_response([
                    'status' => true,
                    'message' => 'Pedido inserido com successo !'
                ], REST_Controller_Definitions::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => false,
                    'error' => 'Falha ao inserir pedido'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            }
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao inserir usuário'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

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
        if ($this->pedido->delete($id)) {
            $this->set_response([
                'status' => true,
                'message' => 'Pedido deletado com successo !'
            ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao deletar pedido'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }




    public function cadastar_servicos()
    {
        $id = (int) $this->get('id');
        //Cadastro serviço
        $dados = array(
            'descricao' => $this->post('descricao'),
            'precos' => $this->post('precos'),
        );
        $insert_services = $this->servicos->insert($dados);
        //////////////////////////////////////////////////////////////////
        if ($insert_services > 0) {
            if ((!$this->put('cd_tipo')) || (!$this->put('cd_Status')) || (!$this->put('cd_funcionario')) || (!$this->put('marca')) || (!$this->put('modelo')) || (!$this->put('defeito')) || (!$this->put('descricao')) || ($id <= 0)) {
                $this->set_response([
                    'status' => false,
                    'error' => 'Campo não preenchidos'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }
            $data = array(
                'cd_tipo' => $this->put('cd_tipo'),
                'cd_Status' => $this->put('cd_Status'),
                'cd_funcionario' => $this->put('cd_funcionario'),
                'cd_servicos' => "$insert_services",
                'marca' => $this->put('marca'),
                'modelo' => $this->put('modelo'),
                'defeito' => $this->put('defeito'),
                'descricao' => $this->put('descricao'),
                'data_pedido' => date('Y-m-d H:i:s')
            );
            if ($this->pedido->update($id, $data)) {
                $this->set_response([
                    'status' => true,
                    'message' => 'Pedido alterado com successo !'
                ], REST_Controller_Definitions::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => false,
                    'error' => 'Falha ao alterar pedido'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            }
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao inserir serviço'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }


    public function index_put()
    {
        $id = (int) $this->get('id');
        if ((!$this->put('cd_tipo')) || (!$this->put('cd_Status')) || (!$this->put('cd_funcionario')) || (!$this->put('cd_servicos')) || (!$this->put('marca')) || (!$this->put('modelo')) || (!$this->put('defeito')) || (!$this->put('descricao')) || ($id <= 0)) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'cd_tipo' => $this->put('cd_tipo'),
            'cd_Status' => $this->put('cd_Status'),
            'cd_funcionario' => $this->put('cd_funcionario'),
            'cd_servicos' => $this->put('cd_servicos'),
            'marca' => $this->put('marca'),
            'modelo' => $this->put('modelo'),
            'defeito' => $this->put('defeito'),
            'descricao' => $this->put('descricao'),
            'data_pedido' => date('Y-m-d H:i:s')
        );
        if ($this->pedido->update($id, $data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Pedido alterado com successo !'
            ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao alterar pedido'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }
}