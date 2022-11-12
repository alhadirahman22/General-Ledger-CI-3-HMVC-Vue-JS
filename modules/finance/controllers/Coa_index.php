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
                '0' => ['name' => 'fin_coa.fin_coa_id', 'title' => 'ID', 'filter' => false, 'class' => 'text-center default-sort', 'sort' => 'desc'],
                // '1' => ['name' => 'fin_coa_group.fin_coa_group_code', 'title' => 'Group', 'filter' => ['type' => 'text'], 'class' => 'text-center'],
                '1' => ['name' => 'fin_coa_group.fin_coa_group_id', 'title' => 'Group', 'filter' => ['type' => 'dropdown', 'options' => $this->m_master->get_dropdown('(select a.fin_coa_group_id,concat(a.fin_coa_group_code," - ",a.fin_coa_group_name) as codeShow from fin_coa_group as a) as xx', 'fin_coa_group_id', 'codeShow')], 'class' => 'text-center'],
                '2' => ['name' => 'fin_coa.fin_coa_code', 'title' => 'Code', 'filter' => ['type' => 'text'], 'class' => 'text-center'],
                '3' => ['name' => 'fin_coa.fin_coa_name', 'title' => 'Name', 'filter' => ['type' => 'text']],
                '4' => ['name' => 'fin_coa.type', 'title' => 'Type', 'class' => 'text-center', 'filter' => ['type' => 'dropdown', 'options' => ['' => 'All', 'D' => 'Debit', 'C' => 'Credit']]],
                '5' => ['name' => 'fin_coa.status', 'class' => 'text-center', 'title' => 'Aktif', 'filter' => ['type' => 'dropdown', 'options' => ['' => 'All', 'A' => 'Y', 'T' => 'N']]],
                '6' => ['name' => 'fin_coa_aktiva_passiva_sub.fin_coa_aktiva_passiva_sub_id', 'class' => 'text-center', 'title' => 'Grouping', 'filter' => ['type' => 'dropdown', 'options' => $this->m_master->get_dropdown('(select b.fin_coa_aktiva_passiva_sub_id,concat(a.name," - ",b.name) as codeShow from fin_coa_aktiva_passiva as a join fin_coa_aktiva_passiva_sub as b on a.fin_coa_aktiva_passiva_id = b.fin_coa_aktiva_passiva_id) as xx', 'fin_coa_aktiva_passiva_sub_id', 'codeShow')], 'class' => 'no-sort'],
                '7' => ['name' => 'fin_coa.created_by', 'title' => 'Created', 'filter' => false, 'class' => 'no-sort'],
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];

        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['8'] = ['name' => 'fin_coa_id', 'title' => '', 'class' => 'no-sort text-center', 'width' => '15%', 'filter' => ['type' => 'action']];
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

    public function delete($token)
    {
        $dataToken = get_jwt_decryption($token);
        $id = $dataToken->id;
        $rule = $this->midlleware->ruleEditDelete($id);
        if ($rule['status'] == 'success') {
            $delete = $this->repository->delete($id);
            $return = ['message' => sprintf(lang('delete_success'), lang('heading')), 'status' => 'success'];
        } else {
            $return = $rule;
        }

        echo json_encode($return);
    }
}
