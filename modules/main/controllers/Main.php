<?php

use Modules\main\repository\MainRepository;

defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller // Non Dispersi
{
    protected $repository;
    public function __construct()
    {
        parent::__construct();
        $this->repository = new MainRepository();
    }

    public function optionModels()
    {
        $eloquent = $_GET['eloquent'];
        $id = $_GET['id'];
        $text = $_GET['text'];
        $where = false;
        if (isset($_GET['where'])) {
            $where = $_GET['where'];
        }

        $data = $this->repository->optionModels($eloquent, $id, $text, $where);
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }

    public function searchAllCode()
    {
        $query = $_GET['query'];
        $data = $this->repository->searchAllCode($query);
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
}
