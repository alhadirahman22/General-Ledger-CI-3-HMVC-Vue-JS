<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Repository\benda\BendaRepository;



class Api_benda extends CI_Controller
{

    protected $BendaRepository;
    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->BendaRepository = new BendaRepository();
    }

    public function index($active = 1)
    {
        $filter = [
            'active' => $active,
        ];
        $data = $this->BendaRepository->get($filter);
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
}
