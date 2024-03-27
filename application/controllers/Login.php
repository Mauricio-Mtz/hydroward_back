<?php
defined('BASEPATH') or exit ('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Login_model');
        $this->load->helper('form');
    }

    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        echo json_encode('Bienvenido al Back-End de HydroWard ESAU');
    }

    public function login()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $correo = $this->input->post('email');
        $contrasena = $this->input->post('password');

        $usuario = $this->Login_model->get_login($correo, $contrasena);

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
    public function loginApi()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $correo = $this->input->post('email');

        $usuario = $this->Login_model->get_login_api($correo);

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
        $usuario = $this->Login_model->obtenerUsuarioPorId($id);

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
    public function signIn()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        $nombre = $this->input->post('name');
        $apellido = $this->input->post('lastName');
        $telefono = $this->input->post('number');
        $correo = $this->input->post('email');
        $contrasena = $this->input->post('password');

        $registro = $this->Login_model->registrarUsuario($nombre, $apellido, $telefono, $correo, $contrasena);
        if ($registro) {
            $response = array(
                'success' => true,
                'message' => 'El registro se realizó con éxito',
                'data' => $registro
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Hubo un fallo en el registro'
            );
        }
        echo json_encode($response);
    }
}
