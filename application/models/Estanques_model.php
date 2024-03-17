<?php
defined('BASEPATH') or exit ('No direct script access allowed');
class Estanques_model extends CI_Model
{
    public function obtenerEstanquesPorId($idUser)
    {
        // Realizar la consulta utilizando el ORM de CodeIgniter
        $this->db->select('*');
        $this->db->from('estanque');
        $this->db->join('usuario_estanque', 'estanque.id = usuario_estanque.estanque_id');
        $this->db->where('usuario_estanque.usuario_id', $idUser);
        $query = $this->db->get();

        // Retornar los resultados de la consulta
        return $query->result();
    }
    public function obtenerEstanquePorId($idEstanque)
    {
        $this->db->select('e.nombre AS nombre, e.id AS id, e.temperatura_min AS temp_min, 
    e.temperatura_max as temp_max, e.tiempo_no_alim AS no_alim, e.tiempo_si_alim AS si_alim,
    e.ph_min,e.ph_max,e.detalle_venta_id,e.alimentacion');
        $this->db->from('estanque as e');
        $this->db->where('e.id', $idEstanque);
        $this->db->where('e.status', 1);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_qr_code($qrCode, $userId)
    {
        // Busca en la tabla detalle_venta utilizando el código QR
        $detalleVentaQuery = $this->db->get_where('detalle_venta', array('qr' => $qrCode));

        // Si se encuentra el registro en la tabla detalle_venta
        if ($detalleVentaQuery->num_rows() > 0) {
            // Obtiene la ID de detalle de venta
            $detalleVenta = $detalleVentaQuery->row();
            $detalleVentaId = $detalleVenta->id;

            // Verifica si la ID de detalle de venta existe en la tabla estanques
            $estanqueQuery = $this->db->get_where('estanque', array('detalle_venta_id' => $detalleVentaId));

            // Si se encuentra en la tabla estanques
            if ($estanqueQuery->num_rows() > 0) {
                // Realiza el insert en la tabla usuario_estanque
                $this->db->insert('usuario_estanque', array('usuario_id' => $userId, 'estanque_id' => $estanqueQuery->row()->id));

                // Devuelve la ID de detalle de venta y que existe en estanques
                return array('detalleVentaId' => $detalleVentaId, 'existeEnEstanques' => true);
            } else {
                // Devuelve la ID de detalle de venta pero no existe en estanques
                return array('detalleVentaId' => $detalleVentaId, 'existeEnEstanques' => false);
            }
        }

        // Si no se encuentra en detalle_venta, devuelve null
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
        $this->db->where('id', $pez);
        $pezData = $this->db->get('peces')->row_array();

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

        $this->db->insert('estanque', $data);
        return $this->db->insert_id();
    }
    public function registrarEstanqueManual($nombre, $alimentacion, $noAlim, $siAlim, $tempMin, $tempMax, $phMin, $phMax, $idVenta)
    {
        $data = array(
            'nombre' => $nombre,
            'alimentacion' => $alimentacion,
            'tiempo_no_alim' => $noAlim,
            'tiempo_si_alim' => $siAlim,
            'temperatura_min' => $tempMin,
            'temperatura_max' => $tempMax,
            'ph_min' => $phMin,
            'ph_max' => $phMax,
            'detalle_venta_id' => $idVenta
        );

        $this->db->insert('estanque', $data);
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
