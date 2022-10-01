<?php

class Fungsi_model extends CI_Model
{

    function __construct()
    {
        $this->columns = isset($this->data['table']) ? $this->data['table']['columns'] : [];
    }

    function get_all($start = 0, $length = 10, $filter = array(), $order = array())
    {
        $this->filter($filter);
        if ($order) {
            $order['column'] = $this->columns[$order['column']]['name'];
            $this->db->order_by($order['column'], $order['dir']);
        }

        $this->db->select('a.*, a1.name AS museum_name');

        return $this->db->limit($length, $start)->get($this->table . ' AS a');
    }

    function count_all($filter = array())
    {
        $this->filter($filter);
        return $this->db->count_all_results($this->table . ' AS a');
    }

    function filter($filter = array())
    {
        $this->db
            ->join('museums AS a1', 'a1.museum_id = a.museum_id', 'left');

        $museums = $this->session->userdata('user')->museums;
        if (count($museums) > 0) {
            $list_museum = array();
            for ($g = 0; $g < count($museums); $g++) {
                array_push($list_museum, $museums[$g]->museum_id);
            }
            $this->db->where_in('a1.museum_id', $list_museum);
        }

        if ($filter) {
            $this->db->group_start();
            $this->db->where('a.active', 1);
            foreach ($filter as $column => $value) {
                $this->db->like('IFNULL(' . $this->columns[$column]['name'] . ',"")', $value);
            }
            $this->db->group_end();
        }
    }
}
