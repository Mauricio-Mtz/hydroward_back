<?php
class Carrito extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('carrito_model');
    }
    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        echo json_encode('Bienvenido al Back-End del carrito de compras');
    }
    public function obtener_carrito($id)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $carrito = $this->carrito_model->obtener_carrito($id);
        if ($carrito) {
            $response = array(
                'success' => true,
                'message' => 'Carrito obtenido correctamente',
                'carrito' => $carrito
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se encontro el carrito'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
?>