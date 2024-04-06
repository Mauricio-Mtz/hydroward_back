<?php
defined('BASEPATH') or exit('No direct script access allowed');
class History extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('History_model');
        $this->load->helper('form');
    }
    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        echo json_encode('Back-End del Historial HydroWard');
    }
    public function obtenerHistorial($id)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $history = $this->History_model->historial($id);
        if ($history) {
            $response = array(
                'success' => true,
                'message' => 'Historial encontrado correctamente',
                'historial' => $history
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se encontró el historial'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
?>