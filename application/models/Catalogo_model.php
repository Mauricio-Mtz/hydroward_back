<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Catalogo_model extends CI_Model
{
    public function get_productos()
    {
        $this->db->select('productos.*, promociones.fecha_inicio, promociones.fecha_fin, promociones.descuento');
        $this->db->from('productos');
        $this->db->join('promociones', 'productos.id = promociones.producto_id', 'left');
        $this->db->where('productos.status', 1);
        $productos = $this->db->get()->result();
    
        foreach ($productos as &$producto) {
            $producto->imagenes = $this->get_imagenes_producto($producto->id);
            if (isset($producto->fecha_inicio) && isset($producto->fecha_fin)) {
                $producto->promocion = $producto->fecha_inicio . ' - ' . $producto->fecha_fin;
            } else {
                $producto->promocion = 'No tiene promoción';
            }
        }
    
        return $productos;
    }
    

    public function get_producto($id)
    {
        $this->db->select('productos.*, promociones.fecha_inicio, promociones.fecha_fin, promociones.descuento');
        $this->db->from('productos');
        $this->db->join('promociones', 'productos.id = promociones.producto_id', 'left');
        $this->db->where('productos.id', $id);
        $producto = $this->db->get()->row();
    
        if ($producto) {
            $producto->imagenes = $this->get_imagenes_producto($id);
        }
    
        return $producto;
    }
    

    private function get_imagenes_producto($producto_id)
    {
        return $this->db->get_where('imagen', array('producto_id' => $producto_id))->result();
    }

    public function agregarAlCarrito($idProducto, $idUsuario, $cantidad)
    {
        $query = $this->db->get_where('carrito_de_compras', array('producto_id' => $idProducto, 'usuario_id' => $idUsuario));

        if ($query->num_rows() > 0) {
            return false;
        } else {
            $data = array(
                'producto_id' => $idProducto,
                'usuario_id' => $idUsuario,
                'cantidad' => $cantidad
            );

            $this->db->insert('carrito_de_compras', $data);
            return true;
        }
    }
}
?>
