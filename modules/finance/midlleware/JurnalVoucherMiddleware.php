<?php

namespace Modules\finance\midlleware;

use Modules\finance\models\GL_model_eloquent;

class JurnalVoucherMiddleware
{

    protected $CI;
    protected $table;
    protected $table_id_key;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->table = 'fin_jurnal_voucher';
        $this->table_id_key = 'fin_jurnal_voucher_id';
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
        if (!empty($dataPost['fin_coa_id'])) {
            $return = $this->ruleEditDelete($dataPost['fin_jurnal_voucher_id']);
        }

        if ($return['status'] == 'success') {
            $form_validation_arr = array(
                array(
                    'field' => 'fin_jurnal_voucher_code',
                    'label' => (empty($_POST['fin_jurnal_voucher_id'])) ? 'Prefix Code' : 'Code',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'fin_jurnal_voucher_name',
                    'label' => 'Name',
                    'rules' => 'required',
                ),
            );

            $this->CI->load->library('form_validation');
            $this->CI->form_validation->set_data($dataPost);
            $this->CI->form_validation->set_rules($form_validation_arr);

            if ($this->CI->form_validation->run() !== true) {
                $return = array('message' => validation_errors(), 'status' => 'error');
            }
        }

        return $return;
    }

    public function ruleEditDelete($fin_jurnal_voucher_id)
    {
        $return = array('message' => '', 'status' => 'success');
        $dataCount = GL_model_eloquent::where('fin_jurnal_voucher_id', $fin_jurnal_voucher_id)->count();
        if ($dataCount > 0) {
            $return = array('message' => 'You are not allowed this action', 'status' => 'error');
        }

        return $return;
    }
}
