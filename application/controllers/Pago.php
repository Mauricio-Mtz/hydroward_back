<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pago extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pago_model');
        $this->load->helper('form');
    }
    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        echo json_encode('Back-End del proceso de pago HydroWard');
    }
    public function insertar_direccion()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');

        $id = $this->input->post('id');
        $direccion = $this->input->post('direccion');
        $ciudad = $this->input->post('ciudad');
        $estado = $this->input->post('estado');
        $cp = $this->input->post('cp');

        if (empty($id)) {
            $this->output->set_status_header(400);
            echo json_encode(array('success' => false, 'message' => 'El ID del usuario es obligatorio.'));
            return;
        }

        $resultado = $this->Pago_model->insertar_direccion($id, $direccion, $ciudad, $estado, $cp);

        if ($resultado !== false) {
            echo json_encode(array('success' => true, 'message' => 'Dirección insertada/actualizada correctamente.'));
        } else {
            $this->output->set_status_header(500);
            echo json_encode(array('success' => false, 'message' => 'Error al insertar/actualizar la dirección.'));
        }
    }
    public function obtener_direccion($id)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        // Obtener dirección
        $direccion = $this->Pago_model->obtener_direccion($id);

        if ($direccion) {
            echo json_encode(array('success' => true, 'message' => 'Dirección obtenida correctamente.', 'direccion' => $direccion));
        } else {
            $this->output->set_status_header(404);
            echo json_encode(array('success' => false, 'message' => "No se encontró la dirección para el usuario con ID $id"));
        }
    }
    public function comprarProd(/*$producto_id, $usuario_id, $cantidad, $monto*/)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $producto_id = $this->input->post('producto_id');
        $usuario_id = $this->input->post('usuario_id');
        $cantidad = $this->input->post('cantidad');
        $monto = $this->input->post('monto');

        $venta_realizada = $this->Pago_model->procesarProd($producto_id, $usuario_id, $cantidad, $monto);
        if ($venta_realizada) {
            $response = array(
                'success' => true,
                'message' => 'La compra se realizó con exito',
                'data' => $venta_realizada
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Hubo un fallo en la compra'
            );
        }
        echo json_encode($response);
    }
    public function renovar(/*$producto_id, $usuario_id,$detalle_venta, $cantidad, $monto*/)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $producto_id = $this->input->post('producto_id');
        $usuario_id = $this->input->post('usuario_id');
        $cantidad = $this->input->post('cantidad');
        $detalle_venta = $this->input->post('detalle_venta');
        $monto = $this->input->post('monto');

        $renovacionRealizada = $this->Pago_model->procesarRenovacion($producto_id, $usuario_id, $detalle_venta, $cantidad, $monto);
        if ($renovacionRealizada) {
            $response = array(
                'success' => true,
                'message' => 'La renovación se realizó con exito',
                'data' => $renovacionRealizada
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Hubo un fallo en la renovación'
            );
        }
        echo json_encode($response);
    }

    public function comprarCart(/*$usuario_id*/)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $usuario_id = $this->input->post('usuario_id');
        
        $venta_realizada = $this->Pago_model->procesarCart($usuario_id);
        if ($venta_realizada) {
            $response = array(
                'success' => true,
                'message' => 'La compra del carrito se realizó con éxito',
                'data' => $venta_realizada
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Hubo un fallo en la compra del carrito'
            );
        }
        echo json_encode($response);
    }
    
}
?>