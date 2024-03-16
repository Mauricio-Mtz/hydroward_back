<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Catalogo_model extends CI_Model
{
    public function get_productos()
    {
        // Obtener solo los productos con status = 1 del catálogo
        $this->db->where('status', 1);
        $productos = $this->db->get('productos')->result();

        // Para cada producto, obtener sus imágenes
        foreach ($productos as $producto) {
            // Obtener las imágenes asociadas a cada producto
            $imagenes = $this->db->get_where('imagen', array('producto_id' => $producto->id))->result();
            $producto->imagenes = $imagenes;
        }

        return $productos;
    }
    public function get_producto($id)
    {
        // Obtener el producto específico por su ID
        $producto = $this->db
            ->get_where('productos', array('id' => $id))
            ->row();

        if ($producto) {
            // Obtener las imágenes asociadas al producto
            $imagenes = $this->db
                ->get_where('imagen', array('producto_id' => $id))
                ->result();

            // Asignar el array de objetos de imágenes al campo 'imagenes' del producto
            $producto->imagenes = $imagenes;
        }

        return $producto;
    }
    public function agregarAlCarrito($idProducto, $idUsuario, $cantidad) {
        // Verificar si el producto ya está en el carrito del usuario
        $this->db->where('producto_id', $idProducto);
        $this->db->where('usuario_id', $idUsuario);
        $query = $this->db->get('carrito_de_compras');

        // Si ya existe el producto en el carrito del usuario, no se agrega
        if ($query->num_rows() > 0) {
            return false;
        } else {
            // Insertar el producto en el carrito del usuario
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