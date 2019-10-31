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
        $this->load->model('Servico_model', 'servico');
    }
    public function index_get()
    {
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $data = $this->servico->get();
        } else {
            $data = $this->servico->getOne($id);
        }
        $this->set_response($data, REST_Controller_Definitions::HTTP_OK);
    }
    // public function index_post()
    // {
    //     if ((!$this->post('descricao')) || (!$this->post('precos'))) {
    //         $this->set_response([
    //             'status' => false,
    //             'error' => 'Campo não preenchidos'
    //         ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
    //         return;
    //     }

    //     $data = array(
    //         'descricao' => $this->post('descricao'),
    //         'precos' => $this->post('precos'),
    //     );
    //     if ($this->servico->insert($data)) {
    //         $this->set_response([
    //             'status' => true,
    //             'message' => 'Serviço inserido com successo !'
    //         ], REST_Controller_Definitions::HTTP_OK);
    //     } else {
    //         $this->set_response([
    //             'status' => false,
    //             'error' => 'Falha ao inserir serviço'
    //         ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
    //     }
    // }

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
        if ($this->servico->delete($id)) {
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
        if ((!$this->put('descricao')) || (!$this->put('precos')) || ($id <= 0)) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'descricao' => $this->put('descricao'),
            'precos' => $this->put('precos'),
        );
        if ($this->servico->update($id, $data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Serviço alterado com successo !'
            ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao alterar serviço'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }
}
