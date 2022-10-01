<?php

use Repository\dashboard\DashboardRepository;
use Illuminate\Database\Capsule\Manager as Capsule;


defined('BASEPATH') or exit('No direct script access allowed!');

class Dashboard extends CI_Controller
{

    protected $DashboardRepository;

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('dashboard', settings('language'));
        $this->data['menu'] = 'dashboard';
        $this->data['module_url'] = base_url() . '';
        $this->DashboardRepository = new DashboardRepository();
    }

    public function index()
    {
        $this->data['dataParser'] = json_encode($this->DashboardRepository->getDashboard());
        $this->template->_init();
        $this->output->set_title(lang('dashboard'));

        $this->load->view('index', $this->data);
        $this->load->js('assets/custom/js/museum/dashboard.js');
    }
}
