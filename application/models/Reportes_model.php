<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reportes_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function getProductoRep($inicio, $fin)
    {
        // Crear o reemplazar la vista
        $create_view_sql = "
        CREATE OR REPLACE VIEW ProductoRep AS
        SELECT 
            productos.id, 
            productos.nombre, 
            SUM(detalle_venta.cantidad) as cantidad, 
            SUM(detalle_venta.cantidad * productos.precio) as total, 
            IF(
                (SELECT COUNT(*) 
                FROM promociones 
                WHERE promociones.producto_id = productos.id) > 0, 1, 0
            ) as promocion,
            (SELECT descuento 
            FROM promociones 
            WHERE promociones.producto_id = productos.id 
            ORDER BY id DESC 
            LIMIT 1) as descuento
        FROM 
            productos
        JOIN 
            detalle_venta ON productos.id = detalle_venta.producto_id
        JOIN 
            venta ON detalle_venta.venta_id = venta.id
        WHERE 
            venta.fecha >= '$inicio' AND venta.fecha <= '$fin'
        GROUP BY 
            productos.id, productos.nombre
        ORDER BY 
            cantidad DESC;
        ";
        $this->db->query($create_view_sql);
        
        $query = $this->db->get('ProductoRep');
        return $query->result();
        
    }
    public function getClienteRep($inicio, $fin)
    {
        $this->db->select('usuarios.nombre, usuarios.apellido, usuarios.correo, COUNT(venta.id) as compras, SUM(venta.monto) as total');
        $this->db->from('usuarios');
        $this->db->join('venta', 'usuarios.id = venta.usuario_id');
        $this->db->where('venta.fecha >=', $inicio);
        $this->db->where('venta.fecha <=', $fin);
        $this->db->group_by('usuarios.id');
        $this->db->order_by('total', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
    public function getVentaRep($inicio, $fin)
    {
        // Crear o reemplazar la vista
        $create_view_sql = "
        CREATE OR REPLACE VIEW VentaRep AS
        SELECT
            venta.id,
            venta.fecha,
            venta.monto as total,
            usuarios.nombre,
            usuarios.apellido
        FROM
            venta
        JOIN
            usuarios ON venta.usuario_id = usuarios.id
        ORDER BY
            total DESC
        ";
        $this->db->query($create_view_sql);

        // Hacer la consulta en la vista
        $this->db->where('fecha >=', $inicio);
        $this->db->where('fecha <=', $fin);
        $query = $this->db->get('VentaRep');
        return $query->result();
    }

}
