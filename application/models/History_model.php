<?php
class History_model extends CI_Model
{
    public function historial($id) {
        $this->db->select('venta.id as venta_id, venta.fecha, venta.monto');
        $this->db->from('venta');
        $this->db->where('venta.usuario_id', $id);
        $this->db->order_by('venta.id', 'DESC'); // Ordenar las ventas por fecha en orden descendente
        $ventas = $this->db->get()->result_array();
    
        $qr_registrados = array(); // Almacena los QRs procesados
        $ventas_filtradas = array(); // Almacena las ventas filtradas
    
        foreach ($ventas as $venta) {
            $this->db->select('productos.id, productos.nombre, detalle_venta.cantidad, productos.precio, productos.tipo, detalle_venta.qr, detalle_venta.id as detalle_venta_id');
            $this->db->from('detalle_venta');
            $this->db->join('productos', 'detalle_venta.producto_id = productos.id');
            $this->db->where('detalle_venta.venta_id', $venta['venta_id']);
            $productos = $this->db->get()->result_array();
    
            // Agregar QR solo si el producto es de tipo "estanques"
            foreach ($productos as $i => $producto) {
                if ($producto['tipo'] === 'estanques') {
                    // Si el QR ya fue procesado, eliminar el producto del array
                    if (in_array($producto['qr'], $qr_registrados)) {
                        unset($productos[$i]);
                        continue;
                    }
    
                    $producto['qr'] = $producto['qr'];
                    $qr_registrados[] = $producto['qr']; // Agregar el QR a la lista de QRs procesados
    
                    // Consultar la tabla estanques para obtener el estado de la suscripción
                    $this->db->select('status');
                    $this->db->from('estanque');
                    $this->db->where('detalle_venta_id', $producto['detalle_venta_id']);
                    $estanque = $this->db->get()->row_array();
    
                    if (empty($estanque)) {
                        $producto['suscripcion'] = 'no registrado';
                    } else {
                        $producto['suscripcion'] = $estanque['status'] ? 'activo' : 'inactivo';
                    }
                } else {
                    unset($producto['qr']);
                }
                $productos[$i] = $producto; // Actualizar el producto con la información de la suscripción
            }
    
            // Si después de filtrar los productos aún quedan productos en la venta, agregar la venta a las ventas filtradas
            if (!empty($productos)) {
                $venta['productos'] = $productos;
                $ventas_filtradas[] = $venta;
            }
        }
    
        return $ventas_filtradas;
    }
    
    
    
}
?>