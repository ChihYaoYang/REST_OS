<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class Status extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Status_model', 'status');
    }
    public function index_get()
    {
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $data = $this->status->get();
        } else {
            $data = $this->status->getOne($id);
        }
        $this->set_response($data, REST_Controller_Definitions::HTTP_OK);
    }
    public function index_post()
    {
        if ((!$this->post('status'))) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }

        $data = array(
            'status' => $this->post('status')
        );
        if ($this->status->insert($data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Status inserido com successo !'
            ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao inserir status'
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
        if ($this->status->delete($id)) {
            $this->set_response([
                'status' => true,
                'message' => 'Status deletado com successo !'
            ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao deletar status'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }
    public function index_put()
    {
        $id = (int) $this->get('id');
        if ((!$this->put('status')) || ($id <= 0)) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'status' => $this->put('status')
        );
        if ($this->status->update($id, $data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Status alterado com successo !'
            ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao alterar status'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }
}
