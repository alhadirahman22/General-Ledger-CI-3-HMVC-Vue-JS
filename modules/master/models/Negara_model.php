<?php

class Negara_model extends CI_Model
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

        // $this->db->select('a.*, b.purchase_order_number , b.status AS status_approval, b1.supplier_name, 
        // c.purchase_request_number, d.name AS item_name, e.description AS unit_description');

        return $this->db->limit($length, $start)->get($this->table . ' AS a');
    }

    function count_all($filter = array())
    {
        $this->filter($filter);
        return $this->db->count_all_results($this->table . ' AS a');
    }

    function filter($filter = array())
    {
        // $this->db
        //     ->join('purchase_order AS b', 'b.purchase_order_id = a.purchase_order_id', 'left')
        //     ->join('suppliers AS b1', 'b1.supplier_id = b.supplier_id', 'left')
        //     ->join('purchase_request AS c', 'c.purchase_request_id = a.purchase_request_id', 'left')
        //     ->join('item AS d', 'd.item_id = a.item_id', 'left')
        //     ->join('unit AS e', 'e.unit_id = d.unit_id', 'left');

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
