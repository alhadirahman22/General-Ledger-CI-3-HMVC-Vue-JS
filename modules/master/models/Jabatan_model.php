<?php

class Jabatan_model extends CI_Model
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

        $this->db->select('a.*, b.name as NameDepartment , c.name as NameCompany,d.value');
        $this->db->join('departments as b', 'b.department_id = a.department_id');
        $this->db->join('company as c', 'c.company_id = a.company_id');
        $this->db->join('level_jabatan as d', 'd.level_jabatan_id = a.level_jabatan_id');

        return $this->db->limit($length, $start)->get($this->table . ' AS a');
    }

    function count_all($filter = array())
    {
        $this->filter($filter);
        $this->db->join('departments as b', 'b.department_id = a.department_id');
        $this->db->join('company as c', 'c.company_id = a.company_id');
        $this->db->join('level_jabatan as d', 'd.level_jabatan_id = a.level_jabatan_id');
        return $this->db->count_all_results($this->table . ' AS a');
    }

    function filter($filter = array())
    {
        $this->db->where('a.deleted_at is NULL');
        $this->db->where('b.active', 1);
        if ($filter) {
            $this->db->group_start();

            foreach ($filter as $column => $value) {
                $this->db->like('IFNULL(' . $this->columns[$column]['name'] . ',"")', $value);
            }
            $this->db->group_end();
        }
    }
}
