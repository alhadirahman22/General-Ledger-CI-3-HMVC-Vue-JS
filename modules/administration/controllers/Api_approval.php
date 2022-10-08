<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Modules\administration\repository\approval\ApprovalRuleRepository;

class Api_approval extends CI_Controller
{

    protected $repositoryApproval;
    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->repositoryApproval = new ApprovalRuleRepository();
    }

    public function index()
    {
        $data = $this->repositoryApproval->get();
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }

    public function onceUsed()
    {
        $data = $this->repositoryApproval->onceUsed();
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }

    public function persetujuan()
    {
        $data = $this->repositoryApproval->persetujuan();
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
}
