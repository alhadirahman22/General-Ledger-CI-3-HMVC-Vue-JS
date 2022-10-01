<?php

defined('BASEPATH') or exit('No direct script access allowed!');

class Users extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'administration/user_management/users';
        $this->aauth->control($this->perm);

        $this->lang->load('users', settings('language'));

        $this->data['menu'] = 'administration/user_management/users';
        $this->data['module_url'] = site_url('administration/user_management/users/');
        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'a.username', 'title' => lang('username'), 'class' => 'default-sort', 'sort' => 'asc', 'filter' => ['type' => 'text']],
                '1' => ['name' => 'a.email', 'title' => lang('email'), 'filter' => ['type' => 'text']],
                '2' => ['name' => 'c.definition', 'title' => lang('role'), 'filter' => ['type' => 'text']],
                '3' => ['name' => 'd.name', 'title' => lang('employee'), 'filter' => ['type' => 'text']],
                '4' => ['name' => 'last_login', 'title' => lang('last_login'), 'filter' => false]
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];
        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['5'] = ['name' => 'a.id', 'title' => '', 'class' => 'no-sort text-center', 'width' => '10%', 'filter' => ['type' => 'action']];
        }
        $this->data['filter_name'] = 'table_filter_setting_user';
        $this->table = 'aauth_users';
    }

    public function index()
    {
        $this->data['btn_option'] = $this->aauth->is_allowed($this->perm . '/add') ? '<a href="' . $this->data['module_url'] . 'form" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> ' . lang('add') . '</a>' : '';

        $this->template->_init();
        $this->template->table();
        $this->output->set_title(lang('heading'));
        $this->load->view('default/list', $this->data);
    }

    public function get_list()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $this->load->library('datatable');

        $this->session->set_userdata($this->data['filter_name'], $this->input->post('filter'));

        // print_r(date(settings('date_format').' H:i:s', strtotime('2021-07-07 17:24:29')));die();

        $this->datatable->select("a.username, a.email, c.definition, d.name,last_login, a.id");
        $this->datatable->from($this->table . ' a');
        $this->datatable->join('aauth_user_to_group b', 'a.id = b.user_id', 'left');
        $this->datatable->join('aauth_groups c', 'b.group_id = c.id', 'left');
        $this->datatable->join('employees d', 'a.employee_id = d.employee_id', 'left');
        //$this->datatable->where('username !=', 'adminSMC');
        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->datatable->edit_column("5", '<div class="hidden-sm hidden-xs action-buttons">' .
                ($this->aauth->is_allowed($this->perm . '/edit') ? '<a class="' . lang('button_edit_class') . '" href="' . $this->data['module_url'] . 'form/' . '$1">' . lang('button_edit') . '</a>' : '') .
                '&nbsp  ' .
                ($this->aauth->is_allowed($this->perm . '/delete') ? '<a tabindex="-1" class="' . lang('button_delete_class') . ' delete_row_default" href="' . $this->data['module_url'] . 'delete/' . '$1">' . lang('button_delete') . '</a>' : '') .
                '</div>', "a.id");
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
            $role = $this->m_master->get('aauth_user_to_group', ['user_id' => $data->id]);
            $data->role = $role->group_id;
        } else {
            $this->aauth->control($this->perm . '/add');
        }

        $this->load->library('form_builder');

        $form = [
            array(
                'id' => 'id',
                'type' => 'hidden',
                'value' => ($data) ? $data->id : '',
            ),
            array(
                'id' => 'role',
                'value' => ($data) ? $data->role : '',
                'label' => lang('role'),
                'type' => 'dropdown',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
                'options' => $this->_dropdown_roles()
            ),
            array(
                'id' => 'employee_id',
                'label' => 'Employee',
                'required' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside',
                'data-table' => 'employees',
                'data-id' => 'employee_id',
                'data-text' => 'nip,name',
                'data-selected' => ($data) ? $data->employee_id : '',
            ),
            array(
                'id' => 'username',
                'value' => ($data) ? $data->username : '',
                'label' => lang('username'),
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'password',
                'label' => lang('password'),
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'email',
                'value' => ($data) ? $data->email : '',
                'label' => lang('email'),
                'type' => 'email',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
        ];

        if ($data) {
            $form[4]['help'] = '<span class="form-text text-muted">' . lang('password_help') . '</span>';
            unset($form[4]['required']);
        }

        $this->data['form'] = [
            'action' => $this->data['module_url'] . 'save',
            'build' => $this->form_builder->build_form_horizontal($form),
            'class' => 'ajax-token',
        ];

        $this->data['data'] = $data;
        $script_add = '<script type="text/javascript">const dataModule = ' . json_encode($this->data) . '</script>';
        $this->data['script_add'] = $script_add;

        $this->template->_init();
        $this->template->form();
        $this->output->set_title(($this->data['data'] ? lang('edit') : lang('add')) . ' ' . lang('heading'));
        $this->load->js('assets/js/modules/administration/users/index.js');
        // $this->load->view('Users', $this->data);
        $this->load->view('default/form', $this->data);
        $this->load->js('assets/custom/js/role_users.js');
    }

    private function _dropdown_employees($data)
    {
        $rs = [null => lang('not_employee')];
        if (!empty($data) && !empty($data->employee_id)) {
            $d = $this->db->where('employee_id', $data->employee_id)->get('employees')->row();
            $rs[$d->employee_id] = $d->nama;
        }

        return $rs;
    }

    function username_check_blank($str)
    {
        $pattern = '/ /';
        $result = preg_match($pattern, $str);

        if ($result) {
            $this->form_validation->set_message('username_check', 'The %s field can not have a " "');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function save()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $this->load->library('form_validation');

        $dataPost = $this->m_master->getInputToken();

        $this->form_validation->set_data($dataPost);

        $this->form_validation->set_rules('email', 'lang:email', 'required|valid_email');
        $this->form_validation->set_rules('username', 'lang:username', 'required|callback_username_check_blank');
        if (isset($dataPost['employee_id']) && $dataPost['employee_id'] == 0) {
            $dataPost['employee_id'] = NULL;
        }

        if ($this->form_validation->run() === true) {
            $data = $dataPost;
            do {
                if (!$data['id']) {
                    $save = $this->aauth->create_user($data['email'], $data['password'], $data['username'], $data['role'], $data['employee_id'], $this->data['user']->id);
                } else {
                    $save = $this->aauth->update_user($data['id'], $data['email'], (isset($data['password']) ? $data['password'] : FALSE), $data['username'], $data['role'], $data['employee_id'], $this->data['user']->id);
                }
                if (!$save) {
                    $return = array('message' => $this->aauth->print_errors(), 'status' => 'error');
                } else {
                    $return = array('message' => sprintf(lang('save_success'), lang('heading') . ' ' . $data['username']), 'status' => 'success', 'redirect' => $this->data['module_url']);
                }
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
        $this->aauth->delete_user($id);
        $return = ['message' => sprintf(lang('delete_success'), lang('heading') . ' ' . $data->username), 'status' => 'success'];

        echo json_encode($return);
    }

    private function _dropdown_roles()
    {
        $roles = $this->m_master->gets('aauth_groups');
        if ($roles) {
            foreach ($roles->result() as $role) {
                $options[$role->id] = $role->definition;
            }
        }
        return $options;
    }

    public function select2_employees()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $search = $this->input->get('search');
        $addDefaultValue = ['id' => 0, 'text' => lang('not_employee')];
        $get = $this->m_master->load_select2($search, 'employees', ['id' => 'employee_id', 'text' => 'nama'], $addDefaultValue);
        echo json_encode($get);
    }

    public function getemployee()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $employee_id = $_GET['employee_id'];
        $get = $this->db->get_where('employees', array('employee_id' => $employee_id))->result_array();
        echo json_encode($get);
    }
}
