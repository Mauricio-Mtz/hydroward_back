<?php
defined('BASEPATH') or exit ('No direct script access allowed');
class Login_model extends CI_Model
{
    public function get_login($correo, $contrasena)
    {
        $query = $this->db->get_where('Usuarios', array('correo' => $correo, 'contrasena' => $contrasena));
        return $query->row_array();
        // $usuario = $query->row_array();
        // if ($usuario) {
        //     // Verificar si la contraseña proporcionada coincide con el hash almacenado en la base de datos
        //     if ($usuario['contrasena'] === md5($contrasena)) {
        //         return $usuario; // Devolver el usuario si las credenciales son válidas
        //     }
        // }
        // return null; // Devolver null si las credenciales son inválidas
    }
    public function obtenerUsuarioPorId($id)
    {
        $query = $this->db->get_where('Usuarios', array('id' => $id));
        return $query->row_array();
    }
    public function registrarUsuario($nombre, $apellido, $telefono, $correo, $contrasena)
    {
        $nombre = $this->db->escape_str($nombre);
        $apellido = $this->db->escape_str($apellido);
        $telefono = $this->db->escape_str($telefono);
        $correo = $this->db->escape_str($correo);
        $contrasena = $this->db->escape_str($contrasena);

        // $hashContrasena = md5($contrasena);

        $data = array(
            'correo' => $correo,
            'contrasena' => $contrasena,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'telefono' => $telefono
        );

        $this->db->insert('Usuarios', $data);
        return $this->db->insert_id();
    }
}
