<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
    public function actualizar_perfil(/*$id, $nombre, $apellido, $telefono, $correo, $contrasena, $imagen_nombre*/)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');
        $apellido = $this->input->post('apellido');
        $telefono = $this->input->post('telefono');
        $correo = $this->input->post('correo');
        $contrasena = $this->input->post('contrasena');

        $config['upload_path'] = 'static/usuarios/';
        $config['allowed_types'] = 'gif|jpg|png|webp';
        $this->load->library('upload', $config);

        $imagen_nombre = null;

        if (isset($_FILES['imagen'])) {
            $imagen = $_FILES['imagen']['name'];

            if (!file_exists($config['upload_path'] . $imagen)) {
                if (!$this->upload->do_upload('imagen')) {
                    $imagen_nombre = "Error en la imagen";
                } else {
                    $data = $this->upload->data();
                    $imagen_nombre = $data['file_name'];
                }
            } else {
                $imagen_nombre = $imagen;
            }
        }

        $result = $this->Login_model->actualizar_perfil($id, $nombre, $apellido, $telefono, $correo, $contrasena, $imagen_nombre);
        if ($result >= 0) {
            $response = array(
                'success' => true,
                'message' => 'Usuario actualizado correctamente',
                'imagen' => $imagen_nombre
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Error al actualizar perfil'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    public function actualizar_direccion()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        $id = $this->input->post('id');
        $direccion = $this->input->post('direccion');
        $ciudad = $this->input->post('ciudad');
        $estado = $this->input->post('estado');
        $cp = $this->input->post('cp');

        $result = $this->Login_model->actualizar_direccion($id, $direccion, $ciudad, $estado, $cp);
        if ($result >= 0) {
            $response = array(
                'success' => true,
                'message' => 'Dirección actualizado correctamente'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Error al actualizar la direcci+on'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
