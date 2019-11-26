<?php
class Cliente_model extends CI_Model
{
    const table = 'cliente';
    const password = 'ryanSENAC';

    public function get()
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

    public function getUsuario($params)
    {
        $params['password'] = sha1($params['password'] . self::password);
        $query = $this->db->get_where(self::table, $params);
        return $query->row();
    }
    public function getUsuarioPhone($params)
    {
        $query = $this->db->get_where(self::table, $params);
        return $query->row();
    }
    public function insert($dados = array())
    {
        $dados['password'] = sha1($dados['password'] . self::password);
        $this->db->insert(self::table, $dados);
        //return valor id
        return $this->db->insert_id();
    }
    public function delete($id)
    {
        if ($id > 0) {
            $this->db->where('cd_cliente', $id);
            $this->db->delete('cadastro_pedido');
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
