<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Usuario_model extends CI_Model
{
    public function get_login($correo, $contrasena)
    {
        $query = $this->db->get_where('Usuarios', array('correo' => $correo, 'contrasena' => $contrasena));
        return $query->row_array(); 
    }

    public function obtenerUsuarioPorId($id) {
        $query = $this->db->get_where('Usuarios', array('id' => $id));
        return $query->row_array();
    }
    
}
