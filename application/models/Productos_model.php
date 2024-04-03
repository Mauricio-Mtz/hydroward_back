<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Productos_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insertar_producto($nombre, $descripcion, $precio, $tipo, $stock, $imagen)
    {
        $data = array(
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'tipo' => $tipo,
            'stock' => $stock,
            'status' => 1
        );

        $this->db->trans_start();
        $this->db->insert('productos', $data);
        $producto_id = $this->db->insert_id();

        $imagen_data = array(
            'nombre' => $imagen,
            'producto_id' => $producto_id
        );

        $this->db->insert('imagen', $imagen_data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return $producto_id;
        }
    }
    public function actualizar_producto($id, $nombre, $descripcion, $precio, $tipo, $stock, $imagen_nombre)
    {
        $data = array(
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'tipo' => $tipo,
            'stock' => $stock,
            'status' => 1
        );

        $campos = array('nombre', 'descripcion', 'precio', 'tipo', 'stock', 'status');

        $datosFiltrados = array_filter(
            $data,
            function ($key) use ($campos) {
                return in_array($key, $campos);
            },
            ARRAY_FILTER_USE_KEY
        );

        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->update('productos', $datosFiltrados);

        if ($imagen_nombre) {
            $this->db->where('producto_id', $id);
            $query = $this->db->get('imagen');
        
            if ($query->num_rows() > 0) {
                // Si existe una entrada, actualízala
                $this->db->set('nombre', $imagen_nombre);
                $this->db->where('producto_id', $id);
                $this->db->update('imagen');
            } else {
                // Si no existe ninguna entrada, inserta una nueva
                $this->db->insert('imagen', array('nombre' => $imagen_nombre, 'producto_id' => $id));
            }
        }
        

        $this->db->trans_complete();

        return $this->db->affected_rows();
    }
    public function obtener_productos()
    {
        $this->db->select('productos.id, productos.nombre, productos.descripcion, productos.precio, productos.tipo, productos.stock, productos.status');
        $this->db->from('productos');
        $productos = $this->db->get()->result_array();

        foreach ($productos as $i => $producto) {
            $this->db->select('imagen.id as imagen_id, imagen.nombre as nombre_imagen');
            $this->db->from('imagen');
            $this->db->where('imagen.producto_id', $producto['id']);
            $imagenes = $this->db->get()->result_array();

            $productos[$i]['imagen'] = $imagenes;
        }

        return $productos;
    }
    public function obtener_producto($id)
    {
        return $this->db->get_where('productos', array('id' => $id))->row_array();
    }
    public function eliminar_producto($id, $status)
    {
        $data = array(
            'status' => $status
        );

        $this->db->where('id', $id);
        $this->db->update('productos', $data);

        return $this->db->affected_rows();
    }
}
?>