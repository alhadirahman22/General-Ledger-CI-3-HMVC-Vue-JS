<?php

use Repository\mutasi\MutasiRepository;
use Repository\mutasi\MutasiRepositoryStatus;

defined('BASEPATH') or exit('No direct script access allowed');

class Mutasi_benda extends CI_Controller
{

    protected $MutasiRepository;
    protected $MutasiRepositoryStatus;

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'mutasi/mutasi_benda';
        $this->table_id_key = 'mutasi_benda' . '_id'; // 'warehouse_sub_id';
        $this->aauth->control($this->perm);
        $this->class_name = get_class($this);
        $this->class_model = strtolower($this->class_name) . '_model';
        $this->lang->load(strtolower($this->class_name), settings('language'));
        $this->data['menu'] = $this->perm;
        $this->data['module_url'] = site_url($this->perm . '/');

        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'mutasi_benda.mutasi_benda_id', 'title' => 'ID', 'width' => '7%', 'filter' => false, 'class' => 'default-sort text-center', 'sort' => 'desc'],
                '1' => ['name' => 'bendas.name', 'title' => 'Nama Benda', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '2' => ['name' => 'jenis_mutasi.name', 'title' => 'Jenis Mutasi', 'filter' => false, 'class' => 'default-sort', 'sort' => 'asc'],
                '3' => ['name' => 'tag_department', 'title' => 'Department', 'filter' => false, 'class' => 'default-sort', 'sort' => 'asc'],
                '4' => ['name' => 'mutasi_benda.status', 'title' => 'Status', 'filter' => ['type' => 'dropdown', 'options' => ['' => 'All', '0' => 'Waiting', '1' => 'Approved', '-1' => 'Reject', '2' => 'Progress']], 'class' => 'no-sort', 'sort' => 'asc'],
                '5' => ['name' => 'Desc', 'title' => 'Desc', 'filter' => false, 'class' => 'no-sort', 'sort' => 'asc'],
                '6' => ['name' => 'employees.name', 'title' => 'Requester', 'filter' => ['type' => 'text'], 'class' => 'no-sort', 'sort' => 'asc'],
                '7' => ['name' => 'mutasi_benda.created_at', 'title' => 'Created At', 'filter' => false, 'class' => 'default-sort', 'sort' => 'asc'],
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];

        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['8'] = ['name' => 'id', 'title' => '', 'class' => 'no-sort text-center', 'width' => '5%', 'filter' => ['type' => 'action']];
        }

        $this->data['filter_name'] = 'table_filter_' . $this->class_model;
        $this->MutasiRepository = new MutasiRepository();
        $this->MutasiRepositoryStatus = new MutasiRepositoryStatus();

        // print_r($this->MutasiRepositoryStatus->statusBendaMutasi(1));
    }

    public function index()
    {
        $this->data['btn_option'] = $this->aauth->is_allowed($this->perm . '/add')
            ? '<a href="' . $this->data['module_url'] . 'form" class="btn btn-sm btn-primary"><i class="ace-icon fa fa fa-plus-circle bigger-110"></i> Add</a>' : '';

        $this->load->view('default/list', $this->data);
        $this->template->_init();
        $this->template->table();
        $this->output->set_title(lang('heading'));
    }

    public function form($mutasi_benda_id =  null)
    {
        $this->aauth->control($this->perm . '/add');
        $dataprop = $this->MutasiRepository->findByID($mutasi_benda_id);

        $this->output->set_title((!empty($id) ? lang('edit') : lang('add')) . ' ' . lang('heading'));
        $this->data['headingOverwrite'] = 'Form ' . lang('heading');
        $this->template->_init();
        $this->template->form();
        $moduleData = $this->data;
        $iconBtn = [
            'cancel_w_icon' => lang('cancel_w_icon'),
            'save_w_icon' => lang('save_w_icon'),
        ];
        $this->data['dataprop'] = $this->m_master->encodeToPropVue($dataprop);
        $this->data['iconBtn'] =  $this->m_master->encodeToPropVue($iconBtn);
        $this->data['moduleData'] =  $this->m_master->encodeToPropVue($moduleData);
        $this->load->view('mutasi_benda', $this->data);
    }

    public function view($mutasi_benda_id =  null)
    {
        $this->aauth->control($this->perm . '/view');
        $dataprop = $this->MutasiRepository->findByID($mutasi_benda_id);

        $this->output->set_title((!empty($id) ? lang('edit') : lang('add')) . ' ' . lang('heading'));
        $this->data['headingOverwrite'] = 'Form ' . lang('heading');
        $this->template->_init();
        $this->template->form();
        $moduleData = $this->data;
        $iconBtn = [
            'cancel_w_icon' => lang('cancel_w_icon'),
            'save_w_icon' => lang('save_w_icon'),
        ];
        $this->data['dataprop'] = $this->m_master->encodeToPropVue($dataprop);
        $this->data['iconBtn'] =  $this->m_master->encodeToPropVue($iconBtn);
        $this->data['moduleData'] =  $this->m_master->encodeToPropVue($moduleData);
        $this->load->view('view_mutasi_benda', $this->data);
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

        $get_data = $this->MutasiRepository->datatable($start, $length, $filter, $order, $this->data['table']);
        $output = $this->MutasiRepository->setOutputDatatable($get_data, $draw);
        echo json_encode($output);
    }

    public function save()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $token = $this->input->post('token');
        $dataAll = $this->m_master->decode_token($token);

        // $validation = $this->MutasiRepository->validationManually($dataAll);
        $validation = $this->MutasiRepository->validationManuallyMultiBenda($dataAll);
        if ($validation['status'] == 'success') {
            // $create = $this->MutasiRepository->create($dataAll);
            $create = $this->MutasiRepository->createManyBenda($dataAll);
            if ($create['status'] == 'success') {
                $return = array('message' => sprintf(lang('save_success'), lang('heading')), 'status' => 'success', 'redirect' => $this->data['module_url']);
                if (isset($return['redirect'])) {
                    $this->session->set_flashdata('form_response_status', $return['status']);
                    $this->session->set_flashdata('form_response_message', $return['message']);
                }
            } else {
                $return  = $create;
            }
        } else {
            $return  = $validation;
        }

        echo json_encode($return);
    }

    public function delete($mutasi_benda_id)
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $delete = $this->MutasiRepository->delete($mutasi_benda_id);
        if ($delete['status'] == 'success') {
            $return = ['message' => sprintf(lang('delete_success'), lang('heading')), 'status' => 'success'];
        } else {
            $return =  $delete;
        }

        echo json_encode($return);
    }
}
