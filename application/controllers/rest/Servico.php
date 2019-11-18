<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class Servico extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pedido_model', 'pedido');
        $this->load->model('Servico_model', 'servicos');
        $this->load->model('Item_pedido_model', 'item');
    }
    public function index_get()
    {
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $data = $this->servicos->get();
        } else {
            $data = $this->servicos->getOne($id);
        }
        $this->set_response($data, REST_Controller_Definitions::HTTP_OK);
    }
    //Cadastrar serviço e inserir o id (item pedido)
    public function index_post()
    {
        $id = (int) $this->get('id');
        if ((!$this->post('servico')) || (!$this->post('precos'))) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $dados = array(
            'servico' => $this->post('servico'),
            'precos' => str_replace(',', '.', str_replace('.', '', $this->post('precos'))),
        );
        $insert = $this->servicos->insert($dados);
        if ($insert > 0) {
            if (($id <= 0)) {
                $this->set_response([
                    'status' => false,
                    'error' => 'Campo não preenchidos'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }

            $data = array(
                'cd_cadastro_pedido' => $id,
                'cd_servicos' => $insert
            );
            if ($this->item->insert($data)) {
                $this->set_response([
                    'status' => true,
                    'message' => 'item inserido com successo !'
                ], REST_Controller_Definitions::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => false,
                    'error' => 'Falha ao inserir item'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            }
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao inserir serviço'
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
        if ($this->servicos->delete($id)) {
            $this->set_response([
                'status' => true,
                'message' => 'Serviço deletado com successo !'
            ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao deletar serviço'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

    public function index_put()
    {
        $id = (int) $this->get('id');
        if ((!$this->put('servico')) || (!$this->put('precos') || $id <= 0)) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $dados = array(
            'servico' => $this->put('servico'),
            'precos' => str_replace(',', '.', str_replace('.', '', $this->put('precos'))),
        );

        if ($this->servicos->update($id, $dados)) {
            $this->set_response([
                'status' => true,
                'message' => 'Serviços alterado com successo !'
            ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao alterar serviços'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }
}
