<?php

namespace Modules\reimbursment\midlleware;

use Modules\reimbursment\models\Reimbursment_model_eloquent;

class ReimbursmentMiddleware
{
    protected $CI;
    protected $table;
    protected $table_id_key;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->table = 'reimbursment';
        $this->table_id_key = 'reimbursment_id';
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

    public function validationPay($dataPost)
    {
        $return = array('message' => '', 'status' => 'success');
        $this->CI->load->library('form_validation');

        $form_validation_arr = array(
            array(
                'field' => 'fin_coa_id',
                'label' => 'Coa',
                'rules' => 'required'
            ),
            array(
                'field' => 'reimbursment_id',
                'label' => 'reimbursment_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'value',
                'label' => 'Prices',
                'rules' => 'required'
            ),
            array(
                'field' => 'date_trans',
                'label' => 'Transaction Pay',
                'rules' => 'required'
            ),

        );


        $this->CI->form_validation->set_data($dataPost);
        $this->CI->form_validation->set_rules($form_validation_arr);

        if ($this->CI->form_validation->run() !== true) {
            $return = array('message' => validation_errors(), 'status' => 'error');
        }

        return $return;
    }

    public function validation($dataPost)
    {
        $return = array('message' => '', 'status' => 'success');
        $this->CI->load->library('form_validation');

        $form_validation_arr = array(
            array(
                'field' => 'name',
                'label' => 'Nama',
                'rules' => 'required'
            ),
            array(
                'field' => 'date_reimbursment',
                'label' => 'Transaction Date',
                'rules' => 'required'
            ),
            array(
                'field' => 'desc',
                'label' => 'Desc',
                'rules' => 'required'
            ),
            array(
                'field' => 'requested_by',
                'label' => 'Requested By',
                'rules' => 'required'
            ),
            array(
                'field' => 'value',
                'label' => 'Price',
                'rules' => 'required'
            ),
        );


        $this->CI->form_validation->set_data($dataPost);
        $this->CI->form_validation->set_rules($form_validation_arr);

        if ($this->CI->form_validation->run() !== true) {
            $return = array('message' => validation_errors(), 'status' => 'error');
        }

        return $return;
    }

    public function delete($reimbursment_id)
    {
        $data = Reimbursment_model_eloquent::find($reimbursment_id);
        $user_id =  $this->CI->data['user']->id;
        if ($user_id == $data->created_by || $this->CI->aauth->is_admin()) {
            return true;
        } else {
            return false;
        }
    }

    public function authPay()
    {
        return $this->CI->aauth->is_allowed($this->CI->perm . '/pay');
    }
}
