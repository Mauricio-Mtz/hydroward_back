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
                c.usuario_id = ?
        ", array($usuario_id));

        return $query->result_array();
    }

    public function mod_actualizar_cantidad($idProd, $cant)
    {
        $query = $this->db->query("
            UPDATE carrito_de_compras
            SET cantidad = ?
            WHERE id = ?;
        ", array($cant, $idProd));
    
        return $query;
    }

    public function mod_eliminar_producto($idProd)
    {
        $query = $this->db->query("
            DELETE FROM carrito_de_compras
            WHERE id = ?;
        ", array($idProd));
    
        return $query;
    }   

    public function mod_eliminar_carrito($idUser)
    {
        $query = $this->db->query("
            DELETE FROM carrito_de_compras
            WHERE usuario_id = ?;
        ", array($idUser));
    
        return $query;
    }    
}
?>
