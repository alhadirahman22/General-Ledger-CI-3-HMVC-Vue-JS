<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Repository\mutasi\JenisMutasiRepository;

class Api_jenis_mutasi extends CI_Controller
{

    protected $JenisMutasiRepository;
    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->JenisMutasiRepository = new JenisMutasiRepository();
    }

    public function index()
    {
        $data = $this->JenisMutasiRepository->get();
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
}
