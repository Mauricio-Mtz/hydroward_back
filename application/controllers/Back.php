<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Back extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->load->helper('form');
    }

    public function index()
    {
        echo json_encode('Bienvenido al Back-End de HydroWard');
    }

    public function login()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $correo = $this->input->post('email');
        $contrasena = $this->input->post('password');

        $usuario = $this->Usuario_model->get_login($correo, $contrasena);

        if ($usuario) {
            $response = array(
                'success' => true,
                'message' => 'Usuario autenticado correctamente',
                'usuario' => $usuario,
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Correo o contraseña incorrectos'
            );
        }
        echo json_encode($response);
    }
    public function obtenerUsuario()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $id = $this->input->post('id');
        $usuario = $this->Usuario_model->obtenerUsuarioPorId($id);

        if ($usuario) {
            $response = array(
                'success' => true,
                'message' => 'Datos del usuario obtenidos correctamente',
                'user' => $usuario
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Usuario no encontrado'
            );
        }
        echo json_encode($response);
    }




}
