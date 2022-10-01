<?php

use Repository\administration\JabatanRepository;

defined('BASEPATH') or exit('No direct script access allowed');

class Api_jabatan extends CI_Controller
{
    protected  $JabatanRepository;
    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->JabatanRepository = new JabatanRepository();
    }

    public function getJabatanByDepartment($department_id) // this one trigger by employee
    {
        $data = $this->JabatanRepository->getJabatanByDepartment($department_id);
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }

    public function fetch()
    {
        $data = $this->JabatanRepository->get();

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }

    public function jabatanByDept()
    {
        $token = $this->input->post('token');
        $data = $this->m_master->decode_token($token);
        $department_id = $data['department_id'];
        $data = $this->JabatanRepository->get(['department_id' => $department_id]);

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
}
