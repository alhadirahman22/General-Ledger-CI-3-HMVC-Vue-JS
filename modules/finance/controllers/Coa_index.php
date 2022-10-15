<?php

use Modules\finance\midlleware\coa\CoaMiddleware;
use Modules\finance\repository\coa\CoaRepository;

defined('BASEPATH') or exit('No direct script access allowed');


class Coa_index extends CI_Controller // Non Dispersi
{
    protected $repository;
    protected $midlleware;
    public function __construct()
    {
        parent::__construct();
        $this->perm = 'finance/coa/coa_index';
        $this->aauth->control($this->perm);
        $this->lang->load('coa', settings('language'));
        $this->data['menu'] = 'finance/coa/coa_index';
        $this->data['module_url'] = site_url('finance/coa/coa_index/');
        $this->repository = new CoaRepository();
        $this->midlleware = new CoaMiddleware();
        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'fin_coa_id', 'title' => 'ID', 'filter' => false, 'class' => 'text-center default-sort', 'sort' => 'desc'],
                '1' => ['name' => 'fin_coa_group_id', 'title' => 'Group', 'filter' => ['type' => 'text'], 'class' => 'text-center'],
                '2' => ['name' => 'fin_coa_code', 'title' => 'Code', 'filter' => ['type' => 'text'], 'class' => 'text-center'],
                '3' => ['name' => 'fin_coa_name', 'title' => 'Name', 'filter' => ['type' => 'text']],
                '4' => ['name' => 'type', 'title' => 'Type', 'class' => 'text-center', 'filter' => ['type' => 'dropdown', 'options' => ['' => 'All', 'D' => 'Debit', 'C' => 'Credit']]],
                '5' => ['name' => 'status', 'class' => 'text-center', 'title' => 'Aktif', 'filter' => ['type' => 'dropdown', 'options' => ['' => 'All', 'A' => 'Y', 'T' => 'N']]],
                '6' => ['name' => 'type', 'class' => 'text-center', 'title' => 'Grouping', 'filter' => false, 'class' => 'no-sort'],
                '7' => ['name' => 'created_by', 'title' => 'Created', 'filter' => false, 'class' => 'no-sort'],
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];

        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['8'] = ['name' => 'fin_coa_id', 'title' => '', 'class' => 'no-sort text-center', 'width' => '7%', 'filter' => ['type' => 'action']];
        }

        $this->data['filter_name'] = 'table_filter_finance_coa';
        $this->table = 'fin_coa';
    }

    public function index()
    {
        $this->data['btn_option'] = $this->aauth->is_allowed($this->perm . '/add') ? '<a href="' . $this->data['module_url'] . 'form" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> ' . lang('add') . '</a>' : '';

        $this->load->view('default/list', $this->data);
        $this->template->_init();
        $this->template->table();
    }

    public function form($token = null)
    {
        $midlleware =  $this->midlleware->form($token);
        $data = $midlleware['data'];
        $id = $midlleware['id'];

        $form =  $this->repository->form($data);
        $this->load->library('form_builder');
        $this->data['form'] = [
            'action' => $this->data['module_url'] . 'save',
            'build' => $this->form_builder->build_form_horizontal($form),
            'class' => 'ajax-token',
        ];

        $this->data['data'] = $data;

        $this->template->_init();
        $this->template->form();
        $this->output->set_title(($this->data['data'] ? lang('edit') : lang('add')) . ' ' . lang('heading'));
        $this->load->view('default/form', $this->data);
    }

    public function save()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $dataAll = $this->m_master->getInputToken();
        $validation = $this->midlleware->validation($dataAll);

        if ($validation['status'] == 'success') {
            $save = $this->repository->save($dataAll);
            if ($save['status'] == 'success') {
                $return = array('message' => $save['message'], 'status' => 'success', 'redirect' => $this->data['module_url']);
            } else {
                $return = $save;
            }
        } else {
            $return = $validation;
        }

        if (isset($return['redirect'])) {
            $this->session->set_flashdata('form_response_status', $return['status']);
            $this->session->set_flashdata('form_response_message', $return['message']);
        }
        echo json_encode($return);
    }
}
