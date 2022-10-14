<?php

namespace Modules\finance\midlleware\coa;

class CoaGroupMiddleware
{
    protected $CI;
    protected $table;
    protected $table_id_key;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->table = 'fin_coa_group';
        $this->table_id_key = 'fin_coa_group_id';
    }

    public function form($token = null)
    {
        $data = array();
        $id = null;
        if ($token) {
            $dataToken = get_jwt_decryption($token);
            $id = $dataToken->id;
            $this->CI->aauth->control($this->CI->perm . '/edit');
            $data = $this->CI->m_master->get($this->table, array($this->table_id_key => $id));
            if (!$data) {
                show_404();
                exit();
            }
        } else {
            $this->CI->aauth->control($this->CI->perm . '/add');
        }

        return ['data' => $data, 'id' => $id];
    }

    public function validation($dataPost)
    {

        $return = array('message' => '', 'status' => 'success');


        $form_validation_arr = array(
            array(
                'field' => 'fin_coa_group_code',
                'label' => (empty($dataPost[0]['value'])) ? 'Prefix Code' : 'Code',
                'rules' => 'required',
            ),
            array(
                'field' => 'fin_coa_group_name',
                'label' => 'fin_coa_group_name',
                'rules' => 'required',
            ),
            array(
                'field' => 'fin_coa_aktiva_passiva_sub_id',
                'label' => 'Group',
                'rules' => 'required',
            ),
        );
        $this->CI->load->library('form_validation');
        $this->CI->form_validation->set_data($dataPost);
        $this->CI->form_validation->set_rules($form_validation_arr);

        if ($this->CI->form_validation->run() !== true) {
            $return = array('message' => validation_errors(), 'status' => 'error');
        }

        return $return;
    }
}
