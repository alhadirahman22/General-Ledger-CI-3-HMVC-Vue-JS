<?php

namespace Modules\finance\midlleware;



class GLMidlleware
{

    protected $CI;
    protected $table;
    protected $table_id_key;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->table = 'fin_gl';
        $this->table_id_key = 'fin_gl_id';
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
}
