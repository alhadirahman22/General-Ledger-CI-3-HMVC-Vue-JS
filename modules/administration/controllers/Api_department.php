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
        $arrFilter = ['company_id' =>  1];
        $arrFilter['active'] = $active;
        $data = $this->DepartmentsRepository->get($arrFilter);
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
}
