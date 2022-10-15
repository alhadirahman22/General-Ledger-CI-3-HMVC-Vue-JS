<?php

namespace Modules\finance\repository\coa;

use Modules\finance\models\Coa_model_eloquent;
use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\finance\models\Coa_group_model_eloquent;


class CoaRepository
{

    protected $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function form($data)
    {
        $form = [
            array(
                'id' => 'fin_coa_id',
                'type' => 'hidden',
                'value' => ($data) ? $data->fin_coa_id : '',
            ),
            array(
                'id' => 'fin_coa_group_id',
                'value' => ($data) ? $data->fin_coa_group_id : '',
                'label' => 'Group',
                'required' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2',
                'options' => $this->CI->m_master->get_dropdown('(select *,concat(fin_coa_group_code," - ",fin_coa_group_name) as codeShow from fin_coa_group)xx', 'fin_coa_group_id', 'codeShow'),
            ),
            array(
                'id' => 'fin_coa_code',
                'value' => ($data) ? $data->fin_coa_code : '',
                'label' => 'Code',
                'form_control_class' => 'col-md-4',
                'placeholder' => 'Automated by system',
                'readonly' => 'true',
            ),
            array(
                'id' => 'fin_coa_name',
                'value' => ($data) ? $data->fin_coa_name : '',
                'label' => 'Name',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'type',
                'value' => ($data) ? $data->type : '',
                'label' => 'Type',
                'required' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2',
                'options' => ['D' => 'Debit', 'C' => 'Credit'],
            ),
            array(
                'id' => 'status',
                'value' => ($data) ? $data->status : '',
                'label' => 'Status',
                'required' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2',
                'options' => ['A' => 'Y', 'T' => 'N'],
            ),
            array(
                'id' => 'desc',
                'type' => 'textarea',
                'value' => ($data) ? $data->desc : '',
                'label' => 'Description',
                'rows' => 2,
                'form_control_class' => 'col-md-5',
            ),
        ];

        if ($data) {
            $form[1]['disabled'] = 'true';
        }

        return $form;
    }

    public function save($dataPost)
    {
        $data = $dataPost;
        if (!$data['fin_coa_id']) {
            // get code
            $set = $this->getCode($data['fin_coa_group_id']);
            $data['fin_coa_code'] = $set['code'];
            $data['fin_coa_code_inc'] = $set['inc'];
            $data['created_at'] =  Date('Y-m-d H:i:s');
            $data['created_by'] =  $this->CI->data['user']->id;
            $this->CI->db->insert($this->CI->table, $data);
        } else {
            $data['updated_at'] =  Date('Y-m-d H:i:s');
            $data['updated_by'] =  $this->CI->data['user']->id;
            $this->CI->db->where('fin_coa_id', $data['fin_coa_id']);
            $this->CI->db->update($this->CI->table, $data);
        }

        $return = array('message' => sprintf(lang('save_success'), lang('heading') . ' ' . $data['fin_coa_name']), 'status' => 'success', 'redirect' => $this->CI->data['module_url']);

        return $return;
    }

    public function getCode($fin_coa_group_id)
    {
        $get = Coa_model_eloquent::where('fin_coa_group_id', $fin_coa_group_id);
        $count = $get->count();
        $get2 = Coa_group_model_eloquent::find($fin_coa_group_id);
        $prefix = $get2->fin_coa_group_code;
        $string = ($count == 0) ? '00' : Coa_model_eloquent::where('fin_coa_group_id', $fin_coa_group_id)->orderByDesc('fin_coa_code_inc')->limit(1)->first()->fin_coa_code_inc; //the last entry from the database

        $strNumber = $string;

        $strNumberNew = str_pad(intval($strNumber) + 1, 2, 0, STR_PAD_LEFT); //increment the number by 1 and pad with 0 in left.
        $new = $prefix . $strNumberNew;

        return ['code' => $new, 'inc' => $strNumberNew];
    }
}
