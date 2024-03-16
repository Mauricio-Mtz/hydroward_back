<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuarios_model');
        $this->load->helper('form');
    }

    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        echo json_encode('Back-End del CRUD de usuarios HydroWard');
    }
    public function obtener_usuarios()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $usuarios = $this->Usuarios_model->obtener_usuarios();
        if ($usuarios) {
            $response = array(
                'success' => true,
                'message' => 'Usuarios obtenidos correctamente',
                'usuarios' => $usuarios
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se encontraron usuarios'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function obtener_usuario($id)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $usuario = $this->Usuarios_model->obtener_usuario($id);
        if ($usuario) {
            $response = array(
                'success' => true,
                'message' => 'Usuario obtenido correctamente',
                'usuario' => $usuario
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Usuario no encontrado'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    public function agregar_usuario()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');

        $nombre = $this->input->post('nombre');
        $apellido = $this->input->post('apellido');
        $telefono = $this->input->post('telefono');
        $correo = $this->input->post('correo');
        $contrasena = $this->input->post('contrasena');
        $tipo = $this->input->post('tipo');

        $result = $this->Usuarios_model->insertar_usuario($nombre, $apellido, $telefono, $correo, $contrasena, $tipo);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => 'Usuario agregado correctamente'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Error al agregar usuario'
            );
        }
        echo json_encode($response);
    }

    public function actualizar_usuario()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        $id = $this->input->post('id');
        $data = $this->input->post();

        $result = $this->Usuarios_model->actualizar_usuario($id, $data);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => 'Usuario actualizado correctamente'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Error al actualizar usuario'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }


    public function eliminar_usuario(/*$id, $action*/)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        $id = $this->input->post("id");
        $action = $this->input->post("action");

        $result = $this->Usuarios_model->eliminar_usuario($id, $action);

        if ($result) {
            $response = array(
                'success' => true,
                'message' => 'Usuario actualizado correctamente'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Error al actualizar usuario'
            );
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }


}
?>