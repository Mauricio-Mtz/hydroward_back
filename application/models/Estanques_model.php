<?php
defined('BASEPATH') or exit ('No direct script access allowed');
class Estanques_model extends CI_Model
{
    public function obtenerEstanquesPorId($idUser) {
        // Realizar la consulta utilizando el ORM de CodeIgniter
        $this->db->select('*');
        $this->db->from('estanque');
        $this->db->join('usuario_estanque', 'estanque.id = usuario_estanque.estanque_id');
        $this->db->where('usuario_estanque.usuario_id', $idUser);
        $query = $this->db->get();

        // Retornar los resultados de la consulta
        return $query->result();
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
            $venta_id = $dv['id'];
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



    public function registrarEstanque($nombre, $pez, $idVenta)
    {
        // Obtenemos los datos del pez desde la tabla de peces
        $this->db->where('id', $pez);
        $pezData = $this->db->get('peces')->row_array();

        // Construimos el array de datos para insertar en la tabla de estanques
        $data = array(
            'nombre' => $nombre,
            'alimentacion' => $pezData['alimentacion'],
            'tiempo_no_alim' => $pezData['tiempo_no_alim'],
            'tiempo_si_alim' => $pezData['tiempo_si_alim'],
            'temperatura_min' => $pezData['temperatura_min'],
            'temperatura_max' => $pezData['temperatura_max'],
            'ph_min' => $pezData['ph_min'],
            'ph_max' => $pezData['ph_max'],
            'detalle_venta_id' => $idVenta
        );

        // Insertamos los datos en la tabla de estanques
        $this->db->insert('estanque', $data);

        // Retornamos el ID del estanque insertado
        return $this->db->insert_id();
    }
    public function asignarEstanqueUsuario($idUser, $idEstanque)
    {
        $data = array(
            'usuario_id' => $idUser,
            'estanque_id' => $idEstanque,
        );
        $this->db->insert('usuario_estanque', $data);
        return $this->db->insert_id();
    }

}
