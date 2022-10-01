<?php

defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;

// print_r(FCPATH."vendor/autoload.php");
// die();

class shipper extends CI_Controller
{

    private $_client;

    function __construct()
    {
    }

    private function setHeader()

    {

        $CI = &get_instance();
        // $CI->load->library('session');
        $CI->load->helper('common_helper');

        // $CI->db->get();

        $this->_client = new GuzzleHttp\Client([
            'base_uri' => settings('shipper_base_url'),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-API-Key' => settings('shipper_key'),
            ],
            'http_errors' => false

        ]);
    }



    public function checklogin_get()
    {
        $this->setHeader();
        $response = $this->_client->request('GET', 'training/profile', ['query' => ['user_type' => 'trainer']]);
        if ($response->getStatusCode() != 200) {
            return false;
        } else {
            return true;
        }
    }

    public function client_get($endpoint, $query)
    {

        $this->setHeader();
        $response = $this->_client->request('GET', $endpoint, ['query' => $query]);

        if ($response->getStatusCode() != 200) {
            // $this->error_handling($response);
            print_r($response->getBody()->getCOntents());
        } else {
            return json_decode($response->getBody()->getCOntents(),true);
        }
    }

    public function client_put($endpoint, $form_params)
    {
        $this->setHeader();
        $response = $this->_client->request('PUT', $endpoint, [
            'body' => $form_params
        ]);
        if ($response->getStatusCode() != 200) {
            $this->error_handling($response);
        } else {
            return $response;
        }
    }

    public function client_post($endpoint, $form_params)
    {
        $this->setHeader();
        $response = $this->_client->request('POST', $endpoint, ['body' => json_encode($form_params)]);
        if ($response->getStatusCode() != 200) {
            $this->error_handling($response);
        } else {
            return $response->getBody()->getCOntents();
        }
    }

    private function error_handling($response)
    {
        if (ENVIRONMENT == 'production') {
            $d = json_decode($response->getBody()->getCOntents(), true);
            redirect('rest-error/' . $response->getStatusCode() . '/' . $d['error'], 'refresh');
        } else {
            print_r($response->getBody()->getCOntents());
            die();
        }
    }
}
