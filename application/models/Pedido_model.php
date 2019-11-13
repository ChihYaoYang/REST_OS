<?php
class Pedido_model extends CI_Model
{
    const table = 'cadastro_pedido';

    public function get($apikey)
    {
        $this->db->select(self::table . '.*,cliente.nome as Cliente,tipo.type as Tipo,status.status as Status,funcionario.nome as Funcionario');
        $this->db->join('funcionario', self::table . '.cd_funcionario = funcionario.id', 'inner');
        $this->db->join('cliente', 'cadastro_pedido.cd_cliente=cliente.id', 'inner');
        $this->db->join('tipo', 'cadastro_pedido.cd_tipo=tipo.id', 'inner');
        $this->db->join('status', 'cadastro_pedido.cd_status=status.id', 'inner');
        $this->db->join('token', 'token.cd_funcionario = funcionario.id', 'inner');
        $this->db->where(array('token.apikey' => $apikey));
        $query = $this->db->get(self::table);
        return $query->result();
    }

    public function getOne($id, $apikey)
    {
        if ($id > 0) {
            $this->db->select(self::table . '.*');
            $this->db->join('funcionario', self::table . '.cd_funcionario = funcionario.id', 'inner');
            $this->db->join('token', 'token.cd_funcionario = funcionario.id', 'inner');
            $this->db->where(array('token.apikey' => $apikey, (self::table) . '.id' => $id));
            $query = $this->db->get(self::table);
            return $query->row(0);
        } else {
            return false;
        }
    }
    public function insert($data = array())
    {
        $this->db->insert(self::table, $data);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        if ($id > 0) {
            $this->db->where('cd_cadastro_pedido', $id);
            $this->db->delete('item_pedido');
            $this->db->where('id', $id);
            $this->db->delete(self::table);
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
    public function update($id, $data = array())
    {
        if ($id > 0) {
            $this->db->where('id', $id);
            $this->db->update(self::table, $data);
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
}
