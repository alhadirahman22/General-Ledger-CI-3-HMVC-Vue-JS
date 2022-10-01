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

                // $id = $CI->session->userdata('id');
                $employee_id = $user->employee_id;

                // if ($employee_id != '' && $employee_id != null && $employee_id != 'null') {
                // }

                $user->museums = $CI->db
                    ->where('a.employee_id', $employee_id)
                    ->join('museums AS b', 'b.museum_id = a.museum_id', 'left')
                    ->select('b.*')
                    ->group_by('a.museum_id')
                    ->get('jabatan_department_employee AS a')->result();

                $user->departments = $CI->db
                    ->where('a.employee_id', $employee_id)
                    ->join('departments AS b', 'b.department_id = a.department_id', 'left')
                    ->select('b.*')
                    ->group_by('a.department_id')
                    ->get('jabatan_department_employee AS a')->result();

                // $user->museums = $CI->db
                //     ->where('b.user_id', $id)
                //     ->join('aauth_user_to_group AS b', 'b.group_id = a.group_id', 'left')
                //     ->join('museums AS c', 'c.museum_id = a.museum_id', 'left')
                //     ->select('a.*, c.name AS museum_name')
                //     ->group_by('a.museum_id')
                //     ->get('aauth_department_to_group AS a')->result();

                // $user->departments = $CI->db
                //     ->where('b.user_id', $id)
                //     ->join('aauth_user_to_group AS b', 'b.group_id = a.group_id', 'left')
                //     ->join('departments AS c', 'c.department_id = a.department_id', 'left')
                //     ->select('a.*, c.name AS department_name')
                //     ->group_by('a.department_id')
                //     ->get('aauth_department_to_group AS a')->result();

                // ->where('b.user_id', $id)
                // ->join('aauth_user_to_group AS b', 'b.group_id = a.aauth_group_id', 'left')
                // ->join('groups AS c', 'a.group_id = c.group_id', 'left')
                // ->select('a.*, c.name')
                // ->get('aauth_hr_group_to_group AS a')->result();

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
