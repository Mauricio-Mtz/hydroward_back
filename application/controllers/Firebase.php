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

    public function tessdt()
    {
        if ($this->input->raw_input_stream[0] == "{") {
            $input = json_decode($this->input->raw_input_stream);
            $mac = $input->mac_esclavo;
        } else {
            $mac = $this->input->post("mac_esclavo");
        }

        $data = $this->Sensor_model->test($mac); //Se obtienen datos de la BD SQL
        if ($data == null) {
            echo 'No se encontro la tarjeta';
            return;
        }
        $fecha = new DateTime();
        $nueva_zona_horaria = new DateTimeZone('America/Mexico_City');
        $fecha->setTimezone($nueva_zona_horaria);

        // Configura los datos que deseas enviar en el cuerpo de la solicitud POST
        $iot = array(
            "fields" => array(
                "id_maestro" => array("integerValue" => $data->maestro),
                "id_esclavo" => array("integerValue" => $data->id_dispositivo),
                "id_cosecha" => array("integerValue" => $data->id_cosecha),
                "cosecha" => array("stringValue" => $data->cosecha),
                "dispositivo" => array("stringValue" => $data->nombre),
                "fecha" => array("stringValue" => $fecha->format("Y-m-d H:i:s")),
                "temp_amb" => array("doubleValue" => $input->temp_amb),
                "hum_amb" => array("doubleValue" => $input->hum_amb),
                "hum_sue" => array("doubleValue" => $input->hum_sue),
            )
        );
        //Madar datos al firebase
        $firestore_url = "https://firestore.googleapis.com/v1/projects/testesp-36e82/databases/(default)/documents/pruebas";

        // Configura las opciones de la solicitud POST
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
        curl_close($ch);

        // Realiza la solicitud POST a Firestore
        $response = curl_exec($ch);
        if (!$result = curl_exec($ch)) {
            echo "ERROR al mandar datos a firebase";
            echo $response;
        } else {
            echo "Documento creado";
        }
    }
}