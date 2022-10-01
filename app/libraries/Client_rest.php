<?php

defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;

// print_r(FCPATH."vendor/autoload.php");
// die();

class client_rest extends CI_Controller
{

    private $_client;

    function __construct()
    {
    }

    private function setHeader()

    {

        $this->_client = new GuzzleHttp\Client([
            'base_uri' => '', // URL_REST,
            'headers' => [
                'X-API-KEY' => $CI->session->userdata('token'),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'http_errors' => false

        ]);
    }

    private function setHeader_shipper()

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

    public function client_upload()
    {
        // $this->_client->request('POST', 'training/uploadMedia', [
        //     'multipart' => [
        //         [
        //             'name'     => 'foo',
        //             'contents' => 'data',
        //             'headers'  => ['X-API-KEY' => 'Y8kXhIyNCQ5SZlzeg2tu3LbdUpP1Rsxn6KoWwHFA']
        //         ],
        //         [
        //             'name'     => 'baz',
        //             'contents' => Psr7\Utils::tryFopen('/path/to/file', 'r')
        //         ],
        //         [
        //             'name'     => 'qux',
        //             'contents' => Psr7\Utils::tryFopen('/path/to/file', 'r'),
        //             'filename' => 'custom_filename.txt'
        //         ],
        //     ]
        // ]);
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
        $this->setHeader_shipper();
        $response = $this->_client->request('GET', $endpoint, ['query' => $query]);

        if ($response->getStatusCode() != 200) {
            // $this->error_handling($response);
            print_r($response->getBody()->getCOntents());
        } else {
            $d = json_decode($response->getBody()->getCOntents(), true);
            return $d;
        }
    }

    public function client_put($endpoint, $form_params)
    {
        $this->setHeader_shipper();
        $response = $this->_client->request('PUT', $endpoint, [
            'body'=> $form_params
        ]);
        if ($response->getStatusCode() != 200) {
            $this->error_handling($response);
        } else {
            return $response;
        }
    }

    public function client_post($endpoint, $form_params)
    {
        $this->setHeader_shipper();
        $response = $this->_client->request('POST', $endpoint, $form_params);
        if ($response->getStatusCode() != 200) {
            $this->error_handling($response);
        } else {
            return $response;
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
