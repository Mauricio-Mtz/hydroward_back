<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Alertas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Alertas_model');
        $this->load->helper('form');
    }

    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        echo json_encode('Back-End del CRUD de alertas HydroWard');
    }

    public function obtener_alertas()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        $idUser = $this->input->post("idUser");

        $alertas = $this->Alertas_model->obtener_alertas($idUser);
        if ($alertas) {
            $response = array(
                'success' => true,
                'message' => 'Alertas obtenidos correctamente',
                'alertas' => $alertas
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se encontraron alertas'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }


    public function agregar_alertas()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        $mensaje = $this->input->post('mensaje');
        $idUser = $this->input->post('idUser');

        $result = $this->Alertas_model->insertar_alertas($mensaje, $idUser);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => 'Alerta agregada correctamente'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Error al agregar alerta'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }


    public function eliminar_alerta($id)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        // $id = $this->input->post("id");

        $result = $this->Alertas_model->eliminar_alerta($id);

        if ($result) {
            $response = array(
                'success' => true,
                'message' => 'Alerta eliminado correctamente'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Error al eliminar alerta'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
?>