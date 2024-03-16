<?php
defined('BASEPATH') or exit ('No direct script access allowed');
class Estanques_model extends CI_Model
{
    public function obtenerEstanquesPorId($id)
    {
        $this->db->select('e.nombre AS nombre, e.id AS id, e.temperatura_min AS temp_min, 
        e.temperatura_max as temp_max, e.tiempo_no_alim AS no_alim, e.tiempo_si_alim AS si_alim,
        e.ph_min,e.ph_max,e.peces_id,e.detalle_venta_id,e.alimentacion, e.cantidad AS cantidad');
        $this->db->from('estanque e');
        $this->db->join('detalle_venta dv', 'dv.id = e.detalle_venta_id');
        $this->db->join('venta v', 'v.id = dv.venta_id');
        $this->db->join('usuarios u', 'v.usuario_id = u.id');
        $this->db->where('e.status', 1);
        $this->db->where('u.id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function obtenerEstanquePorId($id)
    {
        $this->db->select('e.nombre AS nombre, e.id AS id, e.temperatura_min AS temp_min, 
    e.temperatura_max as temp_max, e.tiempo_no_alim AS no_alim, e.tiempo_si_alim AS si_alim,
    e.ph_min,e.ph_max,e.peces_id,e.detalle_venta_id,e.alimentacion, e.cantidad AS cantidad');
        $this->db->from('estanque as e');
        $this->db->where('e.id', $id);
        $this->db->where('e.status', 1);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_qr_code($qrCode)
    {
        $query = $this->db->get_where('detalle_venta', array('qr' => $qrCode));
        if ($query && $query->num_rows() > 0) {
            $dv = $query->row_array();
            $venta_id = $dv['venta_id'];
            return $venta_id;
        }
        return null;
    }
    public function registrarNombreE($nombre, $idVenta)
    {
        $data = array(
            'nombre' => $nombre,
            'detalle_venta_id' => $idVenta,
        );
        $this->db->insert('estanque', $data);
        return $this->db->insert_id();
    }
    public function editarE($nombre, $id, $alim, $tMin, $tMax, $nA, $sA, $pMin, $pMax, $cantidad, $pez)
    {
        $data = array(
            'nombre' => $nombre,
            'alimentacion' => $alim,
            'temperatura_min' => $tMin,
            'temperatura_max' => $tMax,
            'ph_max' => $pMax,
            'ph_min' => $pMin,
            'tiempo_no_alim' => $nA,
            'tiempo_si_alim' => $sA,
            'cantidad' => $cantidad,
            'peces_id' => $pez,
        );
        $this->db->where('id', $id);
        $this->db->update('estanque', $data);
        return $this->db->affected_rows() > 0 ? $id : false;
    }
    public function get_fish()
    {
        $this->db->select('*');
        $this->db->from('peces');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function registrarPezE($id_pez, $estanque)
    {
        $pez = $this->db->get_where('peces', array('id' => $id_pez))->row_array();
        if ($pez) {
            $data = array(
                'alimentacion' => $pez['alimentacion'],
                'tiempo_no_alim' => $pez['tiempo_no_alim'],
                'tiempo_si_alim' => $pez['tiempo_si_alim'],
                'temperatura_max' => $pez['temperatura_max'],
                'temperatura_min' => $pez['temperatura_min'],
                'ph_min' => $pez['ph_min'],
                'ph_max' => $pez['ph_max'],
                'peces_id' => $id_pez,
            );
            $this->db->where('id', $estanque);
            $this->db->update('estanque', $data);

            return $this->db->affected_rows() > 0;
        } else {
            return false;
        }
    }
}
