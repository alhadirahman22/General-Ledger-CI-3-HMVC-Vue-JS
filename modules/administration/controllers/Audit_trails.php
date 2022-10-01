<?php


defined('BASEPATH') or exit('No direct script access allowed!');

class Audit_trails extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->perm = 'administration/audit_trails';
		$this->aauth->control($this->perm);

		$this->lang->load('audit_trails', settings('language'));
		$this->data['menu'] = 'administration/audit_trails';

		$this->data['module_url'] = site_url('administration/audit_trails/');
		$this->data['table'] = [
			'columns' => [
				'0' => ['name' => 'b.username', 'title' => lang('username'), 'filter' => ['type' => 'text']],
				'1' => ['name' => 'a.uri', 'title' => lang('uri'), 'filter' => ['type' => 'text']],
				'2' => ['name' => 'a.request', 'title' => lang('request'), 'filter' => false, 'class' => 'no-sort text-center'],
				'3' => ['name' => 'a.header', 'title' => lang('header'), 'filter' => false,  'class' => 'no-sort text-center'],
				'4' => ['name' => 'a.accessOn', 'title' => lang('accessOn'), 'class' => 'default-sort', 'sort' => 'desc', 'filter' => ['type' => 'text']],
				'5' => ['name' => 'a.ip1', 'title' => lang('ip'), 'filter' => ['type' => 'text']],
			],
			'url' => $this->data['module_url'] . 'get_list'
		];

		$this->data['filter_name'] = 'table_filter_setting_audit_trails';
		$this->table = 'log';
		$this->load->model('audit_trails_model');
	}

	public function index()
	{
		$this->template->_init();
		$this->template->table();
		$this->load->js('assets/js/modules/administration/audit_trails/index.js');
		$this->output->set_title(lang('heading'));

		$this->data['lastMonth'] = date("Y-m-d", strtotime("first day of previous month"));
		$this->data['MonthAgo3'] = date("Y-m-d", strtotime("-3 Months"));

		$this->load->view('audit_trails', $this->data);
	}

	public function get_list()
	{
		$this->input->is_ajax_request() or exit('No direct post submit allowed!');
		$start = $this->input->post('start');
		$length = $this->input->post('length');
		$order = $this->input->post('order')[0];
		$draw = intval($this->input->post('draw'));
		$filter = $this->input->post('filter');
		$this->session->set_userdata($this->data['filter_name'], $filter);
		$output['data'] = array();
		$datas = $this->audit_trails_model->get_all($start, $length, $filter, $order);
		if ($datas) {
			foreach ($datas->result() as $data) {

				$request_Data = print_r(json_decode($data->request, true), true);
				$header = print_r(json_decode($data->header, true), true);

				$output['data'][] = array(
					$data->username,
					$data->uri,
					'<button class = "btn btn-sm btn-warning btnRequestDetail" data-token = "' . $this->jwt->encode('<pre>' . ($request_Data) . '</pre>', jwtKey) . '" data-user = "' . $data->username . '">Detail Request</button>',
					'<button class = "btn btn-sm btn-primary btnHeaderDetail" data-token = "' . $this->jwt->encode('<pre>' . ($header) . '</pre>', jwtKey) . '" data-user = "' . $data->username . '">Detail Header</button>',
					$data->accessOn,
					$data->ip1,

				);
			}
		}
		$output['draw'] = $draw++;
		$output['recordsTotal'] = $this->audit_trails_model->count_all();
		$output['recordsFiltered'] = $this->audit_trails_model->count_all($filter);
		echo json_encode($output);
	}

	public function clearLog()
	{
		$this->input->is_ajax_request() or exit('No direct post submit allowed!');
		$token = $this->input->post('token');
		$dataAll = $this->m_master->decode_token($token);
		$first_date = $dataAll[0];
		$second_date = $dataAll[1] . ' 23:59:00';
		$this->db->where('accessOn >=', $first_date);
		$this->db->where('accessOn <=', $second_date);
		$x  = $this->db->count_all_results($this->table);
		if ($x > 0) {
			$this->db->where('accessOn >=', $first_date);
			$this->db->where('accessOn <=', $second_date);
			$this->db->delete($this->table);
			$return = ['message' => sprintf(lang('delete_success'), lang('heading')), 'status' => 'success', 'redirect' => $this->data['module_url']];
		} else {
			$return = ['message' => 'No data record', 'status' => 'error'];
		}

		$this->session->set_flashdata('form_response_status', $return['status']);
		$this->session->set_flashdata('form_response_message', $return['message']);

		echo json_encode($return);
	}
}
