<?php
class Funcionario_model extends CI_Model
{
    const table = 'funcionario';
    const password = 'ryanSENAC';

    ////////////////Login////////////////
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
        $params['password'] = sha1($params['password'] . self::password);
        $this->db->select(self::table . '.*, funcionario.id, token.apikey ');
        $this->db->join('token', 'token.cd_funcionario=' . self::table . '.id');
        $query = $this->db->get_where(self::table, $params);
        return $query->row();
    }
    public function getphone($params)
    {
        $this->db->select(self::table . '.*, funcionario.id, token.apikey ');
        $this->db->join('token', 'token.cd_funcionario=' . self::table . '.id');
        $query = $this->db->get_where(self::table, $params);
        return $query->row();
    }
    ////////////////////////////////

    public function getAll()
    {
        $query = $this->db->get(self::table);
        return $query->result();
    }
    public function getOne($id)
    {
        if ($id > 0) {
            $this->db->where('id', $id);
            $query = $this->db->get(self::table);
            return $query->result();
        } else {
            return false;
        }
    }
    public function delete($id)
    {
        if ($id > 0) {
            $this->db->where('cd_funcionario', $id);
            $this->db->delete('token');
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
            $data['password'] = sha1($data['password'] . self::password);
            $this->db->where('id', $id);
            $this->db->update(self::table, $data);
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
}
