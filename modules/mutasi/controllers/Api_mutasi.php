<?php

use Repository\mutasi\MutasiRepository;
use Repository\mutasi\MutasiRuleAction;

defined('BASEPATH') or exit('No direct script access allowed');

class Api_mutasi extends CI_Controller
{
    protected $MutasiRepository;
    protected $MutasiRuleAction;
    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->MutasiRepository = new MutasiRepository();
        $this->MutasiRuleAction = new MutasiRuleAction();
    }

    public function createCheckBenda()
    {
        $token = $this->input->post('token');
        $dataAll = $this->m_master->decode_token($token);
        $benda_id = $dataAll['benda_id'];
        $jenis_mutasi_id = $dataAll['jenis_mutasi_id'];
        $proc = $this->MutasiRuleAction->createMutasi($benda_id, $jenis_mutasi_id);
        echo json_encode($proc);
    }

    public function createLoadApproval()
    {
        $token = $this->input->post('token');
        $dataAll = $this->m_master->decode_token($token);
        $jenis_mutasi_id = $dataAll['jenis_mutasi_id'];
        $dataShow = $this->MutasiRepository->getApprovalData($jenis_mutasi_id);

        echo json_encode($dataShow);
    }

    public function loadApprovalMutasi()
    {
        $token = $this->input->post('token');
        $dataAll = $this->m_master->decode_token($token);
        $mutasi_benda_id = $dataAll['mutasi_benda_id'];
        $dataShow = $this->MutasiRepository->loadApprovalMutasi($mutasi_benda_id);

        echo json_encode($dataShow);
    }

    public function approve()
    {
        $token = $this->input->post('token');
        $dataAll = $this->m_master->decode_token($token);
        $item = $dataAll;
        $dataShow = $this->MutasiRepository->approve($item);

        echo json_encode($dataShow);
    }

    public function reject()
    {
        $token = $this->input->post('token');
        $dataAll = $this->m_master->decode_token($token);
        $item = $dataAll;
        $dataShow = $this->MutasiRepository->reject($item);
        echo json_encode($dataShow);
    }
}
