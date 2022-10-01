<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Attribute_groups extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'master/attribute/attribute_groups';
        $this->table_id_key = 'attribute_group_detail' . '_id'; // 'warehouse_sub_id';
        $this->table_id_key_2 = 'attribute_group' . '_id'; // 'warehouse_sub_id';
        $this->aauth->control($this->perm);
        $this->class_name = get_class($this);
        $this->class_model = strtolower($this->class_name) . '_model';
        $this->lang->load(strtolower($this->class_name), settings('language'));
        $this->data['menu'] = $this->perm;
        $this->data['module_url'] = site_url($this->perm . '/');

        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'a1.museum_id', 'title' => 'Museum', 'width' => '15%', 'filter' => ['type' => 'dropdown', 'options' => $this->m_master->get_dropdown('museums', 'museum_id', 'name')], 'class' => 'default-sort', 'sort' => 'asc'],
                '1' => ['name' => 'a.attribute_group_id', 'title' => 'Group', 'width' => '15%', 'filter' => ['type' => 'dropdown', 'options' => $this->m_master->get_dropdown_filter_museum('attribute_groups', 'attribute_group_id', 'name')], 'class' => 'default-sort', 'sort' => 'asc'],
                '2' => ['name' => 'a.attribute_id', 'title' => 'Atribut', 'filter' => ['type' => 'dropdown', 'options' => $this->m_master->get_dropdown_filter_museum('attributes', 'attribute_id', 'name')], 'class' => 'default-sort', 'sort' => 'asc'],
                '3' => ['name' => 'a.created_at', 'title' => lang('created_at'), 'filter' => false, 'class' => 'default-sort', 'sort' => 'asc'],
                '4' => ['name' => 'a.created_by', 'title' => lang('created_by'), 'filter' => false, 'class' => 'no-sort', 'sort' => 'asc'],
                '5' => ['name' => 'a.updated_at', 'title' => lang('updated_at'), 'filter' => false, 'class' => 'default-sort', 'sort' => 'asc'],
                '6' => ['name' => 'a.updated_by', 'title' => lang('updated_by'), 'filter' => false, 'class' => 'no-sort', 'sort' => 'asc'],
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];

        // if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
        //     $this->data['table']['columns']['7'] = ['name' => 'id', 'title' => '', 'class' => 'no-sort text-center', 'width' => '5%', 'filter' => ['type' => 'action']];
        // }

        $this->data['filter_name'] = 'table_filter_' . $this->class_model;
        $this->table = 'attribute_group_details';
        $this->table_2 = 'attribute_groups';
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

        // $options = '';
        // for ($i = 0; $i < count($data_museum); $i++) {
        //     $d = $data_museum[$i];
        //     $options = $options . '<li><a href="javascript:void(0);" class="btn-list-group" data-id="' . $d['museum_id'] . '">' . $d['name'] . '</a></li>';
        // }


        $this->data['btn_option'] = $this->aauth->is_allowed($this->perm . '/add')
            ? '<a href="' . $this->data['module_url'] . 'form" class="btn btn-sm btn-secondary"><i class="ace-icon fa fa fa-plus-circle bigger-110"></i> Add Group</a>
            <a href="javascript:void(0);" class="btn btn-sm btn-secondary show-list-group"><i class="ace-icon fa fa fa-database bigger-110"></i> Lis Group</a>' : '';

        $this->data['data_museum'] = $data_museum;

        $script_add = '<script type="text/javascript">const dataModule = ' . json_encode($this->data) . '</script>';
        $this->data['script_add'] = $script_add;


        $this->load->view('default/list', $this->data);
        $this->template->_init();
        $this->template->table();
        $this->output->set_title(lang('heading'));
        $this->load->js('assets/custom/js/museum/attribute_groups.js');
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
                    $data['museum_name'],
                    $data['attribute_group_name'],
                    $data['attribute_name'],
                    get_date_time($data['created_at']),
                    $this->m_master->get_username_by($data['created_by']),
                    get_date_time($data['updated_at']),
                    $this->m_master->get_username_by($data['updated_by']),
                    //     '<div class="btn-group ' . $showBtnAction . '">
                    //     <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false">
                    //         <i class="fa fa-pencil"></i>
                    //         <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                    //     </button>
                    //     <ul class="dropdown-menu pull-right">
                    //     ' . ($this->aauth->is_allowed($this->perm . '/edit') ? '<li><a href="' . $this->data['module_url'] . 'form/' . $encry . '">Edit</a></li>' : '') . '
                    //     ' . ($this->aauth->is_allowed($this->perm . '/delete_details') ? '<li><a class="delete_row_default" href="' . $this->data['module_url'] . 'delete/' . $encry . '">Delete</a></li>' : '') . '
                    //     </ul>
                    // </div>'

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
            $data = $this->m_master->get($this->table_2, array($this->table_id_key_2 => $id));
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
                'id' => $this->table_id_key_2,
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
                'options' => $this->m_master->get_dropdown('museums', 'museum_id', 'name', false, array(), false),
            ),
            array(
                'id' => 'name',
                'value' => ($data) ? $data->name : '',
                'label' => 'Nama',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'descriptions',
                'value' => ($data) ? $data->descriptions : '',
                'label' => 'Deskripsi',
                'type' => 'textarea',
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
                'field' => 'museum_id',
                'label' => 'museum_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'name',
                'label' => 'Nama',
                'rules' => 'required'
            ),
        );
        $this->form_validation->set_rules($form_validation_arr);

        if ($this->form_validation->run() === true) {



            do {
                if (!$data[$this->table_id_key_2]) {
                    $data['created_at'] =  Date('Y-m-d H:i:s');
                    $data['created_by'] =  $this->data['user']->id;
                    $this->db->insert($this->table_2, $data);
                } else {
                    $data['updated_at'] =  Date('Y-m-d H:i:s');
                    $data['updated_by'] =  $this->data['user']->id;
                    $this->db->where($this->table_id_key_2, $data[$this->table_id_key_2]);
                    $this->db->update($this->table_2, $data);
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

        $data = $this->m_master->get($this->table_2, [$this->table_id_key_2 => $id]);
        $this->db->where($this->table_id_key_2, $id);
        $this->db->update($this->table_2, ['active' => 0]);
        $return = ['message' => sprintf(lang('delete_success'), lang('heading') . ' ' . $data->name), 'status' => 'success'];

        echo json_encode($return);
    }


    public function form_details($token = '')
    {
        $data = array();
        if ($token) {
            $dataToken = get_jwt_decryption($token);
            $id = $dataToken->id;
            $this->aauth->control($this->perm . '/edit');
            // $data = $this->m_master->get($this->table_2, array($this->table_id_key_2 => $id));
            $data = $this->db
                ->where('a.attribute_group_id', $id)
                ->join('museums AS b', 'b.museum_id = a.museum_id', 'left')
                ->select('a.*, b.name AS museum_name')
                ->get('attribute_groups AS a')->row();
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
                'id' => $this->table_id_key_2,
                'type' => 'hidden',
                'value' => ($data) ? $id : '',
            ),
            // array(
            //     'id' => $data->attribute_group_id,
            //     // 'type' => 'hidden',
            //     'value' => ($data) ? $id : '',
            // ),
            array(
                'id' => 'museum_name',
                'value' => ($data) ? $data->museum_name : '',
                'label' => 'Group',
                'disabled' => 'true',
                'form_control_class' => 'col-md-4',

            ),
            array(
                'id' => 'attribute_group_id',
                'value' => ($data) ? $data->attribute_group_id : '',
                'label' => 'Group',
                'disabled' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-nonserverside',
                'options' => $this->m_master->get_dropdown_filter_museum('attribute_groups', 'attribute_group_id', 'name', false, array(), false),
            ),
            array(
                'id' => 'panel_div_attribut',
                'type' => 'html',
                'label' => 'Atribut yang sudah di tambahkan',
                'form_control_class' => 'col-md-4',
                'html' => $this->_html_attribut_exiting($data)
            ),
            array(
                'id' => 'panel_div_attribut',
                'type' => 'html',
                'label' => 'Tambah Atribut',
                'form_control_class' => 'col-md-4',
                'html' => $this->_html_attribut($data)
            ),
            // array(
            //     'id' => 'attribute_id',
            //     'value' => ($data) ? $data->attribute_id : '',
            //     'label' => 'Atribut',
            //     'required' => 'true',
            //     'type' => 'dropdown',
            //     'form_control_class' => 'col-md-4',
            //     'class' => 'select2-nonserverside',
            //     'options' => $this->m_master->get_dropdown_filter_museum('attributes', 'attribute_id', 'name', false, array(), false),
            // ),

        ];

        $this->data['form'] = [
            'action' => $this->data['module_url'] . 'save_details',
            'build' => $this->form_builder->build_form_horizontal($form),
            'class' => '',
        ];

        $this->data['data'] = $data;
        $this->data['museum_id'] = $data->museum_id;
        $this->data['attribute_group_id'] = $data->attribute_group_id;
        $script_add = '<script type="text/javascript">const dataModule = ' . json_encode($this->data) . '</script>';
        $this->data['script_add'] = $script_add;

        $this->template->_init();
        $this->template->form();
        $this->output->set_title(($this->data['data'] ? lang('edit') : lang('add')) . ' ' . lang('heading'));
        $this->load->view('default/form', $this->data);
        $this->load->js('assets/custom/js/museum/attribute_groups_form.js');
    }

    public function save_details()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $this->load->library('form_validation');
        // $_POST = $this->m_master->form_set_all_trim($_POST);
        $data = $this->input->post(null, true);

        $attribute_id = array_filter($data['attribute_id']);

        if (count($attribute_id) > 0) {

            for ($i = 0; $i < count($attribute_id); $i++) {
                $data_insert = array(
                    'attribute_group_id' => $data['attribute_group_id'],
                    'attribute_id' => $attribute_id[$i],
                    'active' => 1
                );
                $data_insert['created_at'] =  Date('Y-m-d H:i:s');
                $data_insert['created_by'] =  $this->data['user']->id;
                $this->db->insert('attribute_group_details', $data_insert);
            }

            $return = array('message' => sprintf(lang('save_success'), lang('heading') . ' Group'), 'status' => 'success', 'redirect' => $this->data['module_url']);
        } else {
            $return = array('message' => validation_errors(), 'status' => 'error');
        }

        if (isset($return['redirect'])) {
            $this->session->set_flashdata('form_response_status', $return['status']);
            $this->session->set_flashdata('form_response_message', $return['message']);
        }
        echo json_encode($return);
    }

    public function delete_details($token)
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');

        $dataToken = get_jwt_decryption($token);
        $id = $dataToken->id;

        $data = $this->m_master->get($this->table, [$this->table_id_key => $id]);
        $this->db->where($this->table_id_key, $id);
        $this->db->update($this->table, ['active' => 0]);
        $return = ['message' => sprintf(lang('delete_success'), lang('heading') . ' Group'), 'status' => 'success'];

        echo json_encode($return);
    }

    public function getgroup()
    {

        $museum_id = $_GET['museum_id'];

        $this->db->where('a.active', 1);
        $this->db->where('a.museum_id', $museum_id);

        $museums = (isset($this->session->userdata('user')->museums)) ? $this->session->userdata('user')->museums : [];
        if (count($museums) > 0) {
            $list_museum = array();
            for ($g = 0; $g < count($museums); $g++) {
                array_push($list_museum, $museums[$g]->museum_id);
            }
            $this->db->where_in('a.museum_id', $list_museum);
        }

        $data = $this->db
            ->join('museums AS b', 'b.museum_id = a.museum_id', 'left')
            ->select('a.*, b.name AS museum_name')
            ->order_by('a.attribute_group_id', 'DESC')->get('attribute_groups AS a')->result_array();

        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                $d = $data[$i];
                $payload = array(
                    'id' => $d['attribute_group_id']
                );
                $encry = get_jwt_encryption($payload);
                $data[$i]['encry'] = $encry;

                // get taol atribut
                $data[$i]['total_attribute'] = $this->db
                    ->where('attribute_group_id', $d['attribute_group_id'])
                    ->where('active', 1)
                    ->from('attribute_group_details')->count_all_results();
            }
        }

        return print_r(json_encode($data));
    }

    public function _html_attribut()
    {
        $html = '<div class="row">
            <div class="col-sm-12">
                <select size="10" name="attribute_id[]" id="attribute_id" multiple="multiple"></select>
            </div>
        </div>';
        return $html;
    }

    public function _html_attribut_exiting($data)
    {
        $data_atribut = array();
        if ($data) {
            $data_atribut = $this->db
                ->where('a.attribute_group_id', $data->attribute_group_id)
                ->where('a.active', 1)
                ->join('benda_attributes AS b', 'b.attribute_group_detail_id = a.attribute_group_detail_id', 'left')
                ->join('attributes AS c', 'c.attribute_id = a.attribute_id', 'left')
                ->select('a.*, b.benda_attribute_id, c.name AS attribute_name')
                ->get('attribute_group_details AS a')->result_array();
        }

        if (count($data_atribut) > 0) {
            $li = '';
            for ($i = 0; $i < count($data_atribut); $i++) {
                $d = $data_atribut[$i];
                $btn_remove = ($d['benda_attribute_id'] != '' && $d['benda_attribute_id'] != null && $d['benda_attribute_id'] != 'null') ? ''
                    : ' | <a data-id="' . $d['attribute_group_detail_id'] . '" class="remove-att-group-detail" href="javascript:void(0)">Hapus</a>';
                $li = $li . '<li id="attribute_group_detail_id_' . $d['attribute_group_detail_id'] . '">' . $d['attribute_name'] . $btn_remove . '</li>';
            }
            $html = '<ol>' . $li . '</ol>';
        } else {
            $html = '-';
        }

        return $html;
    }

    public function select2attribute()
    {
        $museum_id = $_GET['museum_id'];
        $attribute_group_id = $_GET['attribute_group_id'];
        $term = $_GET['term'];
        // print_r($attribute_group_id);

        $this->db->like('name', $term);
        $data = $this->db
            ->where('a.museum_id', $museum_id)
            ->select('a.attribute_id AS id, a.name AS text')
            ->get('attributes AS a')->result_array();

        $results = array();
        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                # code...
                $ck = $this->db
                    ->where('attribute_group_id', $attribute_group_id)
                    ->where('attribute_id', $data[$i]['id'])
                    ->where('active', 1)
                    ->from('attribute_group_details')->count_all_results();

                if ($ck <= 0) {
                    array_push($results, $data[$i]);
                }
            }
        }

        $result = array(
            'results' => $results
        );

        return print_r(json_encode($result));
    }

    public function removeGroupDetails()
    {
        $attribute_group_detail_id = $_GET['attribute_group_detail_id'];
        $this->db->where('attribute_group_detail_id', $attribute_group_detail_id);
        $this->db->update('attribute_group_details', array('active' => 0));
        return print_r(1);
    }
}
