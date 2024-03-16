<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reportes extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        $this->load->model('Reportes_model');
    }
    
    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        echo json_encode('Back-End de los reportes en HydroWard');
    }
    public function productos($inicio, $fin)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $productosRep = $this->Reportes_model->getProductoRep($inicio, $fin);
        if ($productosRep) {
            $response = array(
                'success' => true,
                'message' => 'Reporte obtenido correctamente',
                'reporte' => $productosRep
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se logró realizar la consulta para el reporte'
            );
        }
        echo json_encode($response);
    }

    public function clientes($inicio, $fin)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $usuariosRep = $this->Reportes_model->getClienteRep($inicio, $fin);
        if ($usuariosRep) {
            $response = array(
                'success' => true,
                'message' => 'Reporte obtenido correctamente',
                'reporte' => $usuariosRep
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se logró realizar la consulta para el reporte'
            );
        }
        echo json_encode($response);
    }

    public function ventas($inicio, $fin)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $ventasRep = $this->Reportes_model->getVentaRep($inicio, $fin);
        if ($ventasRep) {
            $response = array(
                'success' => true,
                'message' => 'Reporte obtenido correctamente',
                'reporte' => $ventasRep
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se logró realizar la consulta para el reporte'
            );
        }
        echo json_encode($response);
    }
}
