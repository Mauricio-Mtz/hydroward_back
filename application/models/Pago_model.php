<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pago_model extends CI_Model
{
    public function insertar_direccion($usuario_id, $direccion, $ciudad, $estado, $cp)
    {
        $data = array(
            'direccion' => $direccion,
            'ciudad' => $ciudad,
            'estado' => $estado,
            'cp' => $cp
        );

        // Validar datos
        if (empty($usuario_id)) {
            return false;
        }

        // Verificar si ya existe una dirección para este usuario
        $existing_address = $this->db->get_where('usuarios', array('id' => $usuario_id))->row_array();

        if (!empty($existing_address)) {
            // Si la dirección ya existe, actualizarla
            $this->db->where('id', $usuario_id);
            $this->db->update('usuarios', $data);
        } else {
            // Si no existe, insertarla
            $data['id'] = $usuario_id;
            $this->db->insert('usuarios', $data);
        }

        return true;
    }
    public function obtener_direccion($usuario_id)
    {
        $this->db->select('direccion, ciudad, estado, cp');
        $this->db->where('id', $usuario_id);
        $query = $this->db->get('usuarios');

        return ($query->num_rows() > 0) ? $query->row_array() : false;
    }
    public function procesarProd($producto_id, $usuario_id, $cantidad, $monto)
    {
        // Verificar si hay suficiente stock
        $producto = $this->db->get_where('productos', array('id' => $producto_id))->row();
        if ($producto->stock >= $cantidad) {
            // Calcular monto total
            $monto_total = $monto;

            // Actualizar el stock
            $nuevo_stock = $producto->stock - $cantidad;
            $this->db->where('id', $producto_id);
            $this->db->update('productos', array('stock' => $nuevo_stock));

            // Registrar la venta
            $venta_data = array(
                'monto' => $monto_total,
                'fecha' => date('Y-m-d'),
                'usuario_id' => $usuario_id
            );
            $this->db->insert('venta', $venta_data);

            // Registrar el detalle de la venta
            $venta_id = $this->db->insert_id();
            $detalle_venta_data = array(
                'cantidad' => $cantidad,
                'monto' => $monto_total,
                'qr' => uniqid(), // Generar un código QR único, puedes implementar tu propia lógica aquí
                'producto_id' => $producto_id,
                'venta_id' => $venta_id
            );
            $this->db->insert('detalle_venta', $detalle_venta_data);

            return true;
        } else {
            return false; // No hay suficiente stock
        }
    }
    public function procesarRenovacion($producto_id, $usuario_id, $detalle_venta_id_anterior, $cantidad, $monto)
    {
        // Verificar si hay suficiente stock
        $producto = $this->db->get_where('productos', array('id' => $producto_id))->row();
        if ($producto->stock >= $cantidad) {
    
            // Actualizar el stock
            $nuevo_stock = $producto->stock - $cantidad;
            $this->db->where('id', $producto_id);
            $this->db->update('productos', array('stock' => $nuevo_stock));
    
            // Registrar la venta
            $venta_data = array(
                'monto' => $monto,
                'fecha' => date('Y-m-d'),
                'usuario_id' => $usuario_id
            );
            $this->db->insert('venta', $venta_data);
            // Obtener el ID de la venta después de la inserción
            $venta_id = $this->db->insert_id();
    
            // Obtener el QR del detalle de venta anterior
            $detalle_venta_anterior = $this->db->get_where('detalle_venta', array('id' => $detalle_venta_id_anterior))->row();
            $qr_anterior = $detalle_venta_anterior->qr;
    
            // Registrar el detalle de la venta
            $detalle_venta_data = array(
                'cantidad' => $cantidad,
                'monto' => $monto,
                'qr' => $qr_anterior, // Usar el QR del detalle de venta anterior
                'producto_id' => $producto_id,
                'venta_id' => $venta_id // Usar el ID de la venta recién insertada
            );
            $this->db->insert('detalle_venta', $detalle_venta_data);
            // Obtener el ID del detalle venta después de la inserción
            $detalle_venta_id_nuevo = $this->db->insert_id();
    
            // Actualizar el ID de detalle de venta y el estado en el registro de estanques
            $this->db->where('detalle_venta_id', $detalle_venta_id_anterior);
            $this->db->update('estanque', array('detalle_venta_id' => $detalle_venta_id_nuevo, 'status' => 1));
    
            return true;
        } else {
            return false; // No hay suficiente stock
        }
    }
    
    public function procesarCart($usuario_id)
    {
        $total_monto = 0;

        // Obtener productos del carrito para el usuario dado
        $carrito = $this->db->get_where('carrito_de_compras', array('usuario_id' => $usuario_id))->result_array();

        if (empty($carrito)) {
            return false; // El carrito está vacío para este usuario
        }

        foreach ($carrito as $item) {
            $producto_id = $item['producto_id'];
            $cantidad = $item['cantidad'];

            // Verificar si hay suficiente stock para cada producto en el carrito
            $producto = $this->db->get_where('productos', array('id' => $producto_id))->row();
            if ($producto->stock < $cantidad) {
                return false; // No hay suficiente stock para al menos uno de los productos en el carrito
            }

            // Calcular el monto total para la venta
            $monto_producto = $producto->precio * $cantidad;
            $total_monto += $monto_producto;

            // Actualizar el stock
            $nuevo_stock = $producto->stock - $cantidad;
            $this->db->where('id', $producto_id);
            $this->db->update('productos', array('stock' => $nuevo_stock));
        }

        // Registrar la venta
        $venta_data = array(
            'monto' => $total_monto,
            'fecha' => date('Y-m-d'),
            'usuario_id' => $usuario_id
        );
        $this->db->insert('venta', $venta_data);

        // Obtener el ID de la venta recién creada
        $venta_id = $this->db->insert_id();

        // Registrar el detalle de la venta para cada producto en el carrito
        foreach ($carrito as $item) {
            $producto_id = $item['producto_id'];
            $cantidad = $item['cantidad'];

            // Obtener el precio del producto
            $producto = $this->db->get_where('productos', array('id' => $producto_id))->row();
            $precio_producto = $producto->precio;

            // Calcular el monto para este producto
            $monto_producto = $precio_producto * $cantidad;

            $detalle_venta_data = array(
                'cantidad' => $cantidad,
                'monto' => $monto_producto,
                'qr' => ($producto->tipo == 'estanque') ? uniqid() : '', // Generar un código QR único solo para productos de tipo estanque
                'producto_id' => $producto_id,
                'venta_id' => $venta_id
            );
            $this->db->insert('detalle_venta', $detalle_venta_data);
        }

        // Limpiar el carrito después de la compra
        $this->db->where('usuario_id', $usuario_id);
        $this->db->delete('carrito_de_compras');

        return true;
    }


}
?>