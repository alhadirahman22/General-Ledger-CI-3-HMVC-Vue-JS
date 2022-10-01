<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_session extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function unsetsession($session_name)
    {
        // print_r($session_name);
        // die();

        $this->session->unset_userdata($session_name);
        $result = array(
            'status' => true
        );
        return print_r(json_encode($result));
    }
}
