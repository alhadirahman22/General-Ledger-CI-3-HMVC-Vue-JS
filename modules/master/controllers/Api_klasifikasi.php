<?php

use Repository\master\KlasifikasiRepository;


defined('BASEPATH') or exit('No direct script access allowed');

class Api_klasifikasi extends CI_Controller
{
    protected $KlasifikasiRepository;

    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->KlasifikasiRepository = new KlasifikasiRepository();
    }

    public function index($active = 1)
    {
        $filter = [
            'active' => $active,
        ];
        $data = $this->KlasifikasiRepository->get($filter);
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
}
