<?php
class Funcionario_model extends CI_Model
{
    const table = 'funcionario';
    const password = 'ryanSENAC';

    ////////////////////////////////
    public function insert($fields)
    {
        $fields['password'] = sha1($fields['password'] . self::password);
        $this->db->insert(self::table, $fields);
        return $this->db->insert_id();
    }

    public function insertApiKey($fields)
    {
        $this->db->insert('token', $fields);
        return $this->db->affected_rows();
    }

    public function get($params)
    {
        $this->db->select(self::table . '.*, usuario.id, token.apikey ');
        $this->db->join('token', 'token.usuario_id=' . self::table . '.id');
        $query = $this->db->get_where(self::table, $params);
        return $query->row();
    }

    ////////////////////////////////

    public function delete($id)
    {
        if ($id > 0) {
            $this->db->where('id', $id,'cd_funcionario', $id);
            $this->db->delete(self::table);
            $this->db->delete('token');
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
