<?php

use Repository\administration\EmployeeRepository;

defined('BASEPATH') or exit('No direct script access allowed');

class Api_employee extends CI_Controller
{

    protected $EmployeeRepository;
    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->EmployeeRepository = new EmployeeRepository();
    }

    public function fetch($active = 1)
    {
        $filter = [
            'active' => $active,
        ];
        $data = $this->EmployeeRepository->get($filter);
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
}
