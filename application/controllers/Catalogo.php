<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Catalogo extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Catalogo_model');
        $this->load->helper('form');
    }
    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        echo json_encode('Back-End del catálogo HydroWard');
    }
    public function productos()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $productos = $this->Catalogo_model->get_productos();
        if ($productos) {
            $response = array(
                'success' => true,
                'message' => 'Productos obtenidos correctamente',
                'productos' => $productos
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se encontraron productos'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function producto($id)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $producto = $this->Catalogo_model->get_producto($id);
        if ($producto) {
            $response = array(
                'success' => true,
                'message' => 'Producto obtenido correctamente',
                'producto' => $producto
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se encontro ese producto'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    public function agregar()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $idProducto = $this->input->post('idProducto');
        $idUsuario = $this->input->post('idUsuario');
        $cantidad = $this->input->post('cantidad');

        $resCarrito = $this->Catalogo_model->agregarAlCarrito($idProducto, $idUsuario, $cantidad);
        if ($resCarrito) {
            $response = array(
                'success' => true,
                'message' => 'Producto agregado correctamente',
                'producto' => $resCarrito
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se agregó el producto'
            );
        }
        echo json_encode($response);
    }
}
?>