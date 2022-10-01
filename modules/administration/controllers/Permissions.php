<?php

defined('BASEPATH') or exit('No direct script access allowed!');

class Permissions extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'administration/user_management/permissions';
        $this->aauth->control($this->perm);

        $this->lang->load('permissions', settings('language'));

        $this->data['menu'] = 'administration/user_management/permissions';
        $this->data['module_url'] = site_url('administration/user_management/permissions/');
        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'name', 'title' => lang('name'), 'class' => 'default-sort', 'sort' => 'asc', 'filter' => ['type' => 'text']],
                '1' => ['name' => 'definition', 'title' => lang('definition'), 'filter' => ['type' => 'text']],
                '2' => ['name' => 'help_uri', 'title' => 'Help URI', 'filter' => ['type' => 'text']]
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];
        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['3'] = ['name' => 'id', 'title' => '', 'class' => 'no-sort text-center', 'width' => '10%', 'filter' => ['type' => 'action']];
        }
        $this->data['filter_name'] = 'table_filter_setting_permission';
        $this->table = 'aauth_perms';
    }

    public function index()
    {
        $this->data['btn_option'] = $this->aauth->is_allowed($this->perm . '/add') ? '<a href="' . $this->data['module_url'] . 'form" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> ' . lang('add') . '</a>' : '';

        $this->load->view('default/list', $this->data);
        $this->template->_init();
        $this->template->table();
        $this->output->set_title(lang('heading'));
    }

    public function get_list()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $this->load->library('datatable');

        $this->session->set_userdata($this->data['filter_name'], $this->input->post('filter'));

        $this->datatable->select("name, definition, help_uri, id");
        $this->datatable->from($this->table);
        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->datatable->edit_column("3", '<div class="action-buttons">' .
                ($this->aauth->is_allowed($this->perm . '/edit') ? '<a class="' . lang('button_edit_class') . '" href="' . $this->data['module_url'] . 'form/' . '$1">' . lang('button_edit') . '</a>' : '') .
                ($this->aauth->is_allowed($this->perm . '/delete') ? '<a tabindex="-1" class="' . lang('button_delete_class') . '  delete_row_default" href="' . $this->data['module_url'] . 'delete/' . '$1">' . lang('button_delete') . '</a>' : '') .
                '</div>
                    ', "id");
        }
        $output = json_decode($this->datatable->generate(), true);
        echo json_encode($output);
    }

    public function form($id = '')
    {
        $data = array();
        if ($id) {
            $this->aauth->control($this->perm . '/edit');
            $data = $this->m_master->get($this->table, array('id' => $id));
            if (!$data) {
                show_404();
                exit();
            }
        } else {
            $this->aauth->control($this->perm . '/add');
        }

        $this->load->library('form_builder');

        $form = [
            array(
                'id' => 'perm',
                'type' => 'hidden',
                'value' => ($data) ? $data->name : '',
            ),
            array(
                'id' => 'name',
                'value' => ($data) ? $data->name : '',
                'label' => lang('name'),
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'definition',
                'value' => ($data) ? $data->definition : '',
                'label' => lang('definition'),
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'help_uri',
                'value' => ($data) ? $data->help_uri : '',
                'label' => 'Help URL',
                'type' => 'textarea',
                'form_control_class' => 'col-md-4',
            )

        ];

        if (!$data) {
            array_push($form, array(
                'id' => 'is_crud',
                'value' => '1',
                'label' => 'Is Crud',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-nonserverside',
                'options' => ['1' => 'Yes', '0' => 'No'],
            ));
        }

        $this->data['form'] = [
            'action' => $this->data['module_url'] . 'save',
            'build' => $this->form_builder->build_form_horizontal($form),
            'class' => '',
        ];

        $this->data['data'] = $data;

        $script_add = '<script type="text/javascript">const dataModule = ' . json_encode($this->data) . '</script>';
        $this->data['script_add'] = $script_add;

        $this->template->_init();
        $this->template->form();
        $this->output->set_title(($this->data['data'] ? lang('edit') : lang('add')) . ' ' . lang('heading'));
        $this->load->view('default/form', $this->data);
        $this->load->js('assets/custom/js/permissions.js');
    }

    public function save()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'lang:name', 'required');

        if ($this->form_validation->run() === true) {
            $data = $this->input->post(null, true);
            do {
                if (!$data['perm']) {
                    $is_crud = (isset($data['is_crud'])) ? $data['is_crud'] : '0';
                    $save = $this->aauth->create_perm($data['name'], $data['definition'], $data['help_uri'], $is_crud, $this->data['user']->id);
                    if (!$save) {
                        $return = array('message' => $this->aauth->print_infos(), 'status' => 'error');
                        break;
                    }
                } else {
                    $this->aauth->update_perm($data['perm'], $data['name'], $data['definition'], $data['help_uri'], $this->data['user']->id);
                }
                $return = array('message' => sprintf(lang('save_success'), lang('heading') . ' ' . $data['name']), 'status' => 'success', 'redirect' => $this->data['module_url']);
            } while (0);
        } else {
            $return = array('message' => validation_errors(), 'status' => 'error');
        }

        if (isset($return['redirect'])) {
            $this->session->set_flashdata('form_response_status', $return['status']);
            $this->session->set_flashdata('form_response_message', $return['message']);
        }
        echo json_encode($return);
    }

    public function delete($id)
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');

        $data = $this->m_master->get($this->table, ['id' => $id]);
        $this->aauth->delete_perm($id);
        $return = ['message' => sprintf(lang('delete_success'), lang('heading') . ' ' . $data->name), 'status' => 'success'];

        echo json_encode($return);
    }
}
