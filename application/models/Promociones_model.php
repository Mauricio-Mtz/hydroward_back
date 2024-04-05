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

        $this->db->db_debug = FALSE;

        $this->db->insert('promociones', $data);
        $error = $this->db->error();

        $this->db->db_debug = TRUE;

        if ($error['code']) {
            return array(
                'success' => false,
                'message' => $error['message'],
            );
        }

        return array(
            'success' => true,
            'message' => 'Promoción agregada correctamente',
        );
    }


    public function delete_promocion($id)
    {
        $this->db->where('producto_id', $id);
        $this->db->delete('promociones');

        return $this->db->affected_rows();
    }
}
?>