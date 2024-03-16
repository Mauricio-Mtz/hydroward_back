<?php
class Image_model extends CI_Model {
    public function save_image($filename) {
        $data = array(
            'nombre' => $filename,
            'producto_id' => 1
        );

        $this->db->insert('imagen', $data);
    }
}
