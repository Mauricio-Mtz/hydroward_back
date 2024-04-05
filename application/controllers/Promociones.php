<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promociones extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Promociones_model');
        $this->load->helper('form');
    }

    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        echo json_encode('Back-End del CRUD de promociones HydroWard');
    }
    public function agregar_promocion(/*$fecha_inicio, $fecha_fin, $descuento, $producto_id*/)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin = $this->input->post('fecha_fin');
        $descuento = $this->input->post('descuento');
        $producto_id = $this->input->post('producto_id');

        $result = $this->Promociones_model->insertar_promocion($fecha_inicio, $fecha_fin, $descuento, $producto_id);

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function eliminar_promocion($id)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $cambio = $this->Promociones_model->delete_promocion($id);
        if ($cambio >= 0) {
            $response = array(
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Error al cambiar el estado',
                'resuldatos' => $cambio
            );
        }
        echo json_encode($response);
    }
}
?>