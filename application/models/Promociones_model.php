<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Promociones_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insertar_promocion($fecha_inicio, $fecha_fin, $descuento, $producto_id)
    {
        $data = array(
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'descuento' => $descuento,
            'producto_id' => $producto_id,
        );
        $this->db->insert('promociones', $data);
        return $this->db->insert_id();
    }
    public function delete_promocion($id)
    {
        $this->db->where('producto_id', $id);
        $this->db->delete('promociones');

        return $this->db->affected_rows();
    }
}
?>