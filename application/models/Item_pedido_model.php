<?php
class Item_pedido_model extends CI_Model
{
    const table = 'item_pedido';

    public function getAll()
    {
        $this->db->select('item_pedido.*, cliente.nome as Cliente,tipo.type as Tipo,status.status as Status,funcionario.nome as Funcionario, cadastro_pedido.marca as Marca,cadastro_pedido.modelo as Modelo,cadastro_pedido.defeito as Defeito,cadastro_pedido.descricao as Descricao,cadastro_pedido.data_pedido as Data_Cadastrado,  servicos.servico as Servico,servicos.precos as Precos,');
        $this->db->from('item_pedido');
        $this->db->join('cadastro_pedido', 'cadastro_pedido.id=item_pedido.cd_cadastro_pedido', 'inner');
        $this->db->join('servicos', 'servicos.id=item_pedido.cd_servicos', 'inner');
        $this->db->join('funcionario', 'cadastro_pedido.cd_funcionario = funcionario.id', 'inner');
        $this->db->join('cliente', 'cadastro_pedido.cd_cliente=cliente.id', 'inner');
        $this->db->join('tipo', 'cadastro_pedido.cd_tipo=tipo.id', 'inner');
        $this->db->join('status', 'cadastro_pedido.cd_status=status.id', 'inner');
        $query = $this->db->get();
        return $query->result();
    }
    public function getOne($id)
    {
        if ($id > 0) {
            $this->db->where('item_pedido.cd_cadastro_pedido', $id);
            $this->db->select('item_pedido.*, cliente.nome as Cliente,tipo.type as Tipo,status.status as Status,funcionario.nome as Funcionario, cadastro_pedido.marca as Marca,cadastro_pedido.modelo as Modelo,cadastro_pedido.defeito as Defeito,cadastro_pedido.descricao as Descricao,cadastro_pedido.data_pedido as Data_Cadastrado,  servicos.servico as Servico,servicos.precos as Precos,');
            $this->db->from('item_pedido');
            $this->db->join('cadastro_pedido', 'cadastro_pedido.id=item_pedido.cd_cadastro_pedido', 'inner');
            $this->db->join('servicos', 'servicos.id=item_pedido.cd_servicos', 'inner');
            $this->db->join('funcionario', 'cadastro_pedido.cd_funcionario = funcionario.id', 'inner');
            $this->db->join('cliente', 'cadastro_pedido.cd_cliente=cliente.id', 'inner');
            $this->db->join('tipo', 'cadastro_pedido.cd_tipo=tipo.id', 'inner');
            $this->db->join('status', 'cadastro_pedido.cd_status=status.id', 'inner');
            $query = $this->db->get();
            return $query->result();
        } else {
            return false;
        }
    }

    public function insert($data = array())
    {
        $this->db->insert(self::table, $data);
        return $this->db->affected_rows();
    }
}
