<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kepemilikan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'master/kepemilikan';
        $this->table_id_key = 'kepemilikan' . '_id'; // 'warehouse_sub_id';
        $this->aauth->control($this->perm);
        $this->class_name = get_class($this);
        $this->class_model = strtolower($this->class_name) . '_model';
        $this->lang->load(strtolower($this->class_name), settings('language'));
        $this->data['menu'] = $this->perm;
        $this->data['module_url'] = site_url($this->perm . '/');

        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'a.name', 'title' => 'Nama', 'width' => '15%', 'filter' => false, 'class' => 'default-sort', 'sort' => 'asc'],
                '1' => ['name' => 'a.instansi', 'title' => 'Instansi', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '2' => ['name' => 'a.address', 'title' => 'Alamat', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '3' => ['name' => 'a.pic', 'title' => 'PIC', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '4' => ['name' => 'a.negara_id', 'title' => 'Kewarganegaraan', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '5' => ['name' => 'a.identity', 'title' => 'Identitas', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '6' => ['name' => 'a.no_identity', 'title' => 'No ID', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],

            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];

        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['7'] = ['name' => 'id', 'title' => '', 'class' => 'no-sort text-center', 'width' => '5%', 'filter' => ['type' => 'action']];
        }

        $this->data['filter_name'] = 'table_filter_' . $this->class_model;
        $this->table = 'kepemilikan';
        $this->load->model($this->class_model, 'main_model');
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
                    $data['instansi'],
                    $data['address'],
                    $data['pic'],
                    $data['negara_name'],
                    $data['identity'],
                    $data['no_identity'],
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
                'id' => 'museum_id',
                'value' => ($data) ? $data->museum_id : '',
                'label' => 'Museum',
                'required' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-nonserverside',
                'options' => $this->m_master->get_dropdown_filter_museum('museums', 'museum_id', 'name', false, array(), false),
            ),
            array(
                'id' => 'name',
                'value' => ($data) ? $data->name : '',
                'label' => 'Nama',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'instansi',
                'value' => ($data) ? $data->instansi : '',
                'label' => 'Instansi',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'address',
                'value' => ($data) ? $data->address : '',
                'label' => 'Alamat',
                'type' => 'textarea',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'pic',
                'value' => ($data) ? $data->pic : '',
                'label' => 'PIC',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'negara_id',
                'label' => 'Kewarganegaraan',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside',
                'data-table' => 'negara',
                'data-id' => 'negara_id',
                'data-text' => 'code,name',
                'data-selected' => ($data) ? $data->negara_id : '',
            ),
            array(
                'id' => 'identity',
                'value' => ($data) ? $data->identity : '',
                'label' => 'Identitas',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-nonserverside',
                'options' => ['ktp' => 'KTP', 'passport' => 'Passport'],
            ),
            array(
                'id' => 'no_identity',
                'value' => ($data) ? $data->no_identity : '',
                'label' => 'No Identitas',
                'form_control_class' => 'col-md-4',
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

        $form_validation_arr = array(
            array(
                'field' => 'name',
                'label' => 'Nama',
                'rules' => 'required'
            )
        );
        $this->form_validation->set_rules($form_validation_arr);

        if ($this->form_validation->run() === true) {



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

        $data = $this->m_master->get($this->table, [$this->table_id_key => $id]);
        $this->db->where($this->table_id_key, $id);
        $this->db->update($this->table, ['active' => 0]);
        $return = ['message' => sprintf(lang('delete_success'), lang('heading') . ' ' . $data->name), 'status' => 'success'];

        echo json_encode($return);
    }
}
