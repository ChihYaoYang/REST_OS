<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class Tipo extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tipo_model', 'tipo');
    }
    public function index_get()
    {
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $data = $this->tipo->get();
        } else {
            $data = $this->tipo->getOne($id);
        }
        $this->set_response($data, REST_Controller_Definitions::HTTP_OK);
    }
    public function index_post()
    {
        if ((!$this->post('type'))) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }

        $data = array(
            'type' => $this->post('type')
        );
        if ($this->tipo->insert($data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Tipo inserido com successo !'
            ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao inserir tipo'
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
        if ($this->tipo->delete($id)) {
            $this->set_response([
                'status' => true,
                'message' => 'Tipo deletado com successo !'
            ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao deletar tipo'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }
    public function index_put()
    {
        $id = (int) $this->get('id');
        if ((!$this->put('type')) || ($id <= 0)) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'type' => $this->put('type')
        );
        if ($this->tipo->update($id, $data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Tipo alterado com successo !'
            ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao alterar tipo'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }
}
