<?php
defined('BASEPATH') or exit ('No direct script access allowed');
class UsuarioEstanque_model extends CI_Model
{
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
