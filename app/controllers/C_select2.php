<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_select2 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $term = $_GET['term'];

        $table = $_GET['table'];
        $id = $_GET['id'];
        $text = $_GET['text'];

        $data = $this->getSelect2data($table, $id, $text, $term);

        $result = array(
            'results' => $data
        );
        return print_r(json_encode($result));
    }

    public function select2withfilterMuseum()
    {
        $term = $_GET['term'];

        $table = $_GET['table'];
        $id = $_GET['id'];
        $text = $_GET['text'];

        $museum_id = $_GET['museum_id'];

        $data = $this->getSelect2withfilterMuseum($table, $id, $text, $term, $museum_id);

        $result = array(
            'results' => $data
        );
        return print_r(json_encode($result));
    }

    public function getSelect2withfilterMuseum($table, $id, $text, $term, $museum_id)
    {
        $select_contcat = $this->select_text($text);

        $filter = explode(',', $text);

        $this->db
            ->join('museums AS a1', 'a1.museum_id = a.museum_id', 'left');

        $this->db->where('a.active', 1);
        $this->db->where('a1.museum_id', $museum_id);
        $museums = $this->session->userdata('user')->museums;
        if (count($museums) > 0) {
            $list_museum = array();
            for ($g = 0; $g < count($museums); $g++) {
                array_push($list_museum, $museums[$g]->museum_id);
            }
            $this->db->where_in('a1.museum_id', $list_museum);
        }

        if (count($filter) > 0) {
            $this->db->group_start();
            for ($i = 0; $i < count($filter); $i++) {
                $this->db->or_like('IFNULL(a.' . $filter[$i] . ',"")', $term);
            }
            $this->db->group_end();
        }

        $data = $this->db->select('a.' . $id . ' AS id, ' . $select_contcat)
            ->limit(15)
            ->get($table . ' AS a')->result_array();

        return $data;
    }

    public function getSelect2data($table, $id, $text, $term)
    {

        $select_contcat = $this->select_text($text);

        $filter = explode(',', $text);

        $this->db->where('a.active', 1);
        if (count($filter) > 0) {
            $this->db->group_start();
            for ($i = 0; $i < count($filter); $i++) {
                $this->db->or_like('IFNULL(a.' . $filter[$i] . ',"")', $term);
            }
            $this->db->group_end();
        }

        $data = $this->db->select('a.' . $id . ' AS id, ' . $select_contcat)
            ->limit(15)
            ->get($table . ' AS a')->result_array();

        return $data;
    }

    public function initialselect()
    {
        $table = $_GET['table'];
        $id = $_GET['id'];
        $text = $_GET['text'];
        $selected = $_GET['selected'];

        $select_contcat = $this->select_text($text);
        $data = $this->db->select('a.' . $id . ' AS id, ' . $select_contcat)->get_where($table . ' AS a', array(
            $id => $selected
        ))->result_array();

        return print_r(json_encode($data));
    }

    private function select_text($text)
    {
        $list_ext = explode(',', $text);

        $select_contcat = '';
        if (count($list_ext) > 1) {
            $data_concat = '';
            for ($i = 0; $i < count($list_ext); $i++) {

                $spa = ($i != count($list_ext) - 1) ? '," - ",' : '';
                $text = $list_ext[$i];
                $data_concat = $data_concat . 'a.' . $text . $spa;
            }
            $select_contcat =  'CONCAT(' . $data_concat . ') AS text';
        } else {
            $select_contcat =  'a.' . $text . ' AS text';
        }

        return $select_contcat;
    }
}
