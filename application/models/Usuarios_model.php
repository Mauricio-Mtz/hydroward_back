<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Usuarios_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insertar_usuario($nombre, $apellido, $telefono, $correo, $contrasena, $tipo)
    {
        $data = array(
            'nombre' => $nombre,
            'apellido' => $apellido,
            'telefono' => $telefono,
            'correo' => $correo,
            'contrasena' => $contrasena,
            'tipo' => $tipo,
            'status' => 1
        );

        $this->db->insert('usuarios', $data);
        return $this->db->insert_id();
    }
    public function obtener_usuarios()
    {
        $this->db->select('id, nombre, apellido, telefono, correo, contrasena, tipo, status');
        return $this->db->get('usuarios')->result_array();
    }
    public function obtener_usuario($id)
    {
        return $this->db->get_where('usuarios', array('id' => $id))->row_array();
    }
    public function actualizar_usuario($id, $datos)
    {
        $campos = ['nombre', 'apellido', 'telefono', 'correo', 'contrasena', 'tipo'];

        $datosFiltrados = array_filter(
            $datos,
            function ($key) use ($campos) {
                return in_array($key, $campos);
            },
            ARRAY_FILTER_USE_KEY
        );

        $this->db->where('id', $id);
        $this->db->update('usuarios', $datosFiltrados);
        return $this->db->affected_rows();
    }
    public function eliminar_usuario($id, $status)
    {
        $data = array(
            'status' => $status
        );

        $this->db->where('id', $id);
        $this->db->update('usuarios', $data);

        return $this->db->affected_rows();
    }
}
?>