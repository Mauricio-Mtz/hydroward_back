<?php
class Carrito_model extends CI_Model
{
    public function obtener_carrito($usuario_id)
    {
        $query = $this->db->query("
            SELECT 
                c.id,
                p.id AS producto_id,
                p.nombre,
                (SELECT nombre FROM imagen WHERE producto_id = p.id LIMIT 1) AS imagen,
                p.precio,
                p.precio * c.cantidad AS total,
                c.cantidad,
                p.stock
            FROM 
                carrito_de_compras c
            JOIN 
                productos p ON c.producto_id = p.id
            WHERE 
                c.usuario_id = $usuario_id
        ");

        return $query->result_array();
    }
}
?>