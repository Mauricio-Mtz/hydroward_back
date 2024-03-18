<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Peces extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Peces_model');
        $this->load->helper('form');
    }

    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        echo json_encode('Back-End del CRUD de peces HydroWard');
    }

    public function obtener_peces()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $peces = $this->Peces_model->obtener_peces();
        if ($peces) {
            $response = array(
                'success' => true,
                'message' => 'Peces obtenidos correctamente',
                'peces' => $peces
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se encontraron los peces'
            );
        }
        echo json_encode($response);
    }

    public function obtener_pez($id)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $pez = $this->Peces_model->obtener_pez($id);
        if ($pez) {
            $response = array(
                'success' => true,
                'message' => 'Pez obtenido correctamente',
                'pez' => $pez
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Pez no encontrado'
            );
        }
        echo json_encode($response);
    }

    public function agregar_pez()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');

        $nombre = $this->input->post('nombre');
        $alimentacion = $this->input->post('alimentacion');
        $noAlim = $this->input->post('tiempo_no_alim');
        $siAlim = $this->input->post('tiempo_si_alim');
        $tempMin = $this->input->post('temperatura_min');
        $tempMax = $this->input->post('temperatura_max');
        $phMin = $this->input->post('ph_min');
        $phMax = $this->input->post('ph_max');

        $result = $this->Peces_model->insertar_pez($nombre, $alimentacion, $siAlim, $noAlim, $tempMin, $tempMax, $phMin, $phMax);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => 'Pez agregado correctamente'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Error al agregar pez'
            );
        }
        echo json_encode($response);
    }

    public function actualizar_pez()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');

        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');
        $alimentacion = $this->input->post('alimentacion');
        $noAlim = $this->input->post('tiempo_no_alim');
        $siAlim = $this->input->post('tiempo_si_alim');
        $tempMin = $this->input->post('temperatura_min');
        $tempMax = $this->input->post('temperatura_max');
        $phMin = $this->input->post('ph_min');
        $phMax = $this->input->post('ph_max');

        $result = $this->Peces_model->actualizar_pez($id, $nombre, $alimentacion, $siAlim, $noAlim, $tempMin, $tempMax, $phMin, $phMax);
        if ($result >= 0) {
            $message = $result > 0 ? 'Pez actualizado correctamente' : 'No se realizaron cambios';
            $response = array(
                'success' => true,
                'message' => $message
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Error al actualizar pez'
            );
        }
        echo json_encode($response);
    }


    public function eliminar_pez()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');

        $id = $this->input->post("id");
        $action = $this->input->post("action");

        $result = $this->Peces_model->eliminar_pez($id, $action);

        if ($result) {
            $response = array(
                'success' => true,
                'message' => 'Producto eliminado correctamente'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Error al eliminar producto'
            );
        }

        echo json_encode($response);
    }
}
?>