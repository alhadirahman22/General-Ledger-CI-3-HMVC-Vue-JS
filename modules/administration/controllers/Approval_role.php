<?php

use Modules\administration\repository\approval\ApprovalRuleRepository;
use Modules\administration\midlleware\Approval_role_midlleware;
use Modules\administration\models\Approval_rule_model_eloquent;

use Illuminate\Database\Capsule\Manager as Capsule;

defined('BASEPATH') or exit('No direct script access allowed!');


class Approval_role extends CI_Controller
{

    protected $repository;
    protected $midlleware;


    public function __construct()
    {
        parent::__construct();
        $this->perm = 'administration/approval_role';
        $this->table_id_key = 'approval_rule' . '_id';
        $this->aauth->control($this->perm);

        $this->lang->load('approval_role', settings('language'));

        $this->data['menu'] = 'administration/approval_role';
        $this->data['module_url'] = site_url('administration/approval_role/');
        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'name', 'title' => 'Name', 'class' => 'default-sort', 'sort' => 'asc', 'filter' => ['type' => 'text']],
                '1' => ['name' => 'type_approval', 'title' => 'Type Approval', 'filter' => ['type' => 'dropdown', 'options' => ['' => 'All', '1' => 'Series', '2' => 'Paralel']], 'class' => 'default-sort', 'sort' => 'asc'],
                '2' => ['name' => 'created_at', 'title' => lang('created_at'), 'filter' => false, 'class' => 'no-sort'],
                '3' => ['name' => 'created_by', 'title' => lang('created_by'), 'filter' => false, 'class' => 'no-sort'],
                '4' => ['name' => 'updated_at', 'title' => lang('updated_at'), 'filter' => false, 'class' => 'no-sort'],
                '5' => ['name' => 'updated_by', 'title' => lang('updated_by'), 'filter' => false, 'class' => 'no-sort'],
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];
        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['6'] = ['name' => 'id', 'title' => 'Action', 'class' => 'no-sort text-center', 'width' => '10%', 'filter' => ['type' => 'action']];
        }
        $this->data['filter_name'] = 'table_filter_setting_approval_role';
        $this->table = 'approval_rule';

        $this->repository = new ApprovalRuleRepository();
        $this->midlleware = new Approval_role_midlleware();
    }

    public function index()
    {
        $this->data['btn_option'] = $this->aauth->is_allowed($this->perm . '/add') ? '<a href="' . $this->data['module_url'] . 'form" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> ' . lang('add') . '</a>' : '';

        $this->load->view('default/list', $this->data);
        $this->template->_init();
        $this->template->table();
        $this->output->set_title(lang('heading'));
    }

    public function form($token = '')
    {
        $midlleware =  $this->midlleware->form($token);
        $data = $midlleware['data'];
        $id = $midlleware['id'];
        $this->load->library('form_builder');
        $form = [
            array(
                'id' => $this->table_id_key,
                'type' => 'hidden',
                'value' => ($data) ? $id : '',
            ),
            array(
                'id' => 'name',
                'value' => ($data) ? $data->name : '',
                'label' => 'Nama',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'type_approval',
                'value' => ($data) ? $data->type_approval : '',
                'label' => 'Type Approval',
                'required' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-nonserverside',
                'options' => ['1' => 'Series', '2' => 'Paralel'],
            ),

        ];

        $this->data['form'] = [
            'action' => $this->data['module_url'] . 'save',
            'build' => $this->form_builder->build_form_horizontal($form),
            'class' => '',
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
        $this->load->library('form_validation');
        $_POST = $this->m_master->form_set_all_trim($_POST);
        $data = $this->input->post(null, true);

        $validation = $this->midlleware->validation();

        if ($validation['status'] == 'success') {
            $this->repository->save($data);
            $return = array('message' => sprintf(lang('save_success'), lang('heading') . ' ' . $data['name']), 'status' => 'success', 'redirect' => $this->data['module_url']);
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

        $dataToken = get_jwt_decryption($token);
        $id = $dataToken->id;

        Capsule::beginTransaction();
        try {

            $act = Approval_rule_model_eloquent::findOrFail($id);
            $act->delete();

            $return = ['message' => sprintf(lang('delete_success'), lang('heading') . ' ' . $act->name), 'status' => 'success'];

            Capsule::commit();
        } catch (\Throwable $th) {
            Capsule::rollback();
            $return = array('message' => $th->getMessage(), 'status' => 'error');
        }

        echo json_encode($return);
    }
}
