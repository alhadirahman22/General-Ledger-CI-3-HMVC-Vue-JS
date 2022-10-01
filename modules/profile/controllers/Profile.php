<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Profile extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->data['menu'] = 'profile';
		$this->data['module_url'] = base_url().'profile/';
		$this->lang->load('employees', settings('language'),FALSE,TRUE,'','hrm');
		$this->table = 'employees';
		
	}

	public function index(){
		$employee_id = $this->data['user']->employee_id;
		$this->template->_init();
		$this->output->set_title('Profile');

		if (empty($employee_id)) {
			$this->load->view('no_profile', $this->data);
		}
		else
		{
			$data = array();
			$data = $this->m_master->get($this->table, array('employee_id' => $employee_id));
			if (!$data) {
			    show_404();
			    exit();
			}

			$this->load->library('form_builder');

			$formBiodata= [
				array(
					'id' => 'employee_id',
					'type' => 'hidden',
					'value' => ($data) ? $data->employee_id : '',
				),
				array(
				    'id' => 'nama',
				    'value' => ($data) ? $data->nama : '',
				    'label' => lang('nama'),
				    'required' => 'true',
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'code',
				    'value' => ($data) ? $data->code : '',
				    'label' => lang('code'),
				    'required' => 'true',
				    'readonly' => true,
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'nik',
				    'value' => ($data) ? $data->nik : '',
				    'label' => lang('nik'),
				    'required' => 'true',
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'gender',
				    'value' => ($data) ? $data->gender : '',
				    'label' => lang('gender'),
				    'required' => 'true',
				    'type' => 'dropdown',
				    'form_control_class' => 'col-md-4',
				    'class' => 'select2-nonserverside',
				    'options' => ['Male' => lang('male'),'Female' => lang('female')],
				),
				array(
				    'id' => 'religion_id',
				    'value' => ($data) ? $data->religion_id : '',
				    'label' => lang('name_religion'),
				    'required' => 'true',
				    'type' => 'dropdown',
				    'form_control_class' => 'col-md-4',
				    'class' => 'select2-nonserverside',
				    'options' => $this->m_master->get_dropdown('religion','religion_id','name',false),
				),
				array(
				    'id' => 'date_birth',
				    //'type' => 'text',
				    'value' => ($data) ? $data->date_birth : '',
				    'label' => lang('date_birth'),
				    'required' => 'true',
				    'class' => 'date',
				    //'readonly' => true,
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'blood',
				    'value' => ($data) ? $data->blood : '',
				    'label' => lang('blood'),
				    //'required' => 'true',
				    'form_control_class' => 'col-md-4',
				),
				array(
					'id' => 'address',
					'type' => 'textarea',
					'value' => ($data) ? $data->address : '',
					'label' => lang('address'),
					'rows' => 2,
					'required' => 'true',
					'form_control_class' => 'col-md-5',
				),
				array(
				    'id' => 'rtrw',
				    'value' => ($data) ? $data->rtrw : '',
				    'label' => lang('rtrw'),
				    //'required' => 'true',
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'village',
				    'value' => ($data) ? $data->village : '',
				    'label' => lang('village'),
				    //'required' => 'true',
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'nationality',
				    'value' => ($data) ? $data->nationality : 'Indonesia',
				    'label' => lang('nationality'),
				    //'required' => 'true',
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'phone',
				    'value' => ($data) ? $data->phone : '',
				    'label' => lang('phone'),
				    'required' => 'true',
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'jamsostek',
				    'value' => ($data) ? $data->jamsostek : '',
				    'label' => lang('jamsostek'),
				    //'required' => 'true',
				    'form_control_class' => 'col-md-4',
				)
			];

			$formFamily = [
				array(
				    'id' => 'partner_name',
				    'value' => ($data) ? $data->partner_name : '',
				    'label' => lang('partner_name'),
				    //'required' => 'true',
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'partner_date_birth',
				    //'type' => 'text',
				    'value' => ($data) ? $data->partner_date_birth : '',
				    'label' => lang('partner_date_birth'),
				    //'required' => 'true',
				    'class' => 'date',
				    //'readonly' => true,
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'child_name1',
				    'value' => ($data) ? $data->child_name1 : '',
				    'label' => lang('child_name1'),
				    //'required' => 'true',
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'child1_date_birth',
				    //'type' => 'text',
				    'value' => ($data) ? $data->child1_date_birth : '',
				    'label' => lang('child1_date_birth'),
				    //'required' => 'true',
				    'class' => 'date',
				    //'readonly' => true,
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'child_name2',
				    'value' => ($data) ? $data->child_name2 : '',
				    'label' => lang('child_name2'),
				    //'required' => 'true',
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'child2_date_birth',
				    //'type' => 'text',
				    'value' => ($data) ? $data->child2_date_birth : '',
				    'label' => lang('child2_date_birth'),
				    //'required' => 'true',
				    'class' => 'date',
				    //'readonly' => true,
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'child_name3',
				    'value' => ($data) ? $data->child_name3 : '',
				    'label' => lang('child_name3'),
				    //'required' => 'true',
				    'form_control_class' => 'col-md-4',
				),
				array(
				    'id' => 'child3_date_birth',
				    //'type' => 'text',
				    'value' => ($data) ? $data->child3_date_birth : '',
				    'label' => lang('child3_date_birth'),
				    //'required' => 'true',
				    'class' => 'date',
				    //'readonly' => true,
				    'form_control_class' => 'col-md-4',
				),
			];

			$formCompany = [
				array(
					'id' => 'photo',
					'type' => 'image',
					'label' => lang('photo'),
					'value' => ($data) ? base_url().'uploads/photo/'.$data->photo : '',
				),
			
			];

			$form_build = [];
			$form_build[] = [
			    'title' => lang('formBiodata'),
			    'build' => $this->form_builder->build_form_horizontal($formBiodata)
			];

			$form_build[] = [
			    'title' => lang('formFamily'),
			    'build' => $this->form_builder->build_form_horizontal($formFamily)
			];

			$form_build[] = [
			    'title' => lang('formCompany'),
			    'build' => $this->form_builder->build_form_horizontal($formCompany)
			];

			$this->data['form'] = [
			    'action' => $this->data['module_url'] . 'save',
			    'build' => $form_build,
			    'class' => '',
			];


			$this->data['data'] = $data;
			$this->data['headingOverwrite'] = 'Profile';

			$this->template->_init();
			$this->template->form();
			$this->load->view('default/form_tab', $this->data);
		}
	}

	private function delete_photo($id_old = 0){
		$d =  $this->db->where('employee_id',$id_old)->get($this->table)->row();
		if (!empty($d)) {
			$path = './uploads/photo/'.$d->photo;
			if (file_exists($path)) {
				unlink($path);
			}
		}
		
		
	}

	function date_check($str){
		if (!empty($str)) {
			$regex = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';
			if(!preg_match($regex, $str))
	        {
	        	if (settings('language') != 'indonesia') {
	               $this->form_validation->set_message('date_check', 'The Field {field} not correct format.');
	            }
	            else
	            {
	            	$this->form_validation->set_message('date_check', 'Format {field} tidak benar.');
	            }	
	               return FALSE;
	        }
	        else
	        {
	               return TRUE;
	        }
		}
		else
		{
			return TRUE;
		}
		
	}

	public function save() {
		$this->input->is_ajax_request() or exit('No direct post submit allowed!');
		$this->load->library('form_validation');
		$_POST = $this->m_master->form_set_all_trim($_POST);
		$_POST['nama'] = ucwords($_POST['nama']);
		unset($_POST['code']);
		$form_validation_arr = array(
							        array(
							                'field' => 'nama',
							                'label' => 'lang:nama',
							                'rules' => 'required'
							        ),
							        array(
							                'field' => 'nik',
							                'label' => 'lang:nik',
							                'rules' => 'required'
							        ),
							        array(
							                'field' => 'gender',
							                'label' => 'lang:gender',
							                'rules' => 'required'
							        ),
							        array(
							                'field' => 'religion_id',
							                'label' => 'lang:religion_id',
							                'rules' => 'required'
							        ),
							        array(
							                'field' => 'address',
							                'label' => 'lang:address',
							                'rules' => 'required'
							        ),
							        array(
							                'field' => 'phone',
							                'label' => 'lang:phone',
							                'rules' => 'required'
							        ),
							        array(
							                'field' => 'child1_date_birth',
							                'label' => 'lang:child1_date_birth',
							                'rules' => 'callback_date_check'
							        ),
							        array(
							                'field' => 'child2_date_birth',
							                'label' => 'lang:child2_date_birth',
							                'rules' => 'callback_date_check'
							        ),
							        array(
							                'field' => 'child3_date_birth',
							                'label' => 'lang:child3_date_birth',
							                'rules' => 'callback_date_check'
							        ),
							        array(
							                'field' => 'partner_date_birth',
							                'label' => 'lang:partner_date_birth',
							                'rules' => 'callback_date_check'
							        ),
							);
		if (!empty($_POST['employee_id'])) {
			$form_validation_arr[1]['rules'] = 'required';
		}
		$this->form_validation->set_rules($form_validation_arr);
		if ($this->form_validation->run() === true) {
			$data = $this->input->post(null, true);
			if (isset($_FILES['photo']['name'])) {
				if (!$data['employee_id']) {
					$this->delete_photo();
				}

				if (isset($data['delete_photo']) && $data['delete_photo'] == '1') {
					$this->delete_photo();
				}
				
				// upload
				$file_ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
				$new_name = (str_replace(' ', '_', $data['code'])).'_'.generate_random_letters(5).'.'.$file_ext;
				$config = [];
				$config['file_name'] = $new_name;
				$config['upload_path']          = './uploads/photo/';
				$config['allowed_types']        = 'gif|jpg|png|jpeg';

				$do_upload = $this->m_master->upload_file($config,'photo');
				if ($do_upload['status'] != 1) {
					$return = array('message' => $do_upload['msg'], 'status' => 'error','fieldError' => ['photo']);
					 echo json_encode($return);
					 die();
				}

				$data['photo'] = $new_name;
				
			}
			else
			{
				unset($data['photo']);
			}

			unset($data['upload_photo']);
			unset($data['delete_photo']);

			$data['child1_date_birth'] = (empty($data['child1_date_birth'])) ? null : $data['child1_date_birth'];
			$data['child2_date_birth'] = (empty($data['child2_date_birth'])) ? null : $data['child2_date_birth'];
			$data['child3_date_birth'] = (empty($data['child3_date_birth'])) ? null : $data['child3_date_birth'];
			$data['partner_date_birth'] = (empty($data['partner_date_birth'])) ? null : $data['partner_date_birth'];

			do {
			    if (!$data['employee_id']) {
			    	$data['created_at'] =  Date('Y-m-d H:i:s');
			    	$data['created_by'] =  $this->data['user']->id;
			        $this->db->insert($this->table,$data);
			    } else {
			    	$data['updated_at'] =  Date('Y-m-d H:i:s');
			    	$data['updated_by'] =  $this->data['user']->id;
			    	$this->db->where('employee_id',$data['employee_id']);
			        $this->db->update($this->table,$data);
			    }
			    $return = array('message' => sprintf(lang('save_success'), lang('heading') . ' ' . $data['nama']), 'status' => 'success', 'redirect' => $this->data['module_url']);
			} while (0);

		}
		else {
				//print_r($this->form_validation->_field_data);die();
				$getFieldError = $this->m_master->set_formTab_Active_validation($this->form_validation->_field_data);
				// print_r($getFieldError);die();
            $return = array('message' => validation_errors(), 'status' => 'error','fieldError' => $getFieldError);
        }

        if (isset($return['redirect'])) {
            $this->session->set_flashdata('form_response_status', $return['status']);
            $this->session->set_flashdata('form_response_message', $return['message']);
        }
        echo json_encode($return);

	}


}


