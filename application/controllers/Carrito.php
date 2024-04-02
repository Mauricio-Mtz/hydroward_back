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
        echo json_encode($response);
    }

    public function actualizar_cantidad()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');

        $idProd = $this->input->post('idProd');
        $cant = $this->input->post('cant');
        
        $result = $this->carrito_model->mod_actualizar_cantidad($idProd, $cant);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => 'Carrito actualizado correctamente'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se logró actualizar correctamente'
            );
        }
        echo json_encode($response);
    }
    public function eliminar_producto()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');

        $idProd = $this->input->post('idProd');

        $result = $this->carrito_model->mod_eliminar_producto($idProd);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => 'Producto eliminado correctamente'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se logró eliminar el producto'
            );
        }
        echo json_encode($response);
    }
    public function eliminar_carrito()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');

        $idUser = $this->input->post('idUser');

        $result = $this->carrito_model->mod_eliminar_carrito($idUser);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => 'Carrito eliminado correctamente'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se logró eliminar el carrito'
            );
        }
        echo json_encode($response);
    }
}
?>