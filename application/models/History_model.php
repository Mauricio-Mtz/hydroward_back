<?php
class History_model extends CI_Model
{
    public function historial($id) {
        $this->db->select('venta.id as venta_id, venta.fecha, venta.monto');
        $this->db->from('venta');
        $this->db->where('venta.usuario_id', $id);
        $ventas = $this->db->get()->result_array();
    
        foreach ($ventas as $i => $venta) {
            $this->db->select('productos.id, productos.nombre, detalle_venta.cantidad, productos.precio, productos.tipo, detalle_venta.qr');
            $this->db->from('detalle_venta');
            $this->db->join('productos', 'detalle_venta.producto_id = productos.id');
            $this->db->where('detalle_venta.venta_id', $venta['venta_id']);
            $productos = $this->db->get()->result_array();
    
            // Agregar QR solo si el producto es de tipo "estanques"
            foreach ($productos as &$producto) {
                if ($producto['tipo'] === 'estanques') {
                    $producto['qr'] = $producto['qr'];
                } else {
                    unset($producto['qr']);
                }
            }
    
            $ventas[$i]['productos'] = $productos;
        }
    
        return $ventas;
    }
}
?>