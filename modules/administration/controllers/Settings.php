<?php

defined('BASEPATH') or exit('No direct script access allowed!');

class Settings extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->perm = 'administration/settings';
		$this->aauth->control($this->perm);
		$this->lang->load('settings', settings('language'));
		$this->data['menu'] = 'administration/settings';
		$this->data['module_url'] = site_url('administration/settings/');
	}

	private function _html_textareaSummerNote($key, $value)
	{
		$html = ' <textarea class="form-control area-summernote" id="' . $key . '" name = "' . $key . '">' . $value . '</textarea>
		          <input class="hide" id="formSummernoteID_' . $key . '" />';

		return $html;
	}

	public function index()
	{
		$this->load->library('form_builder');
		$data = [];
		$form = [];
		$formCompany = [];
		$tblSettingAPP =  $this->db->where('type', 1)->get('settings')->result();
		$tblSettingCompany =  $this->db->where('type', 2)->get('settings')->result();

		foreach ($tblSettingAPP as $key => $value) {

			if ($value->key == 'logo_url') {
				$arr = [
					'id' => $value->key,
					'type' => 'image',
					'label' => $value->key,
					'value' => $value->value,
				];
			} else {
				$arr = [
					'id' => $value->key,
					'value' => $value->value,
					'label' => $value->key,
					'form_control_class' => 'col-md-4',
				];
			}

			$form[] = $arr;
		}

		foreach ($tblSettingCompany as $key => $value) {

			if ($value->key == 'company_brief_information') {
				$arr = [
					'id' => $value->key,
					'type' => 'html',
					'label' => $value->key,
					'html' => $this->_html_textareaSummerNote($value->key, $value->value)
				];
			} else {
				$arr = [
					'id' => $value->key,
					'value' => $value->value,
					'label' => $value->key,
					'form_control_class' => 'col-md-4',
				];
			}



			$formCompany[] = $arr;
		}

		$form_build[] = [
			'title' => lang('App'),
			'build' => $this->form_builder->build_form_horizontal($form)
		];

		$form_build[] = [
			'title' => lang('Company'),
			'build' => $this->form_builder->build_form_horizontal($formCompany)
		];

		$this->data['form'] = [
			'action' => $this->data['module_url'] . 'save',
			'build' => $form_build,
			'class' => '',
		];

		$this->data['data'] = $data;
		$this->data['headingOverwrite'] = lang('heading');

		$this->template->_init();
		$this->template->form();
		$this->load->view('default/form_tab', $this->data);
	}

	private function delete_logo()
	{
		// get filename && delete
		if (settings('logo_url') && !empty(settings('logo_url'))) {
			$uri_path = parse_url(settings('logo_url'), PHP_URL_PATH);
			$uri_segments = explode('/', $uri_path);
			$filename = $uri_segments[count($uri_segments) - 1];
			$path = './assets/images/' . $filename;
			if (file_exists($path)) {
				unlink($path);
			}
		}
	}

	private function reload_session()
	{
		$settings = $this->db->get('settings');
		foreach ($settings->result() as $setting) {
			$data[$setting->key] = $setting->value;
		}

		$this->session->set_userdata('getsSettings', $data);
	}

	public function save()
	{
		$this->input->is_ajax_request() or exit('No direct post submit allowed!');
		$data = $this->input->post(null, true);
		if (isset($_FILES['logo_url']['name'])) {
			$this->delete_logo();

			// upload
			$file_ext = strtolower(pathinfo($_FILES['logo_url']['name'], PATHINFO_EXTENSION));
			$new_name = uniqid() . '.' . $file_ext;
			$config = [];
			$config['file_name'] = $new_name;
			$config['upload_path']          = './assets/images/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';


			$do_upload = $this->m_master->upload_file($config, 'logo_url');
			if ($do_upload['status'] != 1) {
				$return = array('message' => $do_upload['msg'], 'status' => 'error');
				echo json_encode($return);
				die();
			}
			$data['logo_url'] = base_url() . '/assets/images/' . $new_name;

			if (isset($data['delete_logo_url']) && $data['delete_logo_url'] == '1') {
				$this->delete_logo();
			}
		} else {
			unset($data['logo_url']);
		}

		unset($data['upload_logo_url']);
		unset($data['delete_logo_url']);

		//$this->m_master->truncate('settings');

		foreach ($data as $key => $value) {
			$this->db->where('key', $key);
			$this->db->update('settings', ['key' => $key, 'value' => $value]);
		}

		$return = array('message' => sprintf(lang('save_success'), lang('heading')), 'status' => 'success', 'redirect' => $this->data['module_url']);

		if (isset($return['redirect'])) {
			$this->session->set_flashdata('form_response_status', $return['status']);
			$this->session->set_flashdata('form_response_message', $return['message']);
		}

		$this->reload_session();

		echo json_encode($return);
	}
}
