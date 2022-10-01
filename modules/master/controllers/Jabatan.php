<?php

use Repository\administration\DepartmentsRepository;
use Modules\administration\models\Jabatan_model_eloquent;

defined('BASEPATH') or exit('No direct script access allowed');

class Jabatan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'master/jabatan';
        $this->table_id_key = 'jabatan' . '_id'; // 'warehouse_sub_id';
        $this->aauth->control($this->perm);
        $this->class_name = get_class($this);
        $this->class_model = strtolower($this->class_name) . '_model';
        $this->lang->load(strtolower($this->class_name), settings('language'));
        $this->data['menu'] = $this->perm;
        $this->data['module_url'] = site_url($this->perm . '/');

        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'a.name', 'title' => 'Nama', 'filter' => false, 'class' => 'default-sort', 'sort' => 'asc'],
                '1' => ['name' => 'a.level', 'title' => 'Level', 'width' => '15%', 'filter' => false, 'class' => 'default-sort', 'sort' => 'asc'],
                '2' => ['name' => 'b.name', 'title' => 'Department', 'width' => '15%', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '3' => ['name' => 'c.name', 'title' => 'Museum', 'width' => '15%', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];

        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['4'] = ['name' => 'id', 'title' => '', 'class' => 'no-sort text-center', 'width' => '5%', 'filter' => ['type' => 'action']];
        }

        $this->data['filter_name'] = 'table_filter_' . $this->class_model;
        $this->table = 'jabatan';
        $this->load->model($this->class_model, 'main_model');
        $this->DepartmentsRepository = new DepartmentsRepository();
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
        $filter_2 = $this->session->userdata($this->data['filter_name'] . '_2');
        $draw = intval($this->input->post('draw'));

        $start = ($draw == 1) ? (
            (isset($filter_2) && isset($filter_2['start']))
            ? $filter_2['start'] : $this->input->post('start'))
            : $this->input->post('start');
        $length = ($draw == 1) ? ((isset($filter_2) && isset($filter_2['length']))
            ? $filter_2['length'] : $this->input->post('length'))
            : $this->input->post('length');

        $order = $this->input->post('order')[0];
        $filter = $this->input->post('filter');

        $this->session->set_userdata($this->data['filter_name'], $filter);

        $filter_2 = array(
            'start' => $start,
            'length' => $length,
            'page' => $start / $length,
        );
        $this->session->set_userdata($this->data['filter_name'] . '_2', $filter_2);
        $output['data'] = array();
        $datas = $this->main_model->get_all($start, $length, $filter, $order);
        // print_r($this->table_id_key);
        // print_r($datas->result_array());
        // die();
        if ($datas) {
            foreach ($datas->result_array() as $data) {

                $showBtnAction = 'hide';
                if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
                    $showBtnAction = '';
                }

                $payload = array(
                    'id' => $data[$this->table_id_key]
                );
                $encry = get_jwt_encryption($payload);

                $output['data'][] = array(
                    $data['name'],
                    $data['level'],
                    $data['NameDepartment'],
                    $data['NameMuseum'],
                    '<div class="btn-group ' . $showBtnAction . '">
                    <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false">
                        <i class="fa fa-pencil"></i>
                        <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                    </button>
                    <ul class="dropdown-menu pull-right">
                    ' . ($this->aauth->is_allowed($this->perm . '/edit') ? '<li><a href="' . $this->data['module_url'] . 'form/' . $encry . '">Edit</a></li>' : '') . '
                    ' . ($this->aauth->is_allowed($this->perm . '/delete') ? '<li><a class="delete_row_default" href="' . $this->data['module_url'] . 'delete/' . $encry . '">Delete</a></li>' : '') . '
                    </ul>
                </div>'

                );
            }
        }
        $output['draw'] = $draw++;
        $output['recordsTotal'] = $this->main_model->count_all();
        $output['recordsFiltered'] = $this->main_model->count_all($filter);
        echo json_encode($output);
    }

    public function form($token = '')
    {
        $data = array();
        if ($token) {
            $dataToken = get_jwt_decryption($token);
            $id = $dataToken->id;
            $this->aauth->control($this->perm . '/edit');
            $data = $this->m_master->get($this->table, array($this->table_id_key => $id));
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
                'id' => 'level',
                'value' => ($data) ? $data->level : '',
                'label' => 'Level',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
                'type' => 'number',
                'min' => '1',
            ),
            array(
                'id' => 'department_id',
                'value' => ($data) ? $data->department_id : '',
                'label' => 'Department',
                'required' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-nonserverside',
                'options' => $this->getDepartmetnt(),
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

    private function getDepartmetnt()
    {
        $arrFilter = [];
        $arrFilter['active'] = 1;
        $data = $this->DepartmentsRepository->get($arrFilter);

        $op = [];
        for ($i = 0; $i < count($data); $i++) {
            $op[$data[$i]['department_id']] = $data[$i]['museum']['name'] . ' - ' . $data[$i]['name'];
        }

        return $op;
    }

    public function save()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $this->load->library('form_validation');
        $_POST = $this->m_master->form_set_all_trim($_POST);
        $data = $this->input->post(null, true);

        $form_validation_arr = array(
            array(
                'field' => 'name',
                'label' => 'Nama',
                'rules' => 'required'
            ),
            array(
                'field' => 'level',
                'label' => 'Level',
                'rules' => 'required'
            ),
            array(
                'field' => 'department_id',
                'label' => 'Department',
                'rules' => 'required'
            )
        );
        $this->form_validation->set_rules($form_validation_arr);

        if ($this->form_validation->run() === true) {
            $data['museum_id'] = $this->DepartmentsRepository->getMuseumID($data['department_id']);
            do {
                if (!$data[$this->table_id_key]) {
                    $data['created_at'] =  Date('Y-m-d H:i:s');
                    $data['created_by'] =  $this->data['user']->id;
                    $this->db->insert($this->table, $data);
                } else {
                    $data['updated_at'] =  Date('Y-m-d H:i:s');
                    $data['updated_by'] =  $this->data['user']->id;
                    $this->db->where($this->table_id_key, $data[$this->table_id_key]);
                    $this->db->update($this->table, $data);
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

    public function delete($token)
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');

        $dataToken = get_jwt_decryption($token);
        $id = $dataToken->id;

        $data = Jabatan_model_eloquent::find($id);
        $data->delete();

        $return = ['message' => sprintf(lang('delete_success'), lang('heading') . ' ' . $data->name), 'status' => 'success'];

        echo json_encode($return);
    }
}
