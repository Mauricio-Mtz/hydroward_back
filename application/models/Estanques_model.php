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
        $detalleVentaQuery = $this->db->get_where('detalle_venta', array('qr' => $qrCode));

        if ($detalleVentaQuery->num_rows() > 0) {
            $detalleVenta = $detalleVentaQuery->row();
            $detalleVentaId = $detalleVenta->id;

            $estanqueQuery = $this->db->get_where('estanque', array('detalle_venta_id' => $detalleVentaId));

            if ($estanqueQuery->num_rows() > 0) {
                $estanque = $estanqueQuery->row();
                $estanqueId = $estanque->id;

                $existingEstanqueQuery = $this->db->get_where('usuario_estanque', array('usuario_id' => $userId, 'estanque_id' => $estanqueId));

                if ($existingEstanqueQuery->num_rows() > 0) {
                    return array('detalleVentaId' => $detalleVentaId, 'existeEnEstanques' => true, 'mensaje' => 'El usuario ya tiene este estanque asignado.');
                } else {
                    $this->db->insert('usuario_estanque', array('usuario_id' => $userId, 'estanque_id' => $estanqueId));
                    return array('detalleVentaId' => $detalleVentaId, 'existeEnEstanques' => true);
                }
            } else {
                return array('detalleVentaId' => $detalleVentaId, 'existeEnEstanques' => false);
            }
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
    public function editarE($nombre, $id, $alim, $tMin, $tMax, $nA, $sA, $pMin, $pMax)
    {
        $data = array(
            'nombre' => $nombre,
            'alimentacion' => $alim,
            'temperatura_min' => $tMin,
            'temperatura_max' => $tMax,
            'ph_max' => $pMax,
            'ph_min' => $pMin,
            'tiempo_no_alim' => $nA,
            'tiempo_si_alim' => $sA
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
    public function getUsuariosConEstanques()
    {
        // Consulta SQL para obtener usuarios con estanques asignados
        $sql = "SELECT usuarios.id, usuarios.nombre, usuarios.apellido, usuarios.correo, usuarios.telefono, GROUP_CONCAT(estanque_id) as estanques_ids
            FROM usuarios
            INNER JOIN usuario_estanque ON usuarios.id = usuario_estanque.usuario_id
            GROUP BY usuarios.id";

        // Ejecutar la consulta
        $query = $this->db->query($sql);

        // Verificar si se encontraron resultados
        if ($query->num_rows() > 0) {
            // Obtener los resultados como objetos de usuario
            $usuarios = $query->result();

            // Para cada usuario, obtener los detalles de los estanques
            foreach ($usuarios as $usuario) {
                // Obtener los IDs de los estanques asignados al usuario
                $estanques_ids = explode(',', $usuario->estanques_ids);

                // Consultar los detalles de los estanques
                $estanques = $this->db->select('id, nombre')
                    ->from('estanque')
                    ->where_in('id', $estanques_ids)
                    ->get()
                    ->result();

                // Agregar los estanques al objeto de usuario
                $usuario->estanques = $estanques;
            }

            // Devolver los usuarios con los detalles de los estanques
            return $usuarios;
        }

        // Si no se encontraron resultados, devolver un arreglo vacío
        return [];
    }
}
