<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->lang->load('auth', settings('language'));
    }

    public function login()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->library('form_validation');

            $dataPost = $this->m_master->getInputToken();

            $this->form_validation->set_data($dataPost);

            $this->form_validation->set_rules('identity', 'Username', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == true) {
                if ($this->aauth->login($dataPost['identity'], $dataPost['password'])) {

                    $data_redirect = $this->aauth->redirect_page($dataPost['identity'], $dataPost['password']);

                    if ($dataPost['back'] == '') {
                        $redirect = ($data_redirect['level']) ?
                            ($data_redirect['level']->dashboard != '' &&
                                $data_redirect['level']->dashboard != null &&
                                $data_redirect['level']->dashboard != 'null')
                            ? $data_redirect['level']->dashboard : $dataPost['back']
                            : $dataPost['back'];
                    } else {
                        $redirect = $dataPost['back'];
                    }

                    $return = array('message' => '', 'status' => 'success', 'redirect' => site_url($redirect));
                } else {
                    $return = array('message' => $this->aauth->print_errors(), 'status' => 'error');
                }
            } else {
                $return = array('message' => validation_errors(), 'status' => 'error');
            }
            echo json_encode($return);
        } else {
            $this->output->set_title('Login Page');
            $this->template->_init('login');
            $this->output->set_output_data('username', '');
            $this->output->set_output_data('password', '');
            if (getenv('APP_ENV')  == 'local') {
                $this->output->set_output_data('username', 'admin');
                $this->output->set_output_data('password', 'admin');
            }

            $this->template->form();
        }
    }

    public function logout()
    {
        $this->aauth->logout();
        redirect('auth/login', 'refresh');
    }

    public function hash_pass()
    {
        if (getenv('APP_ENV')  == 'local') {
            echo $this->aauth->hash_password('123123', 1);
        }
    }
}
