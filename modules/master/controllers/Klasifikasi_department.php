<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Repository\master\KlasifikasiRepository;
use Repository\master\KlasifikasiDepartmentRepository;


class Klasifikasi_department extends CI_Controller
{

    protected $KlasifikasiRepository;
    protected $KlasifikasiDepartmentRepository;

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'master/klasifikasi_department';
        $this->table_id_key = 'klasifikasi_department' . '_id';
        $this->aauth->control($this->perm);
        $this->class_name = get_class($this);
        $this->class_model = strtolower($this->class_name) . '_model';
        $this->data['menu'] = $this->perm;
        $this->data['module_url'] = site_url($this->perm . '/');

        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'klasifikasi_department.klasifikasi_department_id', 'title' => 'ID', 'width' => '8%', 'filter' => false, 'class' => 'default-sort text-center', 'sort' => 'asc'],
                '1' => ['name' => 'klasifikasi.name', 'title' => 'Klasifikasi', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '2' => ['name' => 'tag_department', 'title' => 'Departments', 'filter' => false, 'class' => 'default-sort', 'sort' => 'asc'],
                '3' => ['name' => 'klasifikasi_department.type_approval', 'title' => 'Approval Type', 'filter' => false, 'class' => 'no-sort', 'sort' => 'asc'],
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];

        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['4'] = ['name' => 'klasifikasi_department.klasifikasi_department_id', 'title' => '', 'class' => 'no-sort text-center', 'width' => '5%', 'filter' => ['type' => 'action']];
        }

        $this->data['filter_name'] = 'table_filter_' . $this->class_model;
        $this->table = 'jenis_perolehan';

        $this->KlasifikasiRepository = new KlasifikasiRepository();
        $this->KlasifikasiDepartmentRepository = new KlasifikasiDepartmentRepository();
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

        $get_data = $this->KlasifikasiDepartmentRepository->datatable($start, $length, $filter, $order, $this->data['table']);
        $output = $this->KlasifikasiDepartmentRepository->setOutputDatatable($get_data, $draw);
        echo json_encode($output);
    }

    public function form($klasifikasi_id = '')
    {
        $dataKlasifikasi = $this->KlasifikasiRepository->findByID($klasifikasi_id);

        $this->output->set_title((!empty($id) ? lang('edit') : lang('add')) . ' ' . 'Klasifikasi Department');
        $this->data['headingOverwrite'] = 'Form Klasifikasi Department';
        $this->template->_init();
        $this->template->form();
        $moduleData = $this->data;
        $iconBtn = [
            'cancel_w_icon' => lang('cancel_w_icon'),
            'save_w_icon' => lang('save_w_icon'),
        ];
        $this->data['dataKlasifikasi'] = $this->m_master->encodeToPropVue($dataKlasifikasi);
        $this->data['iconBtn'] =  $this->m_master->encodeToPropVue($iconBtn);
        $this->data['moduleData'] =  $this->m_master->encodeToPropVue($moduleData);
        $this->load->view('klasifikasi_department_form', $this->data);
    }

    public function save()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $token = $this->input->post('token');
        $dataAll = $this->m_master->decode_token($token);
        $validation = $this->KlasifikasiDepartmentRepository->validationManually($dataAll);
        if ($validation['status'] == 'success') {
            $create = $this->KlasifikasiDepartmentRepository->create($dataAll);
            if ($create['status'] == 'success') {
                $return = array('message' => sprintf(lang('save_success'), 'Klasifikasi Department'), 'status' => 'success', 'redirect' => $this->data['module_url']);
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

    public function delete($klasifikasi_id)
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $delete = $this->KlasifikasiDepartmentRepository->delete($klasifikasi_id);
        if ($delete['status'] == 'success') {
            $return = ['message' => sprintf(lang('delete_success'), 'Klasfikasi Department'), 'status' => 'success'];
        } else {
            $return =  $delete;
        }

        echo json_encode($return);
    }
}
