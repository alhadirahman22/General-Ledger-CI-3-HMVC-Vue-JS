<?php

defined('BASEPATH') or exit('No direct script access allowed');

class History extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'benda/history';
        $this->table_id_key = 'history' . '_id'; // 'warehouse_sub_id';
        $this->aauth->control($this->perm);
        $this->class_name = get_class($this);
        $this->class_model = strtolower($this->class_name) . '_model';
        $this->lang->load(strtolower($this->class_name), settings('language'));
        $this->data['menu'] = $this->perm;
        $this->data['module_url'] = site_url($this->perm . '/');

        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'a.museum_id', 'title' => 'Museum', 'width' => '10%', 'filter' => ['type' => 'dropdown', 'options' => $this->m_master->get_dropdown_filter_museum('museums', 'museum_id', 'name')], 'class' => 'default-sort', 'sort' => 'asc'],
                '1' => ['name' => 'a.title', 'title' => 'Judul', 'width' => '20%', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '2' => ['name' => 'a.synopsis', 'title' => 'Sinopsis', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '3' => ['name' => 'a.created_at', 'title' => 'Link', 'filter' => false, 'class' => 'no-sort', 'sort' => 'asc'],
                // '3' => ['name' => 'a.created_by', 'title' => lang('created_by'), 'filter' => false, 'class' => 'no-sort', 'sort' => 'asc'],
                // '4' => ['name' => 'a.updated_at', 'title' => lang('updated_at'), 'filter' => false, 'class' => 'default-sort', 'sort' => 'asc'],
                // '5' => ['name' => 'a.updated_by', 'title' => lang('updated_by'), 'filter' => false, 'class' => 'no-sort', 'sort' => 'asc'],
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];

        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['4'] = ['name' => 'id', 'title' => '', 'class' => 'no-sort text-center', 'width' => '5%', 'filter' => ['type' => 'action']];
        }

        $this->data['filter_name'] = 'table_filter_' . $this->class_model;
        $this->table = 'histories';
        $this->load->model($this->class_model, 'main_model');
    }

    public function index()
    {

        $this->db->where('a.active', 1);

        $museums = (isset($this->session->userdata('user')->museums)) ? $this->session->userdata('user')->museums : [];

        if (count($museums) > 0) {
            $list_museum = array();
            for ($g = 0; $g < count($museums); $g++) {
                array_push($list_museum, $museums[$g]->museum_id);
            }
            $this->db->where_in('a.museum_id', $list_museum);
        }

        $data_museum = $this->db->order_by('a.museum_id', 'DESC')->get('museums AS a')->result_array();
        if (count($data_museum) > 0) {
            for ($i = 0; $i < count($data_museum); $i++) {
                $payload = array(
                    'museum_id' => $data_museum[$i]['museum_id']
                );
                $encry = get_jwt_encryption($payload);
                $data_museum[$i]['encry'] = $encry;
            }
        }

        if (count($data_museum) > 1) {
            $this->data['btn_option'] = $this->aauth->is_allowed($this->perm . '/add')
                ? '<a href="javascript:void(0);" class="btn btn-sm btn-primary show-list-museum"><i class="ace-icon fa fa fa-plus-circle bigger-110"></i> Add</a>' : '';
        } else {
            $this->data['btn_option'] = $this->aauth->is_allowed($this->perm . '/add')
                ? '<a href="' . $this->data['module_url'] . 'form/' . $data_museum[0]['encry'] . '" class="btn btn-sm btn-primary"><i class="ace-icon fa fa fa-plus-circle bigger-110"></i> Add</a>' : '';
        }


        $this->data['data_museum'] = $data_museum;

        $script_add = '<script type="text/javascript">const dataModule = ' . json_encode($this->data) . '</script>';
        $this->data['script_add'] = $script_add;

        $this->load->view('default/list', $this->data);
        $this->template->_init();
        $this->template->table();
        $this->output->set_title(lang('heading'));
        $this->load->js('assets/custom/js/museum/history.js');
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
                    'museum_id' => $data['museum_id'],
                    'id' => $data[$this->table_id_key],
                );
                $encry = get_jwt_encryption($payload);

                $totalLink = $this->db
                    ->where('active', 1)
                    ->where($this->table_id_key, $data[$this->table_id_key])
                    ->from('bendas')->count_all_results();

                $view_link = ($totalLink > 0) ? '<a href="javascript:void(0);" class="show-link-history" data-id="' . $data[$this->table_id_key] . '">' . $totalLink . '</a>' : $totalLink;
                $btn_delete = ($totalLink > 0) ? '' : '<li><a class="delete_row_default" href="' . $this->data['module_url'] . 'delete/' . $encry . '">Delete</a></li>';

                $output['data'][] = array(
                    $data['museum_name'],
                    $data['title'],
                    '<div style="max-height:150px;overflow:auto;">' . $data['synopsis'] . '</div>',
                    '<div style="text-align:center;">' . $view_link . '</div>',
                    // $this->m_master->get_username_by($data['created_by']),
                    // get_date_time($data['updated_at']),
                    // $this->m_master->get_username_by($data['updated_by']),
                    '<div class="btn-group ' . $showBtnAction . '">
                    <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false">
                        <i class="fa fa-pencil"></i>
                        <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                    </button>
                    <ul class="dropdown-menu pull-right">
                    ' . ($this->aauth->is_allowed($this->perm . '/edit') ? '<li><a href="' . $this->data['module_url'] . 'form/' . $encry . '">Edit</a></li>' : '') . '
                    ' . ($this->aauth->is_allowed($this->perm . '/delete') ? $btn_delete : '') . '
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
        $dataToken = get_jwt_decryption($token);
        if ($token && isset($dataToken->id)) {
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
                'type' => 'hidden',
                'value' => $dataToken->museum_id,
            ),
            array(
                'id' => 'museum_id_view',
                'value' => $dataToken->museum_id,
                'label' => 'Museum',
                'disabled' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-nonserverside',
                'options' => $this->m_master->get_dropdown_filter_museum('museums', 'museum_id', 'name', false, array(), false),
            ),
            array(
                'id' => 'title',
                'value' => ($data) ? $data->title : '',
                'label' => 'Judul',
                'required' => 'true',
                // 'type' => 'textarea',
                'form_control_class' => 'col-md-9',
            ),
            array(
                'id' => 'synopsis',
                'value' => ($data) ? $data->synopsis : '',
                'label' => 'Sinopsis',
                'required' => 'true',
                'type' => 'textarea',
                'form_control_class' => 'col-md-9',
            ),
            array(
                'id' => 'history',
                'value' => ($data) ? $data->history : '',
                'label' => 'Cerita',
                'required' => 'true',
                'type' => 'textarea',
                'form_control_class' => 'col-md-9',
                'class' => 'area-summernote',
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
                'field' => 'title',
                'label' => 'Judul',
                'rules' => 'required'
            ),
            array(
                'field' => 'synopsis',
                'label' => 'Sinopsis',
                'rules' => 'required'
            ),
            array(
                'field' => 'history',
                'label' => 'Cerita',
                'rules' => 'required'
            )
        );
        $this->form_validation->set_rules($form_validation_arr);

        if ($this->form_validation->run() === true) {

            unset($data['files']);

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
                $return = array('message' => sprintf(lang('save_success'), lang('heading') . ' '), 'status' => 'success', 'redirect' => $this->data['module_url']);
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

    public function getLink()
    {
        $history_id = $_GET['history_id'];

        $data = $this->db
            ->where('active', 1)
            ->where('history_id', $history_id)
            ->get('bendas')->result_array();

        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                $payload = array(
                    'museum_id' => $data[$i]['museum_id'],
                    'benda_id' => $data[$i]['benda_id'],
                );
                $data[$i]['encry'] = get_jwt_encryption($payload);
            }
        }

        return print_r(json_encode($data));
    }
}
