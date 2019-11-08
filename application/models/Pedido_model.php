<?php
class Pedido_model extends CI_Model
{
    const table = 'cadastro_pedido';

    public function get($apikey)
    {
        $this->db->select(self::table . '.*');
        $this->db->join('funcionario', self::table . '.cd_funcionario = funcionario.id', 'inner');
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
