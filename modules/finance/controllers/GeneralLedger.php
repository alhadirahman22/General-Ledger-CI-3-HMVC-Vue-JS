<?php

use Modules\finance\midlleware\GLMidlleware;
use Modules\finance\repository\GLRepository;

defined('BASEPATH') or exit('No direct script access allowed');

class GeneralLedger extends CI_Controller // Non Dispersi
{
    protected $repository;
    protected $midlleware;
    public function __construct()
    {
        parent::__construct();
        $this->perm = 'finance/generalLedger';
        $this->aauth->control($this->perm);
        $this->lang->load('gl', settings('language'));
        $this->data['menu'] = 'finance/generalLedger';
        $this->data['module_url'] = site_url('finance/generalLedger/');
        $this->repository = new GLRepository();
        $this->midlleware = new GLMidlleware();

        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'fin_gl_id', 'title' => 'ID', 'filter' => false, 'class' => 'text-center default-sort', 'sort' => 'desc'],
                '1' => ['name' => 'fin_gl_code', 'title' => 'Code', 'filter' => ['type' => 'text'], 'class' => 'text-center'],
                '2' => ['name' => 'fin_jurnal_voucher_id', 'title' => 'Jurnal Voucher', 'filter' => ['type' => 'dropdown', 'options' => $this->m_master->get_dropdown('fin_jurnal_voucher', 'fin_jurnal_voucher_id', 'fin_jurnal_voucher_name')], 'class' => 'text-center'],
                '3' => ['name' => 'fin_coa_id', 'title' => 'Coa Code', 'filter' => ['type' => 'text'], 'class' => 'no-sort'],
                '4' => ['name' => 'fin_coa_id', 'title' => 'Reference', 'filter' => ['type' => 'text'], 'class' => 'no-sort'],
                '5' => ['name' => 'fin_gl_no_bukti', 'title' => 'No Bukti', 'filter' => ['type' => 'text']],
                '6' => ['name' => 'fin_gl_date', 'title' => 'Date', 'filter' => false],
                '7' => ['name' => 'debit_total', 'class' => 'text-center', 'title' => 'Debit', 'filter' => false],
                '8' => ['name' => 'credit_total', 'class' => 'text-center', 'title' => 'Credit', 'filter' => false],
                '9' => ['name' => 'status', 'class' => 'text-center', 'title' => 'Status', 'filter' => false],
                '10' => ['name' => 'updated_at', 'title' => 'Time', 'filter' => false, 'class' => 'no-sort text-center'],
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];


        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['11'] = ['name' => 'fin_gl_id', 'title' => '', 'class' => 'no-sort text-center', 'width' => '7%', 'filter' => ['type' => 'action']];
        }

        $this->data['filter_name'] = 'table_filter_finance_gl';
    }

    public function index()
    {
        $this->data['btn_option'] = $this->aauth->is_allowed($this->perm . '/add') ? '<a href="' . $this->data['module_url'] . 'form" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> ' . lang('add') . '</a>' : '';

        $this->load->view('default/list', $this->data);
        $this->template->_init();
        $this->template->table();
    }

    public function get_list()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $order = $this->input->post('order')[0];
        $draw = intval($this->input->post('draw'));
        $filter = $this->input->post('filter');
        $this->session->set_userdata($this->data['filter_name'], $filter);
        $output['data'] = array();

        $get_data = $this->repository->datatable($start, $length, $filter, $order, $this->data['table']);
        $output = $this->repository->setOutputDatatable($get_data, $draw);
        echo json_encode($output);
    }

    public function form($token = null)
    {
        $midlleware =  $this->midlleware->form($token);
        $data = $midlleware['data'];
        $id = $midlleware['id'];

        $this->output->set_title((!empty($id) ? lang('edit') : lang('add')) . ' ' .  lang('heading'));
        $this->data['headingOverwrite'] = 'Form ' . lang('heading');
        $this->template->_init();
        $this->template->form();
        $moduleData = $this->data;
        $iconBtn = [
            'cancel_w_icon' => lang('cancel_w_icon'),
            'save_w_icon' => lang('save_w_icon'),
        ];
        $this->data['data'] = $this->m_master->encodeToPropVue($data);
        $this->data['iconBtn'] =  $this->m_master->encodeToPropVue($iconBtn);
        $this->data['moduleData'] =  $this->m_master->encodeToPropVue($moduleData);
        $this->load->view('gl_form', $this->data);
    }
}
