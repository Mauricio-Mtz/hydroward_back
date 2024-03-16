<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function getProductoRep($inicio, $fin) {
        $this->db->select('productos.nombre, SUM(detalle_venta.cantidad) as cantidad, SUM(detalle_venta.cantidad * productos.precio) as total');
        $this->db->from('productos');
        $this->db->join('detalle_venta', 'productos.id = detalle_venta.producto_id');
        $this->db->join('venta', 'detalle_venta.venta_id = venta.id');
        $this->db->where('venta.fecha >=', $inicio);
        $this->db->where('venta.fecha <=', $fin);
        $this->db->group_by('productos.nombre');
        $this->db->order_by('cantidad', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function getClienteRep($inicio, $fin) {
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
    
    public function getVentaRep($inicio, $fin) {
        $this->db->select('venta.id, venta.fecha, venta.monto as total, usuarios.nombre, usuarios.apellido');
        $this->db->from('venta');
        $this->db->join('usuarios', 'venta.usuario_id = usuarios.id');
        $this->db->where('venta.fecha >=', $inicio);
        $this->db->where('venta.fecha <=', $fin);
        $this->db->order_by('total', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
   
}
