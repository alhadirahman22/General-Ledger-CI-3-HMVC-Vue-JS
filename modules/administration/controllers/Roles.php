<?php

defined('BASEPATH') or exit('No direct script access allowed!');

class Roles extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'administration/user_management/roles';
        $this->aauth->control($this->perm);

        $this->lang->load('roles', settings('language'));

        $this->data['menu'] = 'administration/user_management/roles';
        $this->data['module_url'] = site_url('administration/user_management/roles/');
        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'name', 'title' => lang('name'), 'class' => 'default-sort', 'sort' => 'asc', 'filter' => ['type' => 'text']],
                '1' => ['name' => 'definition', 'title' => lang('definition'), 'filter' => ['type' => 'text']],
                '2' => ['name' => 'dashboard', 'title' => 'Dashboard', 'filter' => ['type' => 'text']]
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];
        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['3'] = ['name' => 'id', 'title' => '', 'class' => 'no-sort text-center', 'width' => '10%', 'filter' => ['type' => 'action']];
        }
        $this->data['filter_name'] = 'table_filter_setting_role';
        $this->table = 'aauth_groups';
        $this->load->model('Aauth_groups_model_eloquent');
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

        $this->datatable->select("name, definition, dashboard, id");
        $this->datatable->from($this->table);
        $this->datatable->where('name !=', 'superadmin');
        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->datatable->edit_column("3", '<div class="hidden-sm hidden-xs action-buttons">' .
                ($this->aauth->is_allowed($this->perm . '/edit') ? '<a class="' . lang('button_edit_class') . '" href="' . $this->data['module_url'] . 'form/' . '$1">' . lang('button_edit') . '</a>' : '') .
                '&nbsp  ' .
                ($this->aauth->is_allowed($this->perm . '/delete') ? '<a tabindex="-1" class="' . lang('button_delete_class') . '  delete_row_default" href="' . $this->data['module_url'] . 'delete/' . '$1">' . lang('button_delete') . '</a>' : '') .
                '</div>', "id");
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
                'id' => 'id',
                'type' => 'hidden',
                'value' => ($data) ? $data->id : '',
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
                'id' => 'dashboard',
                'value' => ($data) ? $data->dashboard : '',
                'label' => 'Dashboard',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'permissions[]',
                'type' => 'html',
                'label' => 'Permission',
                'html' => $this->_html_permission($data)
            ),

            // array(
            //     'id' => 'departments[]',
            //     'type' => 'html',
            //     'label' => 'Permission',
            //     'html' => $this->_html_department($data)
            // ),
        ];

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
        $this->load->js('assets/custom/js/role.js');
    }

    public function save()
    {
        #$this->input->is_ajax_request() or exit('No direct post submit allowed!');
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'lang:name', 'required');

        if ($this->form_validation->run() === true) {
            $data = $this->input->post(null, true);

            do {
                if (!$data['id']) {
                    $save = $this->aauth->create_group($data['name'], $data['definition'], $data['dashboard'], $this->data['user']->id);
                    if (!$save) {
                        $return = array('message' => $this->aauth->print_infos(), 'status' => 'error');
                        break;
                    } else {
                        $group_id = $save;
                    }
                } else {
                    $this->m_master->update('aauth_groups', array(
                        'name' => $data['name'],
                        'definition' => $data['definition'],
                        'dashboard' => $data['dashboard'],
                        'updated_at' => Date('Y-m-d H:i:s'),
                        'updated_by' => $this->data['user']->id
                    ), array('id' => $data['id']));
                    $this->m_master->delete('aauth_perm_to_group', array('group_id' => $data['id']));
                    $group_id = $data['id'];
                }

                $permissions = array_filter($data['permissions']);
                if ($permissions) {
                    $data_perm = array();
                    foreach ($permissions as $perm) {
                        array_push($data_perm, array('perm_id' => $perm, 'group_id' => $group_id));
                    }
                    $this->db->insert_batch('aauth_perm_to_group', $data_perm);
                }

                // $departments = array_filter($data['departments']);
                // if ($departments) {
                //     $data_dept = array();
                //     foreach ($departments as $item) {
                //         $id_exp = explode('|', $item);
                //         array_push($data_dept, array('museum_id' => $id_exp[0], 'department_id' => $id_exp[1], 'group_id' => $group_id));
                //     }
                //     $this->db->insert_batch('aauth_department_to_group', $data_dept);
                // }

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

        // check data exist
        $dataCount = Aauth_groups_model_eloquent::withCount(['aauth_users'])->where('id', $id)->first();

        if ($dataCount->aauth_users_count > 0) {
            $return = ['message' => lang('heading') . ' ' . $data->name . ' currently in use', 'status' => 'error'];
        } else {
            $this->db->where('id', $id);
            $this->db->delete($this->table);
            $return = ['message' => sprintf(lang('delete_success'), lang('heading') . ' ' . $data->name), 'status' => 'success'];
        }



        echo json_encode($return);
    }

    private function _html_permission($data)
    {

        $permissions = $this->m_master->gets('aauth_perms', array(), 'name asc');

        $perm_exists = [];
        if ($data) {
            $perm_exist = $this->m_master->gets('aauth_perm_to_group', array('group_id' => $data->id));
            if ($perm_exist) {
                foreach ($perm_exist->result() as $perm) {
                    array_push($perm_exists, $perm->perm_id);
                }
            }
        }

        $options = '';
        if ($permissions) {
            foreach ($permissions->result() as $permission) {
                $options = $options . '<option value="' . $permission->id . '" ' . ($data ? in_array($permission->id, $perm_exists) ? 'selected="selected"' : '' : '') . '>' . $permission->definition . '</option>';
            }
        }


        $html = '<div class="row">
        <div class="col-sm-12">
            <select multiple="multiple" size="10" name="permissions[]" id="duallist">' . $options . '</select>

            <div class="hr hr-16 hr-dotted"></div>
        </div>
    </div>';

        return $html;
    }

    // private function _html_department($data)
    // {
    //     $permissions = $this->db
    //         ->join('museums AS b', 'b.museum_id = a.museum_id', 'left')
    //         ->select('a.*, b.name AS museum_name')
    //         ->get('departments AS a');

    //     $perm_departments = [];
    //     if ($data) {
    //         $data_perm_departments = $this->m_master->gets('aauth_department_to_group', array('group_id' => $data->id));
    //         // print_r($perm_departments->result());
    //         // die();
    //         if ($data_perm_departments) {
    //             foreach ($data_perm_departments->result() as $item) {
    //                 array_push($perm_departments, $item->department_id);
    //             }
    //         }
    //     }

    //     $options = '';
    //     foreach ($permissions->result() as $permission) {
    //         $options = $options . '<option value="' . $permission->museum_id . '|' . $permission->department_id . '" ' . ($data ? in_array($permission->department_id, $perm_departments) ? 'selected="selected"' : '' : '') . '>' . $permission->museum_name . ' - ' . $permission->name . '</option>';
    //     }

    //     $html = '<div class="row">
    //         <div class="col-sm-12">
    //             <select multiple="multiple" size="10" name="departments[]" id="duallist2">' . $options . '</select>

    //             <div class="hr hr-16 hr-dotted"></div>
    //         </div>
    //     </div>';

    //     return $html;
    // }
}
