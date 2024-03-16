<?php
defined('BASEPATH') or exit ('No direct script access allowed');

class Back extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
    }
    public function index()
    {
        $this->load->view('mi_vista');
    }
}
