<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_summernote extends CI_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function upload_image(){
		header('Content-Type: text/html; charset=UTF-8');

		if(isset($_FILES["image"]["name"])){

		    $SummernoteID = $this->input->get('id');
		    $unixTime = strtotime(Date('Y-m-d H:i:s'));
		    $ext = explode('.',$_FILES["image"]["name"]);
		    $unix_name = $SummernoteID.'_'.$unixTime.'.'.$ext[1];

		    $this->load->library('upload');

		    $pathFolderUrl = 'assets/images/summernote/';
		    $pathFolder = './'.$pathFolderUrl;

			if (!file_exists($pathFolder)) {
				mkdir($pathFolder,0777, true);
			}

		    $config['upload_path'] = $pathFolder;
		    $config['allowed_types'] = 'jpg|jpeg|png|gif';
		    $config['file_name'] = $unix_name;
		    $this->upload->initialize($config);
		    if(!$this->upload->do_upload('image')){
		        $this->upload->display_errors();
		        return FALSE;
		    }
		    else{


		        $data = $this->upload->data();
		        //Compress Image
		        $config['image_library']='gd2';
		        $config['source_image']= $pathFolder.$data['file_name'];
		        $config['create_thumb']= FALSE;
		        $config['maintain_ratio']= TRUE;
		        $config['quality']= '60%';
		        $config['width']= 800;
		        $config['height']= 800;
		        $config['new_image']= $pathFolder.$data['file_name'];
		        $this->load->library('image_lib', $config);
		        $this->image_lib->resize();

		        // Update data temporary summernote
		        $this->db->insert('summernote_image',
		            array('Image'=>$data['file_name'],
		                'SummernoteID' => $SummernoteID));

		        echo base_url().$pathFolderUrl.$data['file_name'];
		    }


		}
	}

	//Delete image summernote
	public function delete_image(){
	    header('Content-Type: text/html; charset=UTF-8');
	    $src = $this->input->post('src');
	    $file_path = str_replace(base_url(), '', $src);

	    // Get file name
	    $file_name = str_replace(base_url('assets/images/summernote/'), '', $src);

	    if (file_exists($file_path)) {
	    	if(unlink($file_path)){
	    	    $this->db->where('Image',$file_name);
	    	    $this->db->delete('summernote_image');
	    	}
	    }

	    echo 'File Delete Successfully';


	}


}
