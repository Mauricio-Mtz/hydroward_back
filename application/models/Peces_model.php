<?php
defined('BASEPATH') or exit ('No direct script access allowed');
class Peces_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function obtener_peces()
    {
        $query = $this->db->get('peces');
        return $query->result();
    }
    public function obtener_pez($id)
    {
        return $this->db->get_where('peces', array('id' => $id))->row_array();
    }
    public function eliminar_pez($id, $status)
    {
        $data = array(
            'status' => $status
        );

        $this->db->where('id', $id);
        $this->db->update('peces', $data);

        return $this->db->affected_rows();
    }
    public function insertar_pez($nombre, $alimentacion, $siAlim, $noAlim, $tempMin, $tempMax, $phMin, $phMax)
    {
        $data = array(
            'nombre' => $nombre,
            'alimentacion' => $alimentacion,
            'tiempo_si_alim' => $siAlim,
            'tiempo_no_alim' => $noAlim,
            'temperatura_min' => $tempMin,
            'temperatura_max' => $tempMax,
            'ph_min' => $phMin,
            'ph_max' => $phMax
        );

        $this->db->trans_start();
        $this->db->insert('peces', $data);
        $producto_id = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return $producto_id;
        }
    }
    public function actualizar_pez($id, $nombre, $alimentacion, $siAlim, $noAlim, $tempMin, $tempMax, $phMin, $phMax )
    {
        $data = array(
            'nombre' => $nombre,
            'alimentacion' => $alimentacion,
            'noAlim' => $noAlim,
            'siAlim' => $siAlim,
            'tempMin' => $tempMin,
            'tempMax' => $tempMax,
            'phMin' => $phMin,
            'phMax' => $phMax
        );

        $campos = array("nombre", "alimentacion", "tiempo_no_alim", "tiempo_no_alim", "temperatura_min", "temperatura_max", "ph_min", "ph_min");

        $datosFiltrados = array_filter(
            $data,
            function ($key) use ($campos) {
                return in_array($key, $campos);
            },
            ARRAY_FILTER_USE_KEY
        );

        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->update('peces', $datosFiltrados);

        $this->db->trans_complete();

        return $this->db->affected_rows();
    }
}
?>