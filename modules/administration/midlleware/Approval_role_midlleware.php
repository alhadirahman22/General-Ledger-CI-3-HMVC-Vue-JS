<?php

namespace Modules\administration\midlleware;

class Approval_role_midlleware
{
    protected $CI;
    protected $table;
    protected $table_id_key;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->table = 'approval_rule';
        $this->table_id_key = 'approval_rule' . '_id';
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

    public function validation()
    {
        $return = array('message' => '', 'status' => 'success');
        $form_validation_arr = array(
            array(
                'field' => 'name',
                'label' => 'Nama',
                'rules' => 'required'
            ),
        );
        $this->CI->form_validation->set_rules($form_validation_arr);

        if ($this->CI->form_validation->run() !== true) {
            $return = array('message' => validation_errors(), 'status' => 'error');
        }

        return $return;
    }
}
