<?php
defined('BASEPATH') or exit ('No direct script access allowed');

class Firebase extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sensor_model');
    }
    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        echo json_encode('Registros en firebase');
    }
    public function test()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');

        // Obtiene el valor del campo "id_estanque" del formulario si no es un objeto JSON
        $id_estanque = $this->input->post("id_estanque");
        $alimentacion = $this->input->post("alimentacion");
        $conteo = $this->input->post("conteo");
        $temperatura = $this->input->post("temperatura");
        $ph = $this->input->post("ph");

        // Verifica si se encontraron datos en la base de datos
        if ($id_estanque == null) {
            echo 'No se encontro la tarjeta';
            return;
        }

        // Crea un objeto DateTime y lo configura con la zona horaria de México
        $fecha = new DateTime();
        $nueva_zona_horaria = new DateTimeZone('America/Mexico_City');
        $fecha->setTimezone($nueva_zona_horaria);

        // Configura los datos que se enviarán a Firestore en un formato específico
        $iot = array(
            "fields" => array(
                "id_estanque" => array("integerValue" => $id_estanque),
                "alimentacion" => array("booleanValue" => $alimentacion),
                "conteo" => array("integerValue" => $conteo),
                "fecha" => array("stringValue" => $fecha->format("Y-m-d H:i:s")),
                "temperatura" => array("integerValue" => $temperatura),
                "ph" => array("integerValue" => $ph),
            )
        );

        // URL del servicio Firestore
        $firestore_url = "https://firestore.googleapis.com/v1/projects/hydroward-aaae8/databases/(default)/documents/sensores";

        // Configura las opciones de la solicitud POST con cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $firestore_url);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Accept: application/json',
                'Content-Type: application/json',
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($iot));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Realiza la solicitud POST a Firestore
        $response = curl_exec($ch);

        // Cierra el recurso cURL
        curl_close($ch);

        // Maneja la respuesta de Firestore
        if ($response === false) {
            echo "ERROR al mandar datos a firebase";
        } else {
            echo "Documento creado"; // Imprime un mensaje si la solicitud es exitosa
        }
    }
    public function get_data()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
    
        // URL del servicio Firestore
        $firestore_url = "https://firestore.googleapis.com/v1/projects/hydroward-aaae8/databases/(default)/documents/sensores";
    
        // Configura las opciones de la solicitud GET con cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $firestore_url);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Accept: application/json',
                'Content-Type: application/json',
            )
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        // Realiza la solicitud GET a Firestore
        $response = curl_exec($ch);
    
        // Cierra el recurso cURL
        curl_close($ch);
    
        // Maneja la respuesta de Firestore
        if ($response === false) {
            $response = array(
                'success' => false,
                'message' => 'ERROR al recibir datos de firebase'
            );
        } else {
            $data = json_decode($response, true);
    
            if (isset($data['documents']) && is_array($data['documents'])) {
                $processed_data = array_map(function ($document) {
                    $processed_fields = array();
                    foreach ($document['fields'] as $key => $value) {
                        $processed_fields[$key] = reset($value); // Extrae el valor real
                    }
                    return $processed_fields;
                }, $data['documents']);
    
                $response = array(
                    'success' => true,
                    'message' => 'Datos obtenidos correctamente',
                    'data' => $processed_data
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'No se encontraron documentos en la respuesta de Firebase'
                );
            }
        }
        echo json_encode($response);
    }
    
}