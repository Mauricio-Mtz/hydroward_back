<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Alertas_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function obtener_alertas($idUser)
    {
        $alertas = $this->db->get_where('alertas', array('usuario_id' => $idUser))->result_array();
        return $alertas;
    }
    public function insertar_alertas($mensaje, $idUser)
    {
        $data = array(
            'mensaje' => $mensaje,
            'fecha' => date('Y-m-d H:i:s'),
            'usuario_id' => $idUser
        );

        $this->db->trans_start();
        $this->db->insert('alertas', $data);
        $alerta_id = $this->db->insert_id();

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return $alerta_id;
        }
    }
    public function eliminar_alerta($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('alertas');
        return true;
    }

}
?>