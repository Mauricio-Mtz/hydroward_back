<?php
defined('BASEPATH') or exit ('No direct script access allowed');

class Estanques extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Estanques_model');
        $this->load->helper('form');
    }

    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        echo json_encode('Bienvenido al Back-End de HydroWard ESAU');
    }

    public function registrarNombreEstanque()
    {
        $idVenta = $this->input->post('idVenta');
        $nombre = $this->input->post('nombre');
        $idEstanque = $this->Estanques_model->registrarNombreE($nombre, $idVenta); // Llama a la función para registrar el nombre y obtener el ID del estanque
        if ($idEstanque) {
            $response = array('success' => true, 'idEstanque' => $idEstanque); // Devuelve un objeto JSON con éxito y el ID del estanque
        } else {
            $response = array('success' => false);
        }
        echo json_encode($response);
    }
    public function editarEstanque()
    {
        $id = $this->input->post('estanque');
        $alim = $this->input->post('alimentacion');
        $tMin = $this->input->post('tempMin');
        $tMax = $this->input->post('tempMax');
        $nA = $this->input->post('noAlim');
        $sA = $this->input->post('siAlim');
        $pMin = $this->input->post('phMin');
        $pMax = $this->input->post('phMax');
        $nombre = $this->input->post('nombre');
        $cantidad = $this->input->post('cantidad');
        $pez = $this->input->post('pez');

        $query = $this->Estanques_model->editarE($nombre, $id, $alim, $tMin, $tMax, $nA, $sA, $pMin, $pMax, $cantidad, $pez); // Llama a la función para registrar el nombre y obtener el ID del estanque
        if ($query) {
            $response = array('success' => true);
        } else {
            $response = array('success' => false);
        }
        echo json_encode($response);
    }
    public function obtenerEstanques()
    {
        $id = $this->input->post('id');
        $estanques = $this->Estanques_model->obtenerEstanquesPorId($id);

        if ($estanques) {
            $response = array(
                'success' => true,
                'message' => 'Datos de los estanques obtenidos correctamente',
                'estanques' => $estanques
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se encontraron estanques para este usuario'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    public function obtenerEstanqueC()
    {
        $id = $this->input->post('id');
        $estanques = $this->Estanques_model->obtenerEstanquePorId($id);

        if ($estanques) {
            $response = array(
                'success' => true,
                'message' => 'Datos de los estanques obtenidos correctamente',
                'estanques' => $estanques
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se encontraron estanques para este usuario'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    public function ObtenerQrCode()
    {
        $qrCode = $this->input->post('qrCode');
        $venta = $this->Estanques_model->get_qr_code($qrCode);

        if ($venta) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(['venta' => $venta]));
        } else {
            $this->output->set_status_header(404);
            $this->output->set_output(json_encode(['error' => 'QR code not found']));
        }
    }
    public function obtenerPeces()
    {
        $peces = $this->Estanques_model->get_fish();
        $jsonResponse = json_encode($peces);
        header('Content-Type: application/json');
        echo $jsonResponse;
    }
    public function registrarPezEstanque()
    {
        $id_pez = $this->input->post('fish');
        $estanque = $this->input->post('estanque');
        $query = $this->Estanques_model->registrarPezE($id_pez, $estanque);
        if ($query) {
            $response = array('success' => true, 'message' => 'Pez and estanque registered successfully.');
        } else {
            $response = array('success' => false, 'message' => 'Error registering pez and estanque.');
        }
        echo json_encode($response);
    }
}
