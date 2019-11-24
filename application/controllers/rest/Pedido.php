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
        date_default_timezone_set('America/Sao_Paulo');
    }
    public function index_get()
    {
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $data = $this->pedido->get();
        } else {
            $data = $this->pedido->getOne($id);
        }
        $this->set_response($data, REST_Controller_Definitions::HTTP_OK);
    }
    //Novo pedido com formaulário de dados do cliente para cadastrar novo cliente
    public function novo_pedido_post()
    {
        if ((!$this->post('nome')) || (!$this->post('email')) || (!$this->post('password')) || (!$this->post('telefone')) || (!$this->post('cpf'))) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }

        $dados = array(
            'nome' => $this->post('nome'),
            'email' => $this->post('email'),
            'password' => $this->post('password'),
            'telefone' => $this->post('telefone'),
            'cpf' => $this->post('cpf')
        );
        $insert = $this->cliente->insert($dados);
        if ($insert > 0) {
            if ((!$this->post('cd_tipo')) || (!$this->post('cd_status')) || (!$this->post('cd_funcionario')) || (!$this->post('marca')) || (!$this->post('modelo')) || (!$this->post('defeito')) || (!$this->post('descricao'))) {
                $this->set_response([
                    'status' => false,
                    'error' => 'Campo não preenchidos'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }
            $data = array(
                'cd_cliente' => "$insert",
                'cd_tipo' => $this->post('cd_tipo'),
                'cd_status' => $this->post('cd_status'),
                'cd_funcionario' => $this->post('cd_funcionario'),
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
    ///cadastrar pedido sem formulário dos cliente///
    public function index_post()
    {
        if ((!$this->post('cd_cliente')) || (!$this->post('cd_tipo')) || (!$this->post('cd_status')) || (!$this->post('cd_funcionario')) || (!$this->post('marca')) || (!$this->post('modelo')) || (!$this->post('defeito')) || (!$this->post('descricao'))) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'cd_cliente' => $this->post('cd_cliente'),
            'cd_tipo' => $this->post('cd_tipo'),
            'cd_status' => $this->post('cd_status'),
            'cd_funcionario' => $this->post('cd_funcionario'),
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
    public function index_put()
    {
        $id = (int) $this->get('id');
        if ((!$this->put('cd_tipo')) || (!$this->put('cd_status')) || (!$this->put('cd_funcionario')) || (!$this->put('marca')) || (!$this->put('modelo')) || (!$this->put('defeito')) || (!$this->put('descricao')) || ($id <= 0)) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'cd_tipo' => $this->put('cd_tipo'),
            'cd_status' => $this->put('cd_status'),
            'cd_funcionario' => $this->put('cd_funcionario'),
            'marca' => $this->put('marca'),
            'modelo' => $this->put('modelo'),
            'defeito' => $this->put('defeito'),
            'descricao' => $this->put('descricao')
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
