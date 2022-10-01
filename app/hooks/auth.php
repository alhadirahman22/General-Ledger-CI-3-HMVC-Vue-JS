<?php

function get_user()
{
    $CI = &get_instance();
    if ($CI->uri->segment(1) != 'ajax') {
        if ($CI->aauth->is_loggedin()) {
            if (!$CI->session->has_userdata('user')) {
                $user = $CI->aauth->get_user();
                // get data employees
                $user->data_employee = $CI->db->where('employee_id', $user->employee_id)->get('employees')->row();
                $employee_id = $user->employee_id;


                $CI->session->set_userdata('user', $user);
            }
            $CI->data['user'] = $CI->session->userdata('user');

            // save all log in database
            $CI->m_master->saveLog();


            // set environment by database
            $CI->m_master->set_environtment();
        } else {
            if (!in_array($CI->uri->segment(1), ['auth', 'crons', 'file_upload', 'migrate', 'migrationdata'])) {
                redirect('auth/login?back=' . uri_string());
            }
        }
    }
}
