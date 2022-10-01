<?php

class Data_benda_model extends CI_Model
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

        $this->db->select('a.*, b.name AS kepemilikan_name, c.name AS jenis_perolehan_name,
        d.name AS fungsi_name, e.name AS kategori_name, f.name AS bahan_utama_name, g.photo, h.status, i.name AS jenis_mutasi_name');

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
            ->join('kepemilikan AS b', 'b.kepemilikan_id = a.kepemilikan_id', 'left')
            ->join('jenis_perolehan AS c', 'c.jenis_perolehan_id = a.jenis_perolehan_id', 'left')
            ->join('fungsi AS d', 'd.fungsi_id = a.fungsi_id', 'left')
            ->join('kategori AS e', 'e.kategori_id = a.kategori_id', 'left')
            ->join('bahan AS f', 'f.bahan_id = a.bahan_utama', 'left')
            ->join('photos AS g', 'g.benda_id = a.benda_id AND g.is_cover = 1', 'left')
            ->join('mutasi_benda AS h', 'h.benda_id = a.benda_id', 'left')
            ->join('jenis_mutasi AS i', 'h.jenis_mutasi_id = i.jenis_mutasi_id', 'left');

        $museums = $this->session->userdata('user')->museums;
        if (count($museums) > 0) {
            $list_museum = array();
            for ($g = 0; $g < count($museums); $g++) {
                array_push($list_museum, $museums[$g]->museum_id);
            }
            $this->db->where_in('a.museum_id', $list_museum);
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
