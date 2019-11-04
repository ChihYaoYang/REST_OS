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
    }
    public function index_get()
    {
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $data = $this->funcionario->getAll();
        } else {
            $data = $this->funcionario->getOne($id);
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
        if ((!$this->put('nome')) || (!$this->put('email')) || (!$this->put('password')) || (!$this->put('telefone')) || (!$this->put('cpf')) || ($id <= 0)) {
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
            'cpf' => $this->put('cpf')
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
