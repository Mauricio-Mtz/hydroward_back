<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Productos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Productos_model');
        $this->load->helper('form');
    }

    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        echo json_encode('Back-End del CRUD de productos HydroWard');
    }

    public function obtener_productos()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $productos = $this->Productos_model->obtener_productos();
        if ($productos) {
            $response = array(
                'success' => true,
                'message' => 'Productos obtenidos correctamente',
                'productos' => $productos
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se encontraron productos'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function obtener_producto($id)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $producto = $this->Productos_model->obtener_producto($id);
        if ($producto) {
            $response = array(
                'success' => true,
                'message' => 'Producto obtenido correctamente',
                'producto' => $producto
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Producto no encontrado'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function agregar_producto()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');

        $nombre = $this->input->post('nombre');
        $descripcion = $this->input->post('descripcion');
        $precio = $this->input->post('precio');
        $tipo = $this->input->post('tipo');
        $stock = $this->input->post('stock');

        $config['upload_path'] = './static/images/';
        $config['allowed_types'] = 'gif|jpg|png|webp';
        $this->load->library('upload', $config);

        $imagen_nombre = null;

        if (isset($_FILES['imagen'])) {
            $imagen = $_FILES['imagen']['name'];

            if (!file_exists($config['upload_path'] . $imagen)) {
                if (!$this->upload->do_upload('imagen')) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->output->set_output(json_encode([
                        'success' => false,
                        'message' => 'error en la imagen',
                        'error' => $error
                    ]));
                    return;
                } else {
                    $data = $this->upload->data();
                    $imagen_nombre = $data['file_name'];
                }
            } else {
                $imagen_nombre = $imagen;
            }
        }

        $result = $this->Productos_model->insertar_producto($nombre, $descripcion, $precio, $tipo, $stock, $imagen_nombre);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => 'Producto agregado correctamente'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Error al agregar producto'
            );
        }
        echo json_encode($response);
    }

    public function actualizar_producto()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');

        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');
        $descripcion = $this->input->post('descripcion');
        $precio = $this->input->post('precio');
        $tipo = $this->input->post('tipo');
        $stock = $this->input->post('stock');

        $config['upload_path'] = './static/images/';
        $config['allowed_types'] = 'gif|jpg|png|webp';
        $this->load->library('upload', $config);

        $imagen_nombre = null;

        if (isset($_FILES['imagen'])) {
            $imagen = $_FILES['imagen']['name'];

            if (!file_exists($config['upload_path'] . $imagen)) {
                if (!$this->upload->do_upload('imagen')) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->output->set_output(json_encode([
                        'success' => false,
                        'message' => 'error en la imagen',
                        'error' => $error
                    ]));
                    return;
                } else {
                    $data = $this->upload->data();
                    $imagen_nombre = $data['file_name'];
                }
            } else {
                $imagen_nombre = $imagen;
            }
        }

        $result = $this->Productos_model->actualizar_producto($id, $nombre, $descripcion, $precio, $tipo, $stock, $imagen_nombre);
        if ($result >= 0) {
            $response = array(
                'success' => true,
                'message' => 'Producto actualizado correctamente'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Error al actualizar producto'
            );
        }
        echo json_encode($response);
    }


    public function eliminar_producto()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        $id = $this->input->post("id");
        $action = $this->input->post("action");

        $result = $this->Productos_model->eliminar_producto($id, $action);

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

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
?>