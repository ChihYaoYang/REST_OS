<?php
class Servico_model extends CI_Model {
    const table = 'servicos';
    
    public function get() {
        $query = $this->db->get(self::table);
        return $query->result();
    }
    public function getOne($id) {
        if ($id > 0) {
            $this->db->where('id', $id);
            $query = $this->db->get(self::table);
            return $query->result();
        } else {
            return false;
        }
    }
    public function insert($data = array()) {
        $this->db->insert(self::table, $data);
        return $this->db->insert_id();
    }
    public function delete($id) {
        if ($id > 0) {
            $this->db->where('cd_servicos', $id);
            $this->db->delete('item_pedido');
            $this->db->where('id', $id);
            $this->db->delete(self::table);
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
    public function update($id, $data = array()) {
        if ($id > 0) {
            $this->db->where('id', $id);
            $this->db->update(self::table, $data);
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
}
