<?php

use Modules\reimbursment\midlleware\ReimbursmentMiddleware;
use Modules\reimbursment\repository\ReimbursmentRepository;

defined('BASEPATH') or exit('No direct script access allowed!');


class Reimbursment extends CI_Controller
{

    protected $repository;
    protected $midlleware;
    public $prefixSettings;
    public $codeApproval;

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'reimbursment';
        $this->table_id_key = 'reimbursment_id';
        $this->aauth->control($this->perm);
        $this->lang->load('reimbursment', settings('language'));

        $this->data['menu'] = 'reimbursment';
        $this->data['module_url'] = site_url('reimbursment/');
        $this->repository = new ReimbursmentRepository();
        $this->midlleware = new ReimbursmentMiddleware();
        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'reimbursment.code', 'title' => 'Code & Name', 'class' => 'default-sort', 'sort' => 'desc', 'filter' => ['type' => 'text']],
                '1' => ['name' => 'employees.name', 'title' => 'Requested', 'filter' => ['type' => 'text'], 'class' => 'default-sort'],
                '2' => ['name' => 'reimbursment.value', 'title' => 'Price', 'filter' => false, 'class' => 'no-sort'],
                '3' => ['name' => 'reimbursment.date_reimbursment', 'title' => 'Trans Date', 'filter' => false, 'class' => 'default-sort'],
                '4' => ['name' => 'reimbursment.status', 'title' => 'Status',  'filter' => ['type' => 'dropdown', 'options' => $this->repository->opStatus()], 'class' => 'default-sort'],
                '5' => ['name' => 'reimbursment.desc', 'title' => 'Desc', 'filter' => false, 'class' => 'no-sort'],
                '6' => ['name' => 'reimbursment.created_at', 'title' => lang('created_at'), 'filter' => false, 'class' => 'no-sort'],
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];
        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['7'] = ['name' => 'reimbursment_id', 'title' => 'Action', 'class' => 'no-sort text-center', 'width' => '10%', 'filter' => ['type' => 'action']];
        }
        $this->data['filter_name'] = 'table_filter_reimbursment';
        $this->table = 'reimbursment';
        $this->prefixSettings = settings('prefix_reimbursment');
        $this->codeApproval = $this->repository->getCodeApproval();
    }

    public function index()
    {
        $this->data['btn_option'] = $this->aauth->is_allowed($this->perm . '/add') ? '<a href="' . $this->data['module_url'] . 'form" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> ' . lang('add') . '</a>' : '';

        $this->load->view('default/list', $this->data);
        $this->template->_init();
        $this->template->table();
        $this->output->set_title(lang('heading'));
    }

    public function form($token = null)
    {
        $midlleware =  $this->midlleware->form($token);
        $data = $midlleware['data'];
        $id = $midlleware['id'];

        $dataprop = $this->repository->findByID($id);


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
        $this->load->view('reimbursment_form', $this->data);
    }

    public function save()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $token = $this->input->post('token');
        $dataAll = $this->m_master->decode_token($token);

        $validation = $this->midlleware->validation($dataAll);

        if ($validation['status'] == 'success') {
            $save = $this->repository->create($dataAll);
            if ($save['status'] == 'success') {
                $return = array('code' => $save['code'], 'message' => sprintf(lang('save_success'), lang('heading') . ' ' . $dataAll['name']), 'status' => 'success', 'redirect' => $this->data['module_url']);
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
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $this->aauth->control($this->perm . '/delete');
        $dataToken = get_jwt_decryption($token);
        $id = $dataToken->id;
        $delete = $this->repository->delete($id);
        if ($delete['status'] == 'success') {
            $return = ['message' => sprintf(lang('delete_success'), lang('heading')), 'status' => 'success'];
        } else {
            $return =  $delete;
        }

        echo json_encode($return);
    }
}
