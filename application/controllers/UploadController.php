<?php
class UploadController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Image_model');
    }

    public function upload_image() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Content-Type: application/json');
        
        $config['upload_path'] = './static/images/';
        $config['allowed_types'] = 'gif|jpg|png';
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image')) {
            $error = array('error' => $this->upload->display_errors());
            $this->output->set_output(json_encode(['success' => false, 'error' => $error]));
        } else {
            $data = $this->upload->data();
            $this->Image_model->save_image($data['file_name']);
            $this->output->set_output(json_encode(['success' => true]));
        }
    }
}
