<?php
class Cliente_model extends CI_Model {
    const table = 'cliente';
    const password = 'ryanSENAC';
    
    public function get() {
        $query = $this->db->get(self::table);
        return $query->result();
    }
    public function getOne($id) {
        if ($id > 0) {
            $this->db->where('id', $id);
            $query = $this->db->get(self::table);
            return $query->row(0);
        } else {
            return false;
        }
    }
    public function insert($data = array()) {
        $data['password'] = sha1($data['password'] . self::password);
        $this->db->insert(self::table, $data);
        // return $this->db->affected_rows();
        return $this->db->insert_id(); //return valor id
    }
    public function delete($id) {
        if ($id > 0) {
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
?>