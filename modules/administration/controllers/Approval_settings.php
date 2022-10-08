<?php

use Modules\administration\repository\approval\ApprovalRuleRepository;
use Modules\administration\repository\approval\ApprovalSettingRepository;
use Modules\administration\midlleware\Approval_role_midlleware;


defined('BASEPATH') or exit('No direct script access allowed!');


class Approval_settings extends CI_Controller
{
    protected $repositoryApproval;
    protected $repositorySettings;
    protected $midllewareApproval;
    protected $midllewareSettings;

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'administration/approval_settings';
        $this->aauth->control($this->perm);

        $this->lang->load('approval_settings', settings('language'));

        $this->data['menu'] = 'administration/approval_settings';
        $this->data['module_url'] = site_url('administration/approval_settings/');
        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'name', 'title' => 'Name', 'class' => 'default-sort', 'sort' => 'asc', 'filter' => ['type' => 'text']],
                '1' => ['name' => 'tag_department', 'title' => 'Department', 'class' => 'no-sort', 'filter' => false],
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];
        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['2'] = ['name' => 'id', 'title' => 'Action', 'class' => 'no-sort text-center', 'width' => '10%', 'filter' => ['type' => 'action']];
        }
        $this->data['filter_name'] = 'table_filter_setting_approval_settings';
        $this->repositoryApproval = new ApprovalRuleRepository();
        $this->repositorySettings = new ApprovalSettingRepository();
        $this->midllewareApproval = new Approval_role_midlleware();
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
        $midllewareApproval =  $this->midllewareApproval->form($token);
        $approval_rule_id = $midllewareApproval['id'];
        $dataApproval = $this->repositoryApproval->findByID($approval_rule_id);

        $this->output->set_title((!empty($id) ? lang('edit') : lang('add')) . ' ' .  lang('heading'));
        $this->data['headingOverwrite'] = 'Form ' . lang('heading');
        $this->template->_init();
        $this->template->form();
        $moduleData = $this->data;
        $iconBtn = [
            'cancel_w_icon' => lang('cancel_w_icon'),
            'save_w_icon' => lang('save_w_icon'),
        ];
        $this->data['dataApproval'] = $this->m_master->encodeToPropVue($dataApproval);
        $this->data['iconBtn'] =  $this->m_master->encodeToPropVue($iconBtn);
        $this->data['moduleData'] =  $this->m_master->encodeToPropVue($moduleData);
        $this->load->view('approval_settings_form', $this->data);
    }

    public function save()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $token = $this->input->post('token');
        $dataAll = $this->m_master->decode_token($token);
        $validation = $this->repositorySettings->validationManually($dataAll);
        if ($validation['status'] == 'success') {
            $create = $this->repositorySettings->create($dataAll);
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

        $get_data = $this->repositorySettings->datatable($start, $length, $filter, $order, $this->data['table']);
        $output = $this->repositorySettings->setOutputDatatable($get_data, $draw);
        echo json_encode($output);
    }

    public function delete($token)
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $dataToken = get_jwt_decryption($token);
        $id = $dataToken->id;

        $delete = $this->repositorySettings->delete($id);
        if ($delete['status'] == 'success') {
            $return = ['message' => sprintf(lang('delete_success'), lang('heading')), 'status' => 'success'];
        } else {
            $return =  $delete;
        }

        echo json_encode($return);
    }
}
