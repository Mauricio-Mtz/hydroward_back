<?php
defined('BASEPATH') or exit ('No direct script access allowed');

class UsuarioEstanque extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UsuarioEstanque_model');
        $this->load->helper('form');
    }

    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        echo json_encode('Bienvenido al Back-End de HydroWard ESAU');
    }
    public function obtenerUsuariosConEstanques()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $usuarios = $this->UsuarioEstanque_model->getUsuariosConEstanques();
        if ($usuarios) {
            $response = array(
                'success' => true,
                'message' => 'Datos de los usuarios obtenidos correctamente',
                'usuarios' => $usuarios
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se encontraron usuarios'
            );
        }
        echo json_encode($response);
    }
}
