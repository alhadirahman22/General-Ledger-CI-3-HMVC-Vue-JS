<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Modules\administration\repository\DepartmentsRepository;
use Illuminate\Database\Capsule\Manager as Capsule;


class Api_department extends CI_Controller
{
    protected  $DepartmentsRepository;
    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->DepartmentsRepository = new DepartmentsRepository();
    }

    public function fetch($active = 1)
    {
        $token = $this->input->post('token');
        $arrFilter = $this->m_master->decode_token($token);
        $arrFilter['active'] = $active;

        // Capsule::connection()->enableQueryLog();
        $data = $this->DepartmentsRepository->get($arrFilter);
        // $queries = Capsule::getQueryLog();
        // print_r($queries);
        // die();

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
}
