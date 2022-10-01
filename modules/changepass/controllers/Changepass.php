<?php

defined('BASEPATH') or exit('No direct script access allowed!');

class Changepass extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['module_url'] = site_url('changepass/');
        $this->data['menu'] = 'changepass';
        $this->table = 'aauth_users';
    }

   public function index(){
        $data = [];
        $this->load->library('form_builder');
        $form = [
             array(
                'id' => 'currentpass',
                'value' => '',
                'label' => 'Current Password',
                'type' => 'password',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'pass',
                'value' => '',
                'label' => 'Password',
                'type' => 'password',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'pass_confirm',
                'value' => '',
                'label' => 'Confirm Password',
                'type' => 'password',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
        ];

        $this->data['form'] = [
            'action' => $this->data['module_url'] . 'save',
            'build' => $this->form_builder->build_form_horizontal($form),
            'class' => 'ajax-token',
        ];

        $this->data['data'] = $data;
        $this->data['headingOverwrite'] = 'Change Password';

        $this->template->_init();
        $this->template->form();
        //$this->output->set_title('Change Password');
        $this->load->view('default/form', $this->data);
    }

    public function save(){
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $this->load->library('form_validation');

        $dataPost = $this->m_master->getInputToken();

        $this->form_validation->set_data($dataPost);
        
        $this->form_validation->set_rules('currentpass', 'Current Password', 'required|min_length[6]');
        $this->form_validation->set_rules('pass', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('pass_confirm', 'Confirm Password', 'required|min_length[6]');

        $id = $this->session->userdata('id');
        
        $this->config->load('aauth');
        $this->config_vars = $this->config->item('aauth');
        $password = ($this->config_vars['use_password_hash'] ? $dataPost['currentpass'] : $this->hash_password($dataPost['currentpass'], $id));
        $d = $this->db->where('id',$id)->get('aauth_users')->row();
        $verify =  $this->aauth->verify_password($password, $d->pass);

        if ($verify) {
            if ($dataPost['pass'] == $dataPost['pass_confirm']  ) {
                $newPassword = $this->aauth->hash_password($dataPost['pass'],$id);
                $this->db->where('id',$id);
                $this->db->update('aauth_users',['pass' => $newPassword]);

                $return = array('message' => sprintf(lang('save_success'), 'Change Pass'), 'status' => 'success', 'redirect' => $this->data['module_url'],'showDirectMsg' => 1);
            }
            else
            {
                $return = array('message' => 'Password & Confirm Password is not match', 'status' => 'error');
            }
        }
        else
        {
            $return = array('message' => 'Wrong current password', 'status' => 'error');
        }

        echo json_encode($return);

    }

}
