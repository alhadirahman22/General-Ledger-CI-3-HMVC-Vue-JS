<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Benda extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'koleksi/benda';
        $this->table_id_key = 'benda_id'; // 'warehouse_sub_id';
        $this->aauth->control($this->perm);
        $this->class_name = get_class($this);
        $this->class_model = strtolower($this->class_name) . '_model';
        $this->lang->load(strtolower($this->class_name), settings('language'));
        $this->data['menu'] = $this->perm;
        $this->data['module_url'] = site_url($this->perm . '/');
        $this->file_path = 'uploads/benda/';

        $this->data['table'] = [
            'columns' => [
                '0' => ['name' => 'g.photo', 'title' => 'Foto', 'width' => '9%', 'filter' => false, 'class' => 'default-sort', 'sort' => 'asc'],
                '1' => ['name' => 'a.name', 'title' => 'Nama', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '2' => ['name' => 'a.alias', 'title' => 'Alias', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '3' => ['name' => 'b.name', 'title' => 'Kepemilikan', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '4' => ['name' => 'c.name', 'title' => 'Jenis Perolehan', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '5' => ['name' => 'd.name', 'title' => 'Fungsi', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '6' => ['name' => 'e.name', 'title' => 'Kategori', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
                '7' => ['name' => 'f.name', 'title' => 'Bahan Utama', 'filter' => ['type' => 'text'], 'class' => 'default-sort', 'sort' => 'asc'],
            ],
            'url' => $this->data['module_url'] . 'get_list'
        ];

        if ($this->aauth->is_allowed($this->perm . '/edit') || ($this->aauth->is_allowed($this->perm . '/delete'))) {
            $this->data['table']['columns']['8'] = ['name' => 'id', 'title' => '', 'class' => 'no-sort text-center', 'width' => '5%', 'filter' => ['type' => 'action']];
        }

        $this->data['filter_name'] = 'table_filter_' . $this->class_model;
        $this->table = 'bendas';
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
                ? '<a href="' . $this->data['module_url'] . 'form" disabled class="btn btn-sm btn-primary"><i class="ace-icon fa fa fa-plus-circle bigger-110"></i> Registrasi Benda</a> 
            <a href="javascript:void(0);" class="btn btn-sm btn-primary show-list-museum"><i class="ace-icon fa fa fa-plus-circle bigger-110"></i> Heregistrasi</a>' : '';
        } else {
            $this->data['btn_option'] = $this->aauth->is_allowed($this->perm . '/add')
                ? '<a href="' . $this->data['module_url'] . 'form" disabled class="btn btn-sm btn-primary"><i class="ace-icon fa fa fa-plus-circle bigger-110"></i> Registrasi Benda</a> 
            <a href="' . $this->data['module_url'] . 'form/' . $data_museum[0]['encry'] . '" class="btn btn-sm btn-primary"><i class="ace-icon fa fa fa-plus-circle bigger-110"></i> Heregistrasi</a>' : '';
        }

        $this->data['data_museum'] = $data_museum;

        $script_add = '<script type="text/javascript">const dataModule = ' . json_encode($this->data) . '</script>';
        $this->data['script_add'] = $script_add;

        $this->load->view('default/list', $this->data);
        $this->template->_init();
        $this->template->table();
        $this->output->set_title(lang('heading'));
        $this->load->js('assets/custom/js/museum/benda-list.js');
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
                    'benda_id' => $data[$this->table_id_key],
                );
                $encry = get_jwt_encryption($payload);

                $photo_profile = ($data['photo'] != '' && $data['photo'] != null && $data['photo'] != 'null')
                    ?
                    (file_exists($this->file_path . $data['photo'])
                        ? '<img src="' . base_url() . $this->file_path . $data['photo'] . '" style="width:100%;" />' :
                        '<img src="' . base_url() . 'assets/images/no-pictures.png" style="width:50px;" />'
                    )

                    : '<img src="' . base_url() . 'assets/images/no-pictures.png" style="width:50px;" />';

                $output['data'][] = array(
                    '<div style="text-align:center;">' . $photo_profile . '</div>',
                    $data['name'],
                    $data['alias'],
                    $data['kepemilikan_name'],
                    $data['jenis_perolehan_name'],
                    $data['fungsi_name'],
                    $data['kategori_name'],
                    $data['bahan_utama_name'],
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

    // public function form($token = '')
    // {
    //     $data = array();
    //     if ($token) {
    //         $dataToken = get_jwt_decryption($token);

    //     } else {
    //         $this->aauth->control($this->perm . '/add');
    //     }

    //     $this->load->library('form_builder');

    // }

    public function form($token = '')
    {
        $data = array();
        $dataToken = get_jwt_decryption($token);
        $benda_id = '';
        if ($token) {
            if (isset($dataToken->benda_id)) {
                $benda_id = $dataToken->benda_id;
                $this->aauth->control($this->perm . '/edit');
                $data = $this->m_master->get($this->table, array($this->table_id_key => $benda_id));

                if (!$data) {
                    show_404();
                    exit();
                }
            } else {
                $this->aauth->control($this->perm . '/add');
            }
        } else {
            $this->aauth->control($this->perm . '/add');
        }

        $this->load->library('form_builder');

        $form = [
            array(
                'id' => $this->table_id_key,
                'type' => 'hidden',
                'value' => ($data) ? $benda_id : '',
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
            // array(
            //     'id' => 'photo',
            //     'type' => 'image',
            //     'value' => ($data) ? $data->photo : '',
            //     'label' => 'Photo',
            //     'form_control_class' => 'col-md-4',
            //     'image_path' => base_url() . $this->file_path,
            // ),

            array(
                'id' => 'div_photo',
                'type' => 'html',
                'label' => 'Photo',
                'form_control_class' => 'col-md-12',
                'html' => $this->_html_photos($data)
            ),

            array(
                'id' => 'kategori_id',
                'label' => 'Kategori',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside-filter-museum',
                'data-museumid' => $dataToken->museum_id,
                'data-table' => 'kategori',
                'data-id' => 'kategori_id',
                'data-text' => 'name',
                'data-selected' => ($data) ? $data->kategori_id : '',
            ),

            array(
                'id' => 'name',
                'value' => ($data) ? $data->name : '',
                'label' => 'Nama',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'alias',
                'value' => ($data) ? $data->alias : '',
                'label' => 'Alias',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'registrasi',
                'value' => ($data) ? $data->registrasi : '',
                'label' => 'Registrasi ID',
                // 'type' => 'number',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'inventaris',
                'value' => ($data) ? $data->inventaris : '',
                'label' => 'Inventaris ID',
                // 'type' => 'number',
                'form_control_class' => 'col-md-4',
            ),

            array(
                'id' => 'kepemilikan_id',
                'label' => 'Kepemilikan',
                'required' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside-filter-museum',
                'data-museumid' => $dataToken->museum_id,
                'data-table' => 'kepemilikan',
                'data-id' => 'kepemilikan_id',
                'data-text' => 'name,instansi',
                'data-selected' => ($data) ? $data->kepemilikan_id : '',
            ),

            array(
                'id' => 'jenis_perolehan_id',
                'label' => 'Jenis Perolehan',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside-filter-museum',
                'data-museumid' => $dataToken->museum_id,
                'data-table' => 'jenis_perolehan',
                'data-id' => 'jenis_perolehan_id',
                'data-text' => 'name',
                'data-selected' => ($data) ? $data->jenis_perolehan_id : '',
            ),

            array(
                'id' => 'fungsi_id',
                'label' => 'Fungsi',
                'required' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside-filter-museum',
                'data-museumid' => $dataToken->museum_id,
                'data-table' => 'fungsi',
                'data-id' => 'fungsi_id',
                'data-text' => 'name',
                'data-selected' => ($data) ? $data->fungsi_id : '',
            ),

            array(
                'id' => 'div_sp_1',
                'type' => 'html',
                'label' => '',
                'form_control_class' => 'col-md-12',
                'html' => $this->_html_sparator('Komposisi Benda', '')
            ),

            array(
                'id' => 'bahan_utama',
                'label' => 'Bahan Utama',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside-filter-museum',
                'data-museumid' => $dataToken->museum_id,
                'data-table' => 'bahan',
                'data-id' => 'bahan_id',
                'data-text' => 'name',
                'data-selected' => ($data) ? $data->bahan_utama : '',
            ),

            array(
                'id' => 'bahan_lain',
                'label' => 'Bahan Lain',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside-filter-museum',
                'data-museumid' => $dataToken->museum_id,
                'data-table' => 'bahan',
                'data-id' => 'bahan_id',
                'data-text' => 'name',
                'data-selected' => ($data) ? $data->bahan_lain : '',
            ),

            array(
                'id' => 'pola_id',
                'label' => 'Pola',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside-filter-museum',
                'data-museumid' => $dataToken->museum_id,
                'data-table' => 'pola',
                'data-id' => 'pola_id',
                'data-text' => 'name',
                'data-selected' => ($data) ? $data->pola_id : '',
            ),

            array(
                'id' => 'kondisi_id',
                'label' => 'Kondisi Benda',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside-filter-museum',
                'data-museumid' => $dataToken->museum_id,
                'data-table' => 'kondisi',
                'data-id' => 'kondisi_id',
                'data-text' => 'name',
                'data-selected' => ($data) ? $data->kondisi_id : '',
            ),

            array(
                'id' => 'situs_id',
                'label' => 'Situs',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside-filter-museum',
                'data-museumid' => $dataToken->museum_id,
                'data-table' => 'situs',
                'data-id' => 'situs_id',
                'data-text' => 'name',
                'data-selected' => ($data) ? $data->situs_id : '',
            ),

            array(
                'id' => 'keterangan_kondisi',
                'value' => ($data) ? $data->keterangan_kondisi : '',
                'label' => 'Keterangan Kondisi',
                'type' => 'textarea',
                'form_control_class' => 'col-md-4',
            ),

            array(
                'id' => 'div_sp_1',
                'type' => 'html',
                'label' => '',
                'form_control_class' => 'col-md-12',
                'html' => $this->_html_sparator('Perkiraan Pembuatan', '')
            ),

            array(
                'id' => 'tahun_pembuatan',
                'value' => ($data) ? $data->tahun_pembuatan : '',
                'label' => 'Tahun Pembuatan',
                'form_control_class' => 'col-md-4',
            ),

            array(
                'id' => 'tahun_ditemukan',
                'value' => ($data) ? $data->tahun_ditemukan : '',
                'label' => 'Tahun Ditemukan',
                'form_control_class' => 'col-md-4',
            ),

            array(
                'id' => 'div_sp_1',
                'type' => 'html',
                'label' => '',
                'form_control_class' => 'col-md-12',
                'html' => $this->_html_sparator('Nilai Benda', '')
            ),

            array(
                'id' => 'currency_id',
                'label' => 'Mata Uang',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside',
                'data-table' => 'currency',
                'data-id' => 'currency_id',
                'data-text' => 'code,name',
                'data-selected' => ($data) ? $data->currency_id : '',
            ),

            array(
                'id' => 'currency_value',
                'value' => ($data) ? $data->currency_value : '',
                'label' => 'Nilai (Sesuai Mata Uang)',
                'type' => 'number',
                'form_control_class' => 'col-md-4',
            ),

            array(
                'id' => 'div_sp_1',
                'type' => 'html',
                'label' => '',
                'form_control_class' => 'col-md-12',
                'html' => $this->_html_sparator('Wilayah dibuat', '')
            ),

            array(
                'id' => 'dibuat_id',
                'label' => 'Negara',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside',
                'data-table' => 'negara',
                'data-id' => 'negara_id',
                'data-text' => 'code,name',
                'data-selected' => ($data) ? $data->dibuat_id : '',
            ),

            array(
                'id' => 'dibuat_provinsi',
                'value' => ($data) ? $data->dibuat_provinsi : '',
                'label' => 'Provinsi',
                'form_control_class' => 'col-md-4',
            ),

            array(
                'id' => 'dibuat_kota',
                'value' => ($data) ? $data->dibuat_kota : '',
                'label' => 'Kota',
                'form_control_class' => 'col-md-4',
            ),

            array(
                'id' => 'div_sp_1',
                'type' => 'html',
                'label' => '',
                'form_control_class' => 'col-md-12',
                'html' => $this->_html_sparator('Wilayah ditemukan / didapat', '')
            ),

            array(
                'id' => 'ditemukan_id',
                'label' => 'Negara',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside',
                'data-table' => 'negara',
                'data-id' => 'negara_id',
                'data-text' => 'code,name',
                'data-selected' => ($data) ? $data->ditemukan_id : '',
            ),

            array(
                'id' => 'ditemukan_provinsi',
                'value' => ($data) ? $data->ditemukan_provinsi : '',
                'label' => 'Provinsi',
                'form_control_class' => 'col-md-4',
            ),

            array(
                'id' => 'ditemukan_kota',
                'value' => ($data) ? $data->ditemukan_kota : '',
                'label' => 'Kota',
                'form_control_class' => 'col-md-4',
            ),

            array(
                'id' => 'ditemukan_keterangan',
                'value' => ($data) ? $data->ditemukan_keterangan : '',
                'label' => 'Keterangan Ditemukan',
                'type' => 'textarea',
                'form_control_class' => 'col-md-4',
            ),

            array(
                'id' => 'periode_id',
                'label' => 'Periode',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside',
                'data-table' => 'periode',
                'data-id' => 'periode_id',
                'data-text' => 'name',
                'data-selected' => ($data) ? $data->periode_id : '',
            ),

            array(
                'id' => 'status_id',
                'label' => 'Status Benda',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside',
                'data-table' => 'status',
                'data-id' => 'status_id',
                'data-text' => 'name',
                'data-selected' => ($data) ? $data->status_id : '',
            ),

            // array(
            //     'id' => 'div_sp_1',
            //     'type' => 'html',
            //     'label' => '',
            //     'form_control_class' => 'col-md-12',
            //     'html' => $this->_html_sparator_close()
            // ),

            array(
                'id' => 'div_sp_1',
                'type' => 'html',
                'label' => '',
                'form_control_class' => 'col-md-12',
                'html' => $this->_html_sparator('Atribut', '')
            ),

            array(
                'id' => 'attribute_group_id',
                'label' => 'Group Atribut',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2-serverside-filter-museum',
                'data-museumid' => $dataToken->museum_id,
                'data-table' => 'attribute_groups',
                'data-id' => 'attribute_group_id',
                'data-text' => 'name',
                'data-selected' => ($data) ? $data->attribute_group_id : '',
            ),

            array(
                'id' => 'div_atribut',
                'type' => 'html',
                'label' => 'Detail Atribut',
                'form_control_class' => 'col-md-9',
                'html' => $this->_html_detail_atribut($data)
            ),

            array(
                'id' => 'div_sp_1',
                'type' => 'html',
                'label' => '',
                'form_control_class' => 'col-md-12',
                'html' => $this->_html_sparator('Meta Tag', '')
            ),

            array(
                'id' => 'div_tags',
                'type' => 'html',
                'label' => 'Tags',
                'form_control_class' => 'col-md-4',
                'html' => $this->_html_tags($data)
            ),



        ];

        $this->data['form'] = [
            'action' => $this->data['module_url'] . 'save',
            'build' => $this->form_builder->build_form_horizontal($form),
            'class' => '',
            'page_1' => 8,
            'form' => $form,
        ];

        $this->data['data'] = $data;

        $script_add = '<script type="text/javascript">const dataModule = ' . json_encode($this->data) . '</script>';
        $this->data['script_add'] = $script_add;

        $this->template->_init();
        $this->template->form();
        $this->output->set_title(($this->data['data'] ? lang('edit') : lang('add')) . ' ' . lang('heading'));
        $this->load->view('default/form', $this->data);
        $this->load->css('assets/custom/css/museum/benda.css');
        $this->load->js('assets/custom/js/museum/benda.js');
    }

    public function save()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $this->load->library('form_validation');
        // $_POST = $this->m_master->form_set_all_trim($_POST);
        $data = $this->input->post(null, true);

        // print_r($data);
        // die();

        $form_validation_arr = array(
            // array(
            //     'field' => 'username',
            //     'label' => 'lang:username',
            //     'rules' => $req_username
            // ),
            array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'required'
            )
        );
        $this->form_validation->set_rules($form_validation_arr);

        $data_file_exist = (isset($data['file_exist'])) ? array_filter($data['file_exist']) : [];

        $attribute_group_detail_id = (isset($data['attribute_group_detail_id'])) ? array_filter($data['attribute_group_detail_id']) : [];
        $attribute_group_detail_value = (isset($data['attribute_group_detail_value'])) ? array_filter($data['attribute_group_detail_value']) : [];

        if ($this->form_validation->run() === true) {

            unset($data['userfile']);
            unset($data['file_exist']);
            unset($data['attribute_group_detail_id']);
            unset($data['attribute_group_detail_value']);

            do {
                if (!$data[$this->table_id_key]) {
                    $data['created_at'] =  Date('Y-m-d H:i:s');
                    $data['created_by'] =  $this->data['user']->id;
                    $this->db->insert($this->table, $data);
                    $benda_id = $this->db->insert_id();
                } else {

                    $data['updated_at'] =  Date('Y-m-d H:i:s');
                    $data['updated_by'] =  $this->data['user']->id;
                    $this->db->where($this->table_id_key, $data[$this->table_id_key]);
                    $this->db->update($this->table, $data);
                    $this->db->reset_query();
                    $benda_id = $data[$this->table_id_key];
                }

                // tagging
                if ($data['tags'] != '' && $data['tags'] != null && $data['tags'] != 'null') {
                    $tags = explode(',', $data['tags']);

                    // kurangi jika edit
                    if ($data[$this->table_id_key]) {

                        $dataOldTags = $this->db
                            ->where('a.benda_id', $benda_id)
                            ->join('tags AS b', 'b.tag_id = a.tag_id', 'left')
                            ->select('b.tag_id, b.count')
                            ->get('benda_tags AS a')->result_array();
                        // $this->db->reset_query();
                        if (count($dataOldTags) > 0) {
                            for ($told = 0; $told < count($dataOldTags); $told++) {
                                $_old_count = (int) $dataOldTags[$told]['count'] - 1;
                                $_old_count = ($_old_count > 0) ? $_old_count : 0;
                                $this->db->where('tag_id', $dataOldTags[$told]['count']);
                                $this->db->update('tags', array('count' => $_old_count));
                                $this->db->reset_query();
                            }
                        }
                    }


                    if (count($tags) > 0) {

                        // remove tags
                        $this->db->where('benda_id', $benda_id);
                        $this->db->delete('benda_tags');
                        $this->db->reset_query();

                        for ($i = 0; $i < count($tags); $i++) {
                            $d = trim($tags[$i]);
                            // cek in tags
                            $dataInsTags = array(
                                'name' => $d,
                                'museum_id' => $data['museum_id']
                            );
                            $datatg = $this->db->get_where('tags', $dataInsTags)->result_array();

                            if (count($datatg) > 0) {
                                $count = (int) $datatg[0]['count'] + 1;
                                $this->db->where('tag_id', $datatg[0]['tag_id']);
                                $this->db->update('tags', array('count' => $count));
                                $tag_id = $datatg[0]['tag_id'];
                            } else {
                                $dataInsTags['count'] = 0;
                                $this->db->insert('tags', $dataInsTags);
                                $tag_id = $this->db->insert_id();
                            }

                            $dataInsBendaTags = array(
                                'benda_id' => $benda_id,
                                'tag_id' => $tag_id
                            );

                            $this->db->insert('benda_tags', $dataInsBendaTags);
                        }
                    }
                }

                // atribut
                if (count($attribute_group_detail_id) > 0) {

                    $this->db->where('benda_id', $benda_id);
                    $this->db->delete('benda_attributes');
                    $this->db->reset_query();

                    for ($atbt = 0; $atbt < count($attribute_group_detail_id); $atbt++) {
                        $data_ins_atbt = array(
                            'benda_id' => $benda_id,
                            'attribute_group_detail_id' => $attribute_group_detail_id[$atbt],
                            'attribute_group_detail_value' => $attribute_group_detail_value[$atbt],
                        );
                        $this->db->insert('benda_attributes', $data_ins_atbt);
                    }
                }

                // file image
                $countFile = (isset($_FILES['userfile']['name'])) ? count($_FILES['userfile']['name']) : 0;
                $data_usage_image_list = $data_file_exist; //[];
                // get existing image
                $data_exist_image = $this->db
                    ->get_where('photos', array('benda_id' => $benda_id))
                    ->result_array();
                $data_exist_image_list = [];
                if (count($data_exist_image) > 0) {
                    for ($i = 0; $i < count($data_exist_image); $i++) {
                        array_push($data_exist_image_list, $data_exist_image[$i]['photo']);
                    }
                }
                $path = $this->file_path;

                if ($countFile > 0) {
                    // create folder
                    if (!file_exists($path)) {
                        $oldmask = umask(0);
                        mkdir($path, 0777);
                        umask($oldmask);
                        copy('uploads/index.html', $path . '/index.html');
                        copy('uploads/index.php', $path . '/index.php');
                        // mkdir(, 0777, true);
                    }
                    // ========================

                    $this->load->library('upload');


                    for ($i = 0; $i < $countFile; $i++) {

                        if (!empty($_FILES['userfile']['name'][$i])) {

                            $_FILES['file']['name'] = $_FILES['userfile']['name'][$i];
                            $_FILES['file']['type'] = $_FILES['userfile']['type'][$i];
                            $_FILES['file']['tmp_name'] = $_FILES['userfile']['tmp_name'][$i];
                            $_FILES['file']['error'] = $_FILES['userfile']['error'][$i];
                            $_FILES['file']['size'] = $_FILES['userfile']['size'][$i];

                            if (in_array($_FILES['file']['name'], $data_exist_image_list)) {
                                array_push($data_usage_image_list, $_FILES['file']['name']);
                                // if (in_array($data_file_exist[$i], $data_exist_image_list)) {
                                //     array_push($data_usage_image_list, $data_file_exist[$i]);
                                // } else {

                                // }
                            } else {

                                $config['upload_path'] = './' . $path;
                                $config['allowed_types'] = '*'; //'gif|jpg|png|doc|txt';
                                $config['max_size'] = 1024 * 8;
                                $config['remove_spaces'] = TRUE;
                                $config['encrypt_name'] = TRUE;

                                $this->upload->initialize($config);

                                if (!$this->upload->do_upload('file')) {
                                    $error = array('error' => $this->upload->display_errors());
                                    // print_r($error);
                                } else {
                                    $success = array('success' => $this->upload->data());
                                    // print_r($success);
                                    $file_name = $success['success']['file_name'];

                                    // update database
                                    $arr_img_upt = array(
                                        'benda_id' => $benda_id,
                                        'photo' => $file_name
                                    );

                                    $this->db->insert('photos', $arr_img_upt);
                                }
                            }
                        }
                    }
                }

                // remove existing
                if (count($data_exist_image_list) > 0) {
                    for ($i = 0; $i < count($data_exist_image_list); $i++) {
                        if (!in_array($data_exist_image_list[$i], $data_usage_image_list)) {
                            $path_img = $path . $data_exist_image_list[$i];
                            if (file_exists($path_img)) {
                                unlink($path_img);
                            }
                            $this->db->where('photo', $data_exist_image_list[$i]);
                            $this->db->delete('photos');
                        }
                    }
                }

                // update Cover
                $dataPhotosKover = $this->db->where('benda_id', $benda_id)
                    ->order_by('photo_id', 'ASC')->limit(1)->get('photos')->row();
                if ($dataPhotosKover) {
                    $this->db->where('benda_id', $benda_id);
                    $this->db->update('photos', array('is_cover' => 0));
                    $this->db->reset_query();

                    $this->db->where('photo_id', $dataPhotosKover->photo_id);
                    $this->db->update('photos', array('is_cover' => 1));
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

    public function getTags()
    {
        $value = $_GET['q'];
        $this->db->like('IFNULL(name,"")', $value);
        $data = $this->db->get('tags')->result_array();

        $key = [];
        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                array_push($key, $data[$i]['name']);
            }
        }

        // $dd = ["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Dakota", "North Carolina", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"];

        return print_r(json_encode($key));
    }

    public function _html_sparator($title, $subtitle)
    {
        $html = '<h3 class="header bigger lighter blue">
        # ' . $title . '
        <small>' . $subtitle . '</small>
        </h3>';

        // <div class="hr hr-16 hr-dotted"></div>

        return $html;
    }

    public function _html_sparator_close()
    {
        $html = '<div class="hr hr-16 hr-dotted"></div>';

        return $html;
    }

    public function _html_tags($data)
    {
        $html = '<div class="row">
        <div class="col-md-12">
            <input type="text" name="tags" id="form-field-tags" value="' . (($data) ?  $data->tags : '') . '" placeholder="Enter tags ..." />
            </div>
        </div>';
        return $html;
    }

    public function _html_photos($data)
    {

        $data_img = [];
        if ($data) {
            $data_img = $this->db
                ->order_by('is_cover', 'DESC')
                ->order_by('benda_id', 'ASC')
                ->get_where(
                    'photos',
                    array('benda_id' => $data->benda_id)
                )->result_array();
        }

        $panel_img = '';

        $no = 1;
        if (count($data_img) > 0) {
            for ($i = 0; $i < count($data_img); $i++) {

                $is_kover = ($i == 0) ? '(Cover)' : '';
                $pnl_id = ($i != 0) ? 'id="md_div_img_' . $no . '"' : '';

                $panel_img = $panel_img . '<div class="col-md-3" ' . $pnl_id . '>
                    <div style="background:#f6f6f6;min-height:211px;margin-bottom:15px;" id="div_img_' . $no . '">
                        <span class="btn btn-xs btn-block btn-raised btn-default btn-file">
                            <span class="fileinput-new"><i class="icon-upload"></i> Pilih gambar ' . $is_kover . '</span>
                            <input type="file" data-no="' . $no . '" id="file_img_' . $no . '" name="userfile[]" accept="image/*">
                            
                        </span>

                        
                        <div id="div_img_preview_' . $no . '">
                            <input name="file_exist[]" class="hide" id="file_exist_' . $no . '" value="' . $data_img[$i]['photo'] . '" />
                            <img data-src="' . base_url() . $this->file_path . $data_img[$i]['photo'] . '" id="img_preview_' . $no . '" class="demo img_preview_default" width="100%" height="150">
                            <button class="btn btn-xs btn-block btn-danger clear-image" data-no="' . $no . '">Hapus</button>
                        </div>

                    </div>
                </div>';

                $no = $no + 1;
            }
        } else {
            $panel_img = '<div class="col-md-3">
                <div style="background:#f6f6f6;min-height:211px;margin-bottom:15px;" id="div_img_1">
                    <span class="btn btn-xs btn-block btn-raised btn-default btn-file">
                        <span class="fileinput-new"><i class="icon-upload"></i> Pilih gambar (Cover)</span>
                        <input type="file" data-no="1" id="file_img_1" name="userfile[]" accept="image/*"> 
                    </span>
                </div>
            </div>';
        }

        $html = '
        <div class="row" style="margin-bottom:10px;">
            <div class="col-md-12">
                <button id="btnAddImg" type="button" class="btn btn-sm btn-success">Tambah Gambar</button> | 
                <button id="btnRemoveImg" type="button" class="btn btn-sm btn-danger">Hapus Gambar</button>
                <input value="' . $no . '" class="hide" id="total_img" />
            </div>
        </div>

        <div class="row" id="panel_img">
            ' . $panel_img . '
        </div>';
        return $html;
    }

    public function _html_detail_atribut($data)
    {

        $pnl_form = '';
        if ($data) {
            $data_attr = $this->db->where('a.benda_id', $data->benda_id)
                ->join('attribute_group_details AS b', 'b.attribute_group_detail_id = a.attribute_group_detail_id', 'left')
                ->join('attributes AS c', 'b.attribute_id = c.attribute_id', 'left')
                ->join('satuan AS d', 'c.satuan_id = d.satuan_id', 'left')
                ->select('a.*, c.name AS attribute_name, c.input_type, d.name AS satuan_name')
                ->get('benda_attributes AS a')->result_array();

            if (count($data_attr) > 0) {
                for ($i = 0; $i < count($data_attr); $i++) {
                    $d = $data_attr[$i];
                    $pnl_form = $pnl_form . '<div class="col-md-4" style="margin-bottom: 15px;">
                    <input class="hide" value="' . $d['attribute_group_detail_id'] . '" name="attribute_group_detail_id[]">
                    <div class="input-group">
                        <span class="input-group-addon input-group-addon-left">' . $d['attribute_name'] . '</span>
                        <input type="' . $d['input_type'] . '" name="attribute_group_detail_value[]" value="' . $d['attribute_group_detail_value'] . '" class="form-control">
                        <span class="input-group-addon">' . $d['satuan_name'] . '</span>
                        </div></div>';
                }
            }
        }

        $html = '<div class="row" id="show_atribut">' . $pnl_form . '</div>';
        return $html;
    }

    public function getdetailatribut()
    {
        $attribute_group_id = $_GET['attribute_group_id'];

        $data = $this->db
            ->where('a.attribute_group_id', $attribute_group_id)
            ->join('attributes AS b', 'b.attribute_id = a.attribute_id', 'left')
            ->join('satuan AS c', 'c.satuan_id = b.satuan_id', 'left')
            ->select('a.*, b.name AS attribute_name, b.input_type, c.name AS satuan_name')
            ->get('attribute_group_details AS a')->result_array();

        return print_r(json_encode($data));
    }
}
