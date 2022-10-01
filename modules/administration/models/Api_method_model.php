<?php 

/**
 * 
 */
class Api_method_model extends CI_Model
{
	
	function __construct() {
	    $this->columns = isset($this->data['table']) ? $this->data['table']['columns'] : [];
	}

	function get_all($start = 0, $length = 10, $filter = array(), $order = array()) {
	    $this->filter($filter);
	    if ($order) {
	        $order['column'] = $this->columns[$order['column']]['name'];
	        $this->db->order_by($order['column'], $order['dir']);
	    }
	    // $this->db->select('a.*,b.username')
	    //         ->join('aauth_users b', 'a.id_aauth_users = b.id', 'join')
	    //         ;

	    return $this->db->limit($length, $start)->get($this->table.' a');
	}

	function count_all($filter = array()) {
	    $this->filter($filter);
	    // $this->db->join('aauth_users b', 'a.id_aauth_users = b.id', 'join');
	    return $this->db->count_all_results($this->table.' a'); 
	}

	function filter($filter = array()) {
	    if ($filter) {
	        $this->db->group_start();
	        foreach ($filter as $column => $value) {
	            $this->db->like('IFNULL(' . $this->columns[$column]['name'] . ',"")', $value);
	        }
	        $this->db->group_end();
	    }
	}

}

