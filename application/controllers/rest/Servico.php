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

    //cadastrar serviço novo e inserir tabela cadastro_pedido
    //function put apenas alterar(inserir) cd_servicos
    public function index_put()
    {
        $id = (int) $this->get('id');
        if ((!$this->put('servico')) || (!$this->put('precos'))) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        //Cadastro serviço
        $dados = array(
            'servico' => $this->put('servico'),
            'precos' => $this->put('precos')
        );
        $insert_services = $this->servicos->insert($dados);
        ////////////////////////////////////////////////////////////////////////////////////
        if ($insert_services > 0) {
            if (($id <= 0)) {
                $this->set_response([
                    'status' => false,
                    'error' => 'Campo não preenchidos' . $id
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }
            $data = array(
                'cd_servicos' => "$insert_services"
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
}
