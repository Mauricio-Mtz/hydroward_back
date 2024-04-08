<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Login_model extends CI_Model
{
    public function get_login($correo, $contrasena)
    {
        $query = $this->db->get_where('usuarios', array('correo' => $correo, 'contrasena' => $contrasena));
        $usuario = $query->row_array();
        if (!empty($usuario)) {
            // Llamar al procedimiento almacenado RevisarEstanques
            $this->db->query("CALL RevisarEstanques(?)", array($usuario['id']));
        }
        return $usuario;
    }
    
    public function get_login_api($correo)
    {
        $query = $this->db->get_where('usuarios', array('correo' => $correo));
        return $query->row_array();
    }
    public function obtenerUsuarioPorId($id)
    {
        $query = $this->db->get_where('usuarios', array('id' => $id));
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

        $this->db->insert('usuarios', $data);
        return $this->db->insert_id();
    }
    public function actualizar_perfil($id, $nombre, $apellido, $telefono, $correo, $contrasena, $imagen_nombre)
    {
        $data = array(
            'nombre' => $nombre,
            'apellido' => $apellido,
            'telefono' => $telefono,
            'correo' => $correo,
            'contrasena' => $contrasena,
        );
    
        if ($imagen_nombre != "Error en la imagen") {
            $data['imagen'] = $imagen_nombre;
        }
    
        $this->db->where('id', $id);
        $this->db->update('usuarios', $data);
    
        return $this->db->affected_rows();
    }
    public function actualizar_direccion($id, $direccion, $ciudad, $estado, $cp)
    {
        $data = array(
            'direccion' => $direccion,
            'ciudad' => $ciudad,
            'estado' => $estado,
            'cp' => $cp,
        );
        $this->db->where('id', $id);
        $this->db->update('usuarios', $data);

        return $this->db->affected_rows();
    }

}
