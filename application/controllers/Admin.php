<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function index()
	{
		$this->dashboard();
	}


	public function login()
	{
		if($this->session->userdata('admin_logged_in')){
				
				//user already logged in, redirects to account page
				redirect('admin/dashboard');
				
		}	
		else {
				
				if($this->input->get('redirectURL') != ''){
					$url = $this->input->get('redirectURL');
					$this->session->set_flashdata('redirectURL', $url);	
				}
				//assign page title name
				$data['pageTitle'] = 'Admin Login';
				
				//assign page ID
				$data['pageID'] = 'admin_login';
				
				//load main body
				$this->load->view('admin_pages/admin_login_page', $data);
		}		

	}


	/**
		* Function to validate admin login
		*
		*/
        public function login_validation() {
			
			$this->session->keep_flashdata('redirectURL');
            
            $this->load->library('form_validation');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			
            $this->form_validation->set_rules('username','Username','required|trim|callback_validate_credentials');
            $this->form_validation->set_rules('password','Password','required|trim');
            
			 $this->form_validation->set_message('required', '%s cannot be blank!');
			
            if ($this->form_validation->run()){
				
				$data = array(
					'admin_username' => $this->input->post('username'),
					'admin_logged_in' => 1,
				);
				$this->session->set_userdata($data);

				//user already logged in, redirects to account page
				redirect('admin/dashboard');
				
            }
            else {
				
				//user not logged in, redirects to login page
				$this->login();
            }
                
		}		
		
		/**
		* Function to validate username
		*
		*/		
		public function validate_credentials() {
			
			if ($this->Admin->admin_can_log_in()){
				
				$username = $this->input->post('username');
				
				//check admin last login time from the logins table
				$last_login = $this->Logins->last_login_time($username);
				
				//if there is a record then update users record
				//otherwise ignore
				if($last_login){
					foreach($last_login as $login){
						$this->Logins->update_admin_login_time($username, $login->login_time);
					}
				}
				
				//create new login record after updating with previous entry
				$this->Logins->insert_login();
				return TRUE;
			}
			else {
				
				$this->Logins->insert_failed_login();
				
				$this->form_validation->set_message('validate_credentials', 'Incorrect username or password.');
				
				return FALSE;
				
			}
			
		}

		
		/**
		* Function to access admin account
		*
		*/
        public function dashboard() {
			 
			if($this->session->userdata('admin_logged_in')){
				
				//check if redirect url is set
				$redirect = '';
				if($this->session->flashdata('redirectURL')){
					$redirect = $this->session->flashdata('redirectURL');
					//redirect user to previous url
					//$url = 'account/'.$redirect;
					redirect($redirect);
				}	
				
				$username = $this->session->userdata('admin_username');
				
				$data['username']=$username;
				
				$data['users'] = $this->Admin->get_user($username);
				
				$data['messages_unread'] = $this->Messages->count_unread_messages($username);
				
				$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	
				
				$activities = $this->Site_activities->get_activities();
				
				$activity_group = '<a href="#" class="list-group-item"><i class="fa fa-star-o" aria-hidden="true"></i> No activities yet</a>';
				
				if(!empty($activities)){
					foreach($activities as $activity){
						//get users name
						$fullname = '';
						$query = $this->db->get_where('users', array('username' => $activity->username));
						if($query){
							foreach ($query->result() as $row){
								$fullname = $row->first_name.'-'.$row->last_name[0].'.';
							}							
						}	
						//get time ago
						$activity_time = $this->Site_activities->time_elapsed_string(strtotime($activity->activity_time));
						$icon = '<i class="fa fa-list-alt" aria-hidden="true"></i>';
						if($activity->keyword == 'Security'){
							$icon = '<i class="fa fa-lock" aria-hidden="true"></i>';
						}
						if($activity->keyword == 'Update'){
							$icon = '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>';
						}
						if($activity->keyword == 'Complete' || $activity->keyword == 'Finish'){
							$icon = '<i class="fa fa-th-list" aria-hidden="true"></i>';
						}
						
						$activity_group = '<a href="#" class="list-group-item">';
						$activity_group .= '<span class="badge">'.$activity_time.'</span>';
						$activity_group .= $icon .' <strong><em>'.$fullname.'</em></strong> '.$activity->description;
						$activity_group .= '</a>';
						
					}
				}
				
				$data['activity_group'] = $activity_group;
							
				//assign page title name
				$data['pageTitle'] = 'Admin Dashboard';
				
				//assign page ID
				$data['pageID'] = 'dashboard';
				
				//load header and page title
				$this->load->view('admin_pages/header', $data);
				
				//load main body
				$this->load->view('admin_pages/admin_account_page', $data);
				
				//load footer
				$this->load->view('admin_pages/footer');
				
			}
			else {
				if($this->session->flashdata('redirectURL')){
					$redirect = $this->session->flashdata('redirectURL');
					$url = 'admin/login?redirectURL='.$redirect;
					redirect($url);
				}else{	

					redirect('admin/login');
					//user not logged in, redirects to login page
					//redirect('home/','refresh');           
				} 

			}
            
        } 

		
		/***
		* Function to display messages
		*
		***/		
		public function messages(){

			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);													
				//$this->login();
				//redirect('admin/login/','refresh');
							
			}else{ 			
				$username = $this->session->userdata('admin_username');
					
				$data['messages_unread'] = $this->Messages->count_unread_messages($username);
					
				$data['sent_messages_count'] = $this->Messages->count_sent_messages($username);				
						
				$data['users'] = $this->Admin->get_user($username);

				$config = array();
				$config["base_url"] = base_url()."admin/messages";
								
				if($this->input->get('search') != ''){
						
						$search = html_escape($this->input->get('search'));
						
						$data['count'] = $this->Messages->count_search($search, $username);
						
						$data['display_option'] = 'Showing Results for "<strong><em>'.$search.'</em></strong>" <a href="'.base_url().'admin/messages"  >Show All</a>';
						
						$config["total_rows"] = $this->Admin->count_search($search, $username);
						$config["per_page"] = 10;
						$config["uri_segment"] = 3;
						$choice = $config["total_rows"] / $config["per_page"];
						$config["num_links"] = round($choice);
					
						$this->pagination->initialize($config);
						
						if($this->uri->segment(3) > 0)
							$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
						else
							$offset = $this->uri->segment(3);					
						
						$data['messages_array'] = $this->Admin->get_search($username, $search, $config["per_page"], $offset);	
						
				}else{	
				
					$data['display_option'] = '<strong>Showing All</strong>';
						
					$table = 'messages';
					$config["total_rows"] = $this->Admin->count_admin_messages($username);
						
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
		
					$this->pagination->initialize($config);
													
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
								
					//call the model function to get the messages data
					// $data['notification_array'] = $this->bids->get_user_notifications($email_address, $config["per_page"], $data['page']);	
					//call the model function to get the posts data
						
					$data['messages_array'] = $this->Admin->get_admin_messages($config["per_page"], $offset);	

					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	
						
					$data['count'] = $this->Admin->count_admin_messages($username);
				}	
				
				$data['pagination'] = $this->pagination->create_links();	
					
				//assign page title name
				$data['pageTitle'] = 'Messages';
					
				//assign page title name
				$data['pageID'] = 'messages';
										
				//load header and page title
				$this->load->view('admin_pages/header', $data);
					
				//load main body
				$this->load->view('admin_pages/messages_page');
				
				//load footer
				$this->load->view('admin_pages/footer');
									
			}
		}
		
		public function mark_as_read($message_id){
				
				$data = array(
					'opened' => '1',
				);
				$this->db->where('id', $message_id);
				$query = $this->db->update('messages', $data);
		}
		
		
		public function sent_messages(){

			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//$this->login();
				//redirect('admin/login/','refresh');
				
			}else{ 
			
					$username = $this->session->userdata('admin_username');

					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
					
					$data['users'] = $this->Admin->get_user($username);

					$config = array();
					$config["base_url"] = base_url()."admin/sent_messages";
									
					if($this->input->get('search') != ''){
							
							$search = html_escape($this->input->get('search'));
							
							$data['count'] = $this->Admin->count_search_sent($search, $username);
								
							$data["count_sent_messages"] = $this->Admin->count_search_sent($search, $username);
							$data['sent_messages_count'] = $this->Admin->count_search_sent($search, $username);				
						
							$data['display_option'] = 'Showing Results for "<strong><em>'.$search.'</em></strong>" <a href="'.base_url().'admin/sent_messages"  >Show All</a>';
							
							$config["total_rows"] = $this->Admin->count_search_sent($search, $username);
							$config["per_page"] = 10;
							$config["uri_segment"] = 3;
							$choice = $config["total_rows"] / $config["per_page"];
							$config["num_links"] = round($choice);
						
							$this->pagination->initialize($config);
							
							if($this->uri->segment(3) > 0)
								$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
							else
								$offset = $this->uri->segment(3);					
							
							$data['sent_messages'] = $this->Messages->get_search_sent($username, $search, $config["per_page"], $offset);	
							
					}else{	
					
						$data['display_option'] = '<strong>Showing All</strong>';
						
						$table = 'messages';
						$config["total_rows"] = $this->Admin->count_sent_messages($username);
						
						$config["per_page"] = 10;
						$config["uri_segment"] = 3;
						$choice = $config["total_rows"] / $config["per_page"];
						$config["num_links"] = round($choice);
				
						$this->pagination->initialize($config);
													
						if($this->uri->segment(3) > 0)
							$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
						else
							$offset = $this->uri->segment(3);					
							
						$data["count_sent_messages"] = $this->Admin->count_sent_messages($username);
						$data['sent_messages_count'] = $this->Admin->count_sent_messages($username);				
						
						$data['sent_messages'] = $this->Admin->get_sent_messages($username, $config["per_page"], $offset);	
						
						$data['count'] = $this->Admin->count_all($table);
						
					}
					$data['pagination'] = $this->pagination->create_links();
					
					
					//assign page title name
					$data['pageTitle'] = 'Sent Messages';
				
					//assign page ID
					$data['pageID'] = 'sent_messages';
								
					//load header and page title
					$this->load->view('admin_pages/header', $data);
				
					//load main body
					$this->load->view('admin_pages/sent_messages_page');
				
					//load footer
					$this->load->view('admin_pages/footer');
									
			}	
		}			
		
		/***
		* Function to validate send message
		*
		***/		
		public function send_message_validation(){
			
			$username = $this->session->userdata('admin_username');	
			
			$this->load->library('form_validation');
			
            $this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');
			
            $this->form_validation->set_rules('message_subject','Subject','required|trim|xss_clean');
            $this->form_validation->set_rules('message_details','Message','required|trim|xss_clean');
	
			$this->form_validation->set_message('required', '%s cannot be blank!');
			
			$table = $this->input->post('model');
			$id = $this->input->post('id');
			
			if ($this->form_validation->run()){

				if($this->prevent_double_post($username)){
					
				//	echo img('assets/images/round_error.png').'You must wait at least 20 seconds before you send another message!';
					//echo "<script language=\"javascript\">alert('You must wait at least 20 seconds before you send another message!')</script>";
					$this->session->set_flashdata('message_error', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-exclamation-circle"></i> You must wait at least 20 seconds before you send another message!</div>');
					$data['success'] = false;
					$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <i class="fa fa-exclamation-circle"></i> You must wait at least 20 seconds before you send another message!</div>';
					
				}else{			
						if($this->Admin->add_message()){
							
							$this->session->set_flashdata('message_sent', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Message has been sent!</div>');
							
							$username = $this->input->post('receiver_username');
						
							$data['new_count_message'] = $this->Messages->count_unread_messages($username);
							$data['success'] = true;
							$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Message sent!</div>';
				
						}else{
							$this->session->set_flashdata('message_error', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-exclamation-circle"></i> Message has not been sent!</div>');
							$data['success'] = false;
							$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Message not sent!</div>';

						}					
				}								
			}else {
					$this->session->set_flashdata('message_error', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-exclamation-circle"></i> Message has not been sent!</div>');
					$data['success'] = false;
					$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> ' . validation_errors() . '</div>';
		
			}
			echo json_encode($data);
			
		}
		
		/**
		* Function to prevent user from posting
		* same message within a few seconds
		*/			
		public function prevent_double_post($username){

			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			$min_date = strtotime("-20 second", $date);
			
			$max_date = date('Y-m-d H:i:s', time());
			$min_date = date('Y-m-d H:i:s', $min_date);
			
			$this->db->select('*');
			$this->db->from('messages');
			$this->db->where('sender_username', $username);
			
			$this->db->where("date_sent BETWEEN '$min_date' AND '$max_date'", NULL, FALSE);

			$query = $this->db->get();
			
			if ($query->num_rows() >= 1){	
				return true;
			}else {
				return false;
			}	
								
		}	
		
		/**
		* Function to check_double_post 
		* 
		*/			
		public function check_double_post(){
			
			$admin_username = $this->session->userdata('username');

			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			$min_date = strtotime("-20 second", $date);
			
			$max_date = date('Y-m-d H:i:s', time());
			$min_date = date('Y-m-d H:i:s', $min_date);
			
			$this->db->select('*');
			$this->db->from('messages');
			$this->db->where('sender_username', $admin_username);
			
			$this->db->where("date_sent BETWEEN '$min_date' AND '$max_date'", NULL, FALSE);

			$query = $this->db->get();
			
			if ($query->num_rows() >= 1){	
				return TRUE;
			}else {
				$this->form_validation->set_message('check_double_post', 'You must wait at least 20 seconds before you send another message!');
				return FALSE;
			}	
		}
		
		
	public function multi_delete(){
			
			if($this->input->post('cb') != '' && $this->input->post('table')!= '' )
			{
				$checked =  $this->input->post('cb');
				$table = $this->input->post('table');
				//$this->load->model(array('Issue'));
				$new_model = '';
				
				if(strtolower($table) == 'admin_users'){
					$new_model = 'Admin_model';
				}else{
					$new_model = ucfirst($table.'_model');
				}
				
				//$issue = new Issue();	
				$object = new $new_model();
				//$issue->load($issue_id);
				foreach($checked as $each){
					if(strtolower($table) == 'questions'){
						$path = './uploads/questions/'.$each.'/';
						delete_files($path);
						//unlink("uploads/questions/".$each);
						//$path = base_url().'uploads/questions/'.$each.'/';
						//if($this->Admin->deleteFiles($path)){
						//	rmdir($path);
						//}
					}	
					$object->load($each);
					$object->delete();
					
					
				}
				
				$this->session->set_flashdata('deleted', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> The entry(ies) have been deleted!</div>');
				$url = 'admin/'.$table;
				redirect($url);			
				
			}else{
				$url = current_url();
				redirect($url);
			}
		}
			
		
		
		
		/***
		* Function to handle admins
		*
		***/		
		public function admin_users(){
			
			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//redirect('admin/login/','refresh');
				
			}else{			

				if($this->Admin->check_admin_access()){
					
					$username = $this->session->userdata('admin_username');	
					
					$data['users'] = $this->Admin->get_user($username);
					
					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
							
					$config = array();
					$config["base_url"] = base_url()."admin/admin_users";
									
					$table = 'admin_users';
				
					$config["total_rows"] = $this->Admin->count_all($table);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);

					$this->pagination->initialize($config);
												
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
						
					$data['admins_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	

					$data['count'] = $this->Admin->count_all($table);
					
					$data['pagination'] = $this->pagination->create_links();
					
					//assign page title name
					$data['pageTitle'] = 'Admin Users';
							
					//assign page title name
					$data['pageID'] = 'admin_users';
									
					//load header and page title
					$this->load->view('admin_pages/header', $data);
						
					//load main body
					$this->load->view('admin_pages/admin_users_page', $data);	
				
					//load footer
					$this->load->view('admin_pages/footer');
									
				}else{

					redirect('admin/error');
				}
			}
		}
		
		
		/**
		* Function to validate add admin
		*
		*/			
		public function add_admin(){

				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('admin_name','Admin Name','required|trim|xss_clean');
				$this->form_validation->set_rules('admin_username','Admin Username','required|trim|xss_clean|is_unique[admins.admin_username]');
				$this->form_validation->set_rules('admin_password','Admin Password','required|trim|xss_clean|md5');
				
				$this->form_validation->set_message('required', '%s cannot be blank!');
				$this->form_validation->set_message('is_unique', 'Username already exists!');
				$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			
				if($this->form_validation->run()){
						
						$data = array(
					
							'admin_name' => $this->input->post('admin_name'),
							'admin_username' => $this->input->post('admin_username'),
							'admin_password' => $this->input->post('admin_password'),
							'access_level' => '1',
							'date_created' => date('Y-m-d H:i:s'),
						
						);

						$insert_id = $this->Admin->create_admin($data);
						
						if($insert_id){
							
							if(isset($_FILES["newUserPhoto"])){
								
								$file_name = '';
								
								$path = './uploads/admins/'.$insert_id.'/';
								if(!is_dir($path))
								{
									mkdir($path,0777);
								}
								$config['upload_path'] = $path;
								$config['allowed_types'] = 'gif|jpg|jpeg|png';
								$config['max_size'] = 2048000;
								$config['max_width'] = 3048;
								$config['max_height'] = 2048;
									
								$config['file_name'] = $insert_id.'.jpg';
								
								$this->load->library('upload', $config);	

								$this->upload->overwrite = true;
								if($this->upload->do_upload('newUserPhoto')){
							
									$upload_data = $this->upload->data();
										
									if (isset($upload_data['file_name'])){
										$file_name = $upload_data['file_name'];
									}				
								}else{
									$data['upload_error'] = $this->upload->display_errors();
								}
								$profile_data = array(
									'profile_photo' => $file_name,		
								);
								$this->Admin->update_admin($profile_data);	
							}	

													
							$detail = $this->db->select('*')->from('admin_users')->where('id', $insert_id)->get()->row();	
							$data['id'] = $detail->id;
							$data['admin_username'] = $detail->admin_username;
							$data['admin_name'] = $detail->admin_name;
							$data['access_level'] = $detail->access_level;
							
							$last_login = '';
							if ($detail->last_login == '0000-00-00 00:00:00' || $detail->last_login == ''){  
								$last_login = 'Never';
							}
							else{	
								$last_login = date("F j, Y", strtotime($detail->last_login));
							}
							$data['last_login'] = $last_login;
							$data['date_created'] = date("F j, Y", strtotime($detail->date_created));
							
							//prepare buttons
							$data['messageButton'] = '<a data-toggle="modal" data-target="#messageModal" class="btn btn-success send_message"  id="'.$detail->admin_username.'" title="Send Message to '.$detail->admin_name.'"><i class="fa fa-envelope"></i></a>';
							$data['viewButton'] = '<a data-toggle="modal" data-target="#viewModal" class="btn btn-info view_user"  id="'.$detail->admin_username.'" title="Click to View '.$detail->admin_name.'"><i class="fa fa-search"></i></a>';
							$data['editButton'] = '<a data-toggle="modal" data-target="#editModal" class="btn btn-warning edit_user"  id="'.$detail->admin_username.'" title="Click to '.$detail->admin_name.'"><i class="fa fa-pencil"></i></a>';
							$data['deleteButton'] = '<a data-toggle="modal" data-target="#deleteModal" class="btn btn-danger delete_user"  id="'.$detail->admin_username.'" title="Click to '.$detail->admin_name.'"><i class="fa fa-trash"></i></a>';
							
							$this->session->set_flashdata('admin_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> A new admin user (<i>'.$this->input->post('admin_name').'</i>) has been created!</div>');
							$data['success'] = true;
							$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> A new admin user (<i>'.$this->input->post('admin_name').'</i>) has been created!</div>';
						
						}else{
							$this->session->set_flashdata('admin_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> The new admin user has not been created!</div>');
							$data['success'] = false;
							$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> The new admin user has not been created!</div>';
						
						}				
				}
				else {
					
					$data['success'] = false;
					$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.validation_errors().'</div>';
					//$data['errors'] = $this->form_validation->error_array();
					//$this->addhand();	
				}

			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			//echo json_encode($data);			
		}


		
		/**
		* Function to validate update admin 
		* form
		*/			
		public function update_admin(){
				
				if(isset($_FILES["uploadPhoto"])){
					
					$admin_id = $this->input->post('adminID');
					//$upload = false;
					
					$path = './uploads/admins/'.$admin_id.'/';
					if(!is_dir($path))
					{
						mkdir($path,0777);
					}
					$config['upload_path'] = $path;
					$config['allowed_types'] = 'gif|jpg|jpeg|png';
					$config['max_size'] = 2048000;
					$config['max_width'] = 3048;
					$config['max_height'] = 2048;
						
					$config['file_name'] = $admin_id.'.jpg';
					
					$this->load->library('upload', $config);	

					$this->upload->overwrite = true;
										
				}
				
			$this->load->library('form_validation');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			$this->form_validation->set_rules('admin_name','Admin Name','required|trim|xss_clean');
			$this->form_validation->set_rules('username','Username','required|trim|xss_clean');
			$this->form_validation->set_rules('admin_password','Password','trim|xss_clean');
			$this->form_validation->set_rules('access_level','Access Level','required|trim|xss_clean');	
					
			if ($this->form_validation->run()){
				
				$username = $this->input->post('admin_username');
				
				$user = $this->Admin->get_user($username);
				$profile_photo = '';
				
				if($this->upload->do_upload('uploadPhoto')){
						
					$upload_data = $this->upload->data();
						
					$file_name = '';
					if (isset($upload_data['file_name'])){
						$file_name = $upload_data['file_name'];
					}
					$profile_photo = $file_name;				
				}else{
					if($this->upload->display_errors()){
						$data['upload_error'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> There are errors with the photo!<br/>'.$this->upload->display_errors().'</div>';
					}
					//$data['upload_error'] = $this->upload->display_errors();
					foreach($user as $u){
						$profile_photo = $u->profile_photo;
					}
				}					

				$password = '';
				if($this->input->post('new_password') == ''){
					$password = $this->input->post('old_password');
				}else{
					$password = $this->input->post('new_password');
				}

				$data = array(
					'admin_name' => $this->input->post('admin_name'),
					'admin_username' => $this->input->post('admin_username'),
					'admin_password' => $password,
					'access_level' => $this->input->post('access_level'),
				);
				
				if ($this->Admin->update_admin_user($data)){	
				
					$this->session->set_flashdata('admin_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Admin updated!</div>');
					
					$data['success'] = true;
					$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Admin has been updated!</div>';
				}
				
			}else {
				$data['success'] = false;
				$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> There are errors on the form!'.validation_errors().'</div>';
			}
			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			//echo json_encode($data);			
		}			



		/**
		* Function to handle display
		* user details
		* 
		*/	
		public function user_details(){
			
			$username = $this->session->userdata('admin_username');

			$detail = $this->db->select('*')->from('users')->where('username',$this->input->post('username'))->get()->row();
			
			$username = $this->input->post('username');

			if($detail){

					$data['id'] = $detail->id;
					
					$data['headerTitle'] = $detail->first_name .' '.$detail->last_name;			

					$thumbnail = '';
					$filename = FCPATH.'uploads/users/'.$detail->id.'/'.$detail->profile_photo;

					//check if record in db is url thus facebook or google
					if(filter_var($detail->profile_photo, FILTER_VALIDATE_URL)){
						//diplay facebook avatar
						$thumbnail = '<img src="'.$detail->profile_photo.'" class="social_profile img-responsive img-rounded" width="150" height="150" />';
					}
					elseif($detail->profile_photo == '' || $detail->profile_photo == null || !file_exists($filename)){
						$thumbnail = '<img src="'.base_url().'assets/images/icons/avatar.jpg" class="img-responsive img-rounded" width="150" height="150" />';
					}
					
					else{
						$thumbnail = '<img src="'.base_url().'uploads/users/'.$detail->id.'/'.$detail->profile_photo.'" class="img-responsive img-rounded" width="140" height="150" />';
					}	
					$data['thumbnail'] = $thumbnail;
					$data['first_name'] = $detail->first_name;
					$data['last_name'] = $detail->last_name;
					$data['fullname'] = $detail->first_name .' '.$detail->last_name;
					$data['tagline'] = $detail->tagline;
					$data['address'] = $detail->address;
					$data['city'] = $detail->city;
					$data['postcode'] = $detail->postcode;
					$data['state'] = $detail->state;
					$data['country'] = $detail->country;
					
					$data['username'] = $detail->username;
					$data['email_address'] = $detail->email_address;
					$data['old_password'] = $detail->password;
					$data['mobile'] = $detail->mobile;
					
					$birthday = '';
					if($detail->birthday == '0000-00-00' || $detail->birthday == ''){
						$birthday = '';
					}else{
						$birthday = date("F j, Y", strtotime($detail->birthday));
					}
					
					$data['birthday'] = $birthday;
					$data['dob'] = $detail->birthday;
					$data['profile_description'] = $detail->profile_description;
					
					$data['account_balance'] = '$ '.number_format($detail->account_balance, 0) ;
					$data['acct_balance'] = number_format($detail->account_balance, 0) ;
					$data['security_question'] = $detail->security_question;
					$data['security_answer'] = $detail->security_answer;
					$data['date_created'] = date("F j, Y", strtotime($detail->date_created));
					
					$last_login = '';
					if($detail->last_login == '0000-00-00 00:00:00' || $detail->last_login == ''){
						$last_login = 'Never';
					}else{
						$last_login = date("F j, Y, g:i a", strtotime($detail->last_login));
					}
					$data['last_login'] = $last_login;
					
					$data['model'] = 'users';
					$data['success'] = true;
					
					
			}else {
				$data['success'] = false;
			}
			
			echo json_encode($data);
			
		}

		
		/***
		* Function to handle users
		*
		***/		
		public function users(){
			
			if(!$this->session->userdata('admin_logged_in')){
				
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);				
				//redirect('admin/login/','refresh');
				
			}else{  
			
				$username = $this->session->userdata('admin_username');	
				
				$data['users'] = $this->Admin->get_user($username);
				
				$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

				$data['messages_unread'] = $this->Messages->count_unread_messages($username);
				
				//country list dropdown
				$country_options = '<select name="country" id="userCountry">';
				$country_options .= '<option value="0" selected="selected">Select Country</option>';
					
				$this->db->from('countries');
				$this->db->order_by('id');
				$result = $this->db->get();
				if($result->num_rows() > 0) {
					foreach($result->result_array() as $row){
						$country_options .= '<option value="'.$row['name'].'">'.$row['name'].'</option>';			
					}
				}
				$country_options .= '</select>';
				$data['country_options'] = $country_options;
				
				$config = array();
				$config["base_url"] = base_url()."admin/users";
				if($this->input->get('search') != ''){
						
						$search = html_escape($this->input->get('search'));
						$data['count'] = $this->Users->count_search_users($search);
						
						$data['display_option'] = 'Showing Results for "<strong><em>'.$search.'</em></strong>" <a href="'.base_url().'admin/users"  >Show All</a>';
						
						$config["total_rows"] = $this->Users->count_search_users($search);
						$config["per_page"] = 10;
						$config["uri_segment"] = 3;
						$choice = $config["total_rows"] / $config["per_page"];
						$config["num_links"] = round($choice);
					
						$this->pagination->initialize($config);
						
						if($this->uri->segment(3) > 0)
							$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
						else
							$offset = $this->uri->segment(3);					
						
						$data['users_array'] = $this->Users->search_users($search, $config["per_page"], $offset);	
						
				}else{	
				
					$data['display_option'] = '<strong>Showing All</strong>';
						
					$table = 'users';
				
					$config["total_rows"] = $this->Admin->count_all($table);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);

					$this->pagination->initialize($config);
									
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
							
					//call the model function to get the messages data
				   // $data['notification_array'] = $this->bids->get_user_notifications($email_address, $config["per_page"], $data['page']);	
					//call the model function to get the posts data
					$data['users_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	

					$data['count'] = $this->Admin->count_all($table);
				}	
				$data['pagination'] = $this->pagination->create_links();
				
				//assign page title name
				$data['pageTitle'] = 'Users List';
						
				//assign page title name
				$data['pageID'] = 'users_list';
								
				//load header and page title
				$this->load->view('admin_pages/header', $data);
					
				//load main body
				$this->load->view('admin_pages/users_list_page', $data);	
				
				//load footer
				$this->load->view('admin_pages/footer');
								
			}
		}
		
		
		/**
		* Function to validate add user
		*
		*/			
		public function adduser(){

				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('first_name','First name','required|trim|xss_clean');
				$this->form_validation->set_rules('last_name','Last name','required|trim|xss_clean');
				$this->form_validation->set_rules('mobile','Mobile','trim|xss_clean|regex_match[/^[0-9\+\(\)\/-]+$/]');
				$this->form_validation->set_rules('email_address','Email Address','required|trim|xss_clean|valid_email|is_unique[users.email_address]');
				$this->form_validation->set_rules('address','Address','required|trim|xss_clean');
				$this->form_validation->set_rules('city','City','required|trim|xss_clean');
				$this->form_validation->set_rules('postcode','Postcode','required|trim|xss_clean');
				$this->form_validation->set_rules('state','State','required|trim|xss_clean');
				$this->form_validation->set_rules('country','Country','required|trim|xss_clean');

				$this->form_validation->set_rules('birthday','Birthday','trim|xss_clean');
				$this->form_validation->set_rules('username','Username','required|trim|xss_clean|is_unique[users.username]|is_unique[admin_users.admin_username]');
				
				$this->form_validation->set_rules('password','Password','required|trim|xss_clean|md5');
				
				$this->form_validation->set_message('required', '%s cannot be blank!');
				$this->form_validation->set_message('is_unique', '%s is already registered!');
				$this->form_validation->set_message('regex_match', 'Please enter a valid phone number!');
				$this->form_validation->set_message('valid_email', 'Please enter a valid email address!');
				
				$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			
				if($this->form_validation->run()){
		
						$birthday = $this->input->post('birthday');
						$birthday  = date('Y-m-d', strtotime($birthday));
						
						$add = array(
										
							'first_name' => $this->input->post('first_name'),
							'last_name' => $this->input->post('last_name'),
							'mobile' => $this->input->post('mobile'),
							'email_address' => $this->input->post('email_address'),
							'address' => $this->input->post('address'),
							'city' => $this->input->post('city'),
							'postcode' => $this->input->post('postcode'),
							'state' => $this->input->post('state'),
							'country' => $this->input->post('country'),
							'birthday' => $birthday,
							'username' => $this->input->post('username'),
							'password' => $this->input->post('password'),
							'account_balance' => 0.00,
							'date_created' => date('Y-m-d H:i:s'),
						
						);
						
						$table = 'users';	
						//$this->Admin->add_to_db($table, $data)
						
						$insert_id = $this->Admin->add_to_db($table, $add);
						
						if($insert_id){
							
							if(isset($_FILES["newUserPhoto"])){
								
								$file_name = '';
								
								$path = './uploads/users/'.$insert_id.'/';
								if(!is_dir($path))
								{
									mkdir($path,0777);
								}
								$config['upload_path'] = $path;
								$config['allowed_types'] = 'gif|jpg|jpeg|png';
								$config['max_size'] = 2048000;
								$config['max_width'] = 3048;
								$config['max_height'] = 2048;
									
								$config['file_name'] = $insert_id.'.jpg';
								
								$this->load->library('upload', $config);	

								$this->upload->overwrite = true;
								if($this->upload->do_upload('newUserPhoto')){
							
									$upload_data = $this->upload->data();
										
									if (isset($upload_data['file_name'])){
										$file_name = $upload_data['file_name'];
									}				
								}else{
									$data['upload_error'] = $this->upload->display_errors();
								}
								$profile_data = array(
									'profile_photo' => $file_name,		
								);
								$this->Admin->user_update($profile_data);	
							}	
				
							$detail = $this->db->select('*')->from('users')->where('id', $insert_id)->get()->row();	
							$data['id'] = $detail->id;
							$data['fullname'] = $detail->first_name .' '.$detail->last_name;
							$data['address'] = $detail->address;
							$data['city'] = $detail->city;
							$data['postcode'] = $detail->postcode;
							$data['state'] = $detail->state;
							$data['country'] = $detail->country;
							$data['account_balance'] = number_format($detail->account_balance, 0);
							
							$last_login = '';
							if ($detail->last_login == '0000-00-00 00:00:00' || $detail->last_login == ''){  
								$last_login = 'Never';
							}
							else{	
								$last_login = date("F j, Y", strtotime($detail->last_login));
							}
							$data['last_login'] = $last_login;
							
							//prepare buttons
							$data['messageButton'] = '<a data-toggle="modal" data-target="#messageModal" class="btn btn-success send_message"  id="'.$detail->email_address.'" title="Send Message to '.$detail->first_name .' '.$detail->last_name.'"><i class="fa fa-envelope"></i></a>';
							$data['viewButton'] = '<a data-toggle="modal" data-target="#viewModal" class="btn btn-info view_user"  id="'.$detail->email_address.'" title="Click to View '.$detail->first_name .' '.$detail->last_name.'"><i class="fa fa-eye"></i></a>';
							$data['editButton'] = '<a data-toggle="modal" data-target="#editModal" class="btn btn-warning edit_user"  id="'.$detail->email_address.'" title="Click to '.$detail->first_name .' '.$detail->last_name.'"><i class="fa fa-pencil"></i></a>';
							$data['deleteButton'] = '<a data-toggle="modal" data-target="#deleteModal" class="btn btn-danger delete_user"  id="'.$detail->email_address.'" title="Click to '.$detail->first_name .' '.$detail->last_name.'"><i class="fa fa-trash"></i></a>';
							
							$this->session->set_flashdata('user_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> A new user has been added!</div>');
							$data['success'] = true;
							$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> User has been added!</div>';

							//update complete redirects to success page
							//redirect('admin/users');							
						}else{
							$this->session->set_flashdata('user_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> The user has not been added!</div>');
							$data['success'] = false;
							$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> User not added!</div>';
		
							//update complete redirects to success page
							//redirect('admin/users');							
						}				
				}
				else {
					
					$data['success'] = false;
					$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> User not added!'.validation_errors().'</div>';
					$data['errors'] = $this->form_validation->error_array();
					//$this->add_user();	
				}

			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			//echo json_encode($data);			
		}
		
		
		/**
		* Function to validate update user 
		* form
		*/			
		public function update_user(){
				
				if(isset($_FILES["uploadPhoto"])){
					
					$user_id = $this->input->post('userID');
					//$upload = false;
					
					$path = './uploads/users/'.$user_id.'/';
					if(!is_dir($path))
					{
						mkdir($path,0777);
					}
					$config['upload_path'] = $path;
					$config['allowed_types'] = 'gif|jpg|jpeg|png';
					$config['max_size'] = 2048000;
					$config['max_width'] = 3048;
					$config['max_height'] = 2048;
						
					$config['file_name'] = $user_id.'.jpg';
					
					$this->load->library('upload', $config);	

					$this->upload->overwrite = true;
										
				}
				
			$this->load->library('form_validation');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			
			$this->form_validation->set_rules('first_name','First Name','required|trim|xss_clean');
			$this->form_validation->set_rules('last_name','Last Name','required|trim|xss_clean');
			$this->form_validation->set_rules('address','Address','required|trim|xss_clean');
			$this->form_validation->set_rules('city','City','required|trim|xss_clean');
			$this->form_validation->set_rules('postcode','Postcode','required|trim|xss_clean');
			$this->form_validation->set_rules('state','State','required|trim|xss_clean');
			$this->form_validation->set_rules('country','Country','required|trim|xss_clean');

			$this->form_validation->set_rules('email_address','Email Address','required|trim|xss_clean|valid_email');
			$this->form_validation->set_rules('mobile','Mobile','trim|xss_clean|regex_match[/^[0-9\+\(\)\/-]+$/]');

			$this->form_validation->set_rules('birthday','Birthday','trim|xss_clean');
			$this->form_validation->set_rules('acct_bal','Account Balance','trim|xss_clean');
			$this->form_validation->set_rules('security_question','Security Question','trim|xss_clean');
			$this->form_validation->set_rules('security_answer','Security Answer','trim|xss_clean');
			$this->form_validation->set_rules('tag_line','Tagline','trim|xss_clean');
			$this->form_validation->set_rules('profile_description','Profile Description','trim|xss_clean');
			$this->form_validation->set_rules('total_rating','Total Rating','trim|xss_clean');
			$this->form_validation->set_rules('no_of_raters','No of raters','trim|xss_clean');
			$this->form_validation->set_rules('new_password','New Password','trim|xss_clean|md5');
										
			if ($this->form_validation->run()){
				
				$username = $this->input->post('username');
				
				$user = $this->Users->get_user($username);
				$profile_photo = '';
				
				if($this->upload->do_upload('uploadPhoto')){
						
					$upload_data = $this->upload->data();
						
					$file_name = '';
					if (isset($upload_data['file_name'])){
						$file_name = $upload_data['file_name'];
					}
					$profile_photo = $file_name;				
				}else{
					if($this->upload->display_errors()){
						$data['upload_error'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> There are errors with the photo!<br/>'.$this->upload->display_errors().'</div>';

					}
					//$data['upload_error'] = $this->upload->display_errors();
					foreach($user as $u){
						$profile_photo = $u->profile_photo;
					}
				}					

				$password = '';
				if($this->input->post('new_password') == ''){
					$password = $this->input->post('old_password');
				}else{
					$password = $this->input->post('new_password');
				}
				
				$birthday = $this->input->post('birthday');   
				$birthday = date('Y-m-d', strtotime($birthday));
				
				$account_balance = floatval(preg_replace('/[^\d\.]/', '', $this->input->post('acct_bal')));
					
				$update = array(
					'profile_photo' => $profile_photo,
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'address' => $this->input->post('address'),
					'city' => $this->input->post('city'),
					'postcode' => $this->input->post('postcode'),
					'state' => $this->input->post('state'),
					'country' => $this->input->post('country'),
					'email_address' => $this->input->post('email_address'),
					'mobile' => $this->input->post('mobile'),
					'birthday' => $birthday,
					'account_balance' => $account_balance,
					'tagline' => $this->input->post('tag_line'),
					'profile_description' => $this->input->post('profile_description'),
					'username' => $this->input->post('username'),
					'password' => $password,
				);
				
				if ($this->Admin->user_update($update)){	
				
					$this->session->set_flashdata('user_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> User updated!</div>');
					$data['success'] = true;
					$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> User has been updated!</div>';
				}
				
			}else {
				$data['success'] = false;
				$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> There are errors on the form!'.validation_errors().'</div>';
			}
			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			//echo json_encode($data);			
		}
		
		/***
		* Function to handle temporary users
		*
		***/
		public function temp_users(){
			
			if(!$this->session->userdata('admin_logged_in')){
				
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);				
				//redirect('admin/login/','refresh');
				
			}else{			
			
				$username = $this->session->userdata('admin_username');
				
				$data['users'] = $this->Admin->get_user($username);
				
				$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

				$data['messages_unread'] = $this->Messages->count_unread_messages($username);

				$config = array();
				$config["base_url"] = base_url()."admin/temp_users";
				
				$table = 'temp_users';
			
				$config["total_rows"] = $this->Admin->count_all($table);
				$config["per_page"] = 10;
				$config["uri_segment"] = 3;
				$choice = $config["total_rows"] / $config["per_page"];
				$config["num_links"] = round($choice);

				$this->pagination->initialize($config);
								
				if($this->uri->segment(3) > 0)
					$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
				else
					$offset = $this->uri->segment(3);					
							
				//call the model function to get the messages data
			   // $data['notification_array'] = $this->bids->get_user_notifications($email_address, $config["per_page"], $data['page']);	
				//call the model function to get the posts data
			    $data['temp_users_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	
				
				$data['count'] = $this->Admin->count_all($table);
					
				$data['pagination'] = $this->pagination->create_links();				
				//$temp_users_list = $this->Temp_users->get();

				$data['messages_unread'] = $this->Messages->count_unread_messages($admin_username);
									
				//assign page title name
				$data['pageTitle'] = 'Temp Users';
				
				//assign page title name
				$data['pageID'] = 'temp_users';				
					
				//load header and page title
				$this->load->view('admin_pages/header', $data);
					
				//load main body
				$this->load->view('admin_pages/temp_users_list_page');		
				
				//load footer
				$this->load->view('admin_pages/footer');
								
			}
		}		
		
		

		/***
		* Function for details modification
		*
		***/		
		public function user_modifications(){

			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//$this->login();
				//redirect('admin/login/','refresh');
				
			}else{ 
			
					$username = $this->session->userdata('admin_username');

					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
				
					$data['users'] = $this->Admin->get_user($username);

					$config = array();
					$config["base_url"] = base_url()."admin/user_modifications";
					
					$table = 'details_modification';
					$config["total_rows"] = $this->Admin->count_all($table);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
			
					$this->pagination->initialize($config);
												
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
						
					$data['user_modifications_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	
					
					$data['count'] = $this->Admin->count_all($table);
					
					$data['pagination'] = $this->pagination->create_links();
				
					//assign page title name
					$data['pageTitle'] = 'User Modifications';
				
					//assign page ID
					$data['pageID'] = 'user_modifications';
								
					//load header and page title
					$this->load->view('admin_pages/header', $data);
				
					//load main body
					$this->load->view('admin_pages/user_modifications_page');
				
					//load footer
					$this->load->view('admin_pages/footer');
									
			}	
		}	
					

		/***
		* Function for login list
		*
		***/		
		public function login_list(){

			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//$this->login();
				//redirect('admin/login/','refresh');
				
			}else{ 
			
					$username = $this->session->userdata('admin_username');

					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
						
					$data['users'] = $this->Admin->get_user($username);

					$config = array();
					$config["base_url"] = base_url()."admin/login_list";
					
					$table = 'logins';
					$config["total_rows"] = $this->Admin->count_all($table);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
			
					$this->pagination->initialize($config);
												
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
						
					$data['logins_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	
					
					$data['count'] = $this->Admin->count_all($table);
					
					$data['pagination'] = $this->pagination->create_links();
				
					//assign page title name
					$data['pageTitle'] = 'Active Logins';
				
					//assign page ID
					$data['pageID'] = 'logins';
								
					//load header and page title
					$this->load->view('admin_pages/header', $data);
				
					//load main body
					$this->load->view('admin_pages/logins_page');
				
					//load footer
					$this->load->view('admin_pages/footer');
									
			}	
		}	

		/***
		* Function for failed logins
		*
		***/		
		public function failed_logins(){

			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//$this->login();
				//redirect('admin/login/','refresh');
				
			}else{ 
			
					$username = $this->session->userdata('admin_username');

					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
						
					$data['users'] = $this->Admin->get_user($username);

					$config = array();
					$config["base_url"] = base_url()."admin/failed_logins";
					
					$table = 'failed_logins';
					$config["total_rows"] = $this->Admin->count_all($table);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
			
					$this->pagination->initialize($config);
												
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
						
					$data['failed_logins_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	
					
					$data['count'] = $this->Admin->count_all($table);
					
					$data['pagination'] = $this->pagination->create_links();
				
					//assign page title name
					$data['pageTitle'] = 'Failed Logins';
				
					//assign page ID
					$data['pageID'] = 'failed_logins';
								
					//load header and page title
					$this->load->view('admin_pages/header', $data);
				
					//load main body
					$this->load->view('admin_pages/failed_logins_page');
				
					//load footer
					$this->load->view('admin_pages/footer');
			}	
		}	

		/***
		* Function for failed resets
		*
		***/		
		public function failed_resets(){

			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//$this->login();
				//redirect('admin/login/','refresh');
				
			}else{ 
			
					$username = $this->session->userdata('admin_username');

					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
					
					$data['users'] = $this->Admin->get_user($username);

					$config = array();
					$config["base_url"] = base_url()."admin/failed_resets";
					
					$table = 'failed_resets';
					$config["total_rows"] = $this->Admin->count_all($table);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
			
					$this->pagination->initialize($config);
												
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
						
					$data['failed_resets_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	
					
					$data['count'] = $this->Admin->count_all($table);
					
					$data['pagination'] = $this->pagination->create_links();
				
					//assign page title name
					$data['pageTitle'] = 'Failed Resets';
				
					//assign page ID
					$data['pageID'] = 'failed_resets';
								
					//load header and page title
					$this->load->view('admin_pages/header', $data);
				
					//load main body
					$this->load->view('admin_pages/failed_resets_page');
				
					//load footer
					$this->load->view('admin_pages/footer');
									
			}	
		}	


		/**
		* Function to handle jquery display and edit
		* quiz questions 
		* 
		*/	
		public function category_details(){
			
			$detail = $this->db->select('*')->from('question_categories')->where('id',$this->input->post('id'))->get()->row();
			
			$id = $this->input->post('id');

			if($detail){

					$data['id'] = $detail->id;
					
					$data['headerTitle'] = $detail->category;			

					$data['category'] = $detail->category;
					
					$category = '<select name="category" class="form-control">';
					
					$this->db->from('question_categories');
					$this->db->order_by('id');
					$result = $this->db->get();
					if($result->num_rows() > 0) {
						foreach($result->result_array() as $row){
							$default = ($row['category'] == $detail->category)?'selected':'';
							$category .= '<option value="'.$row['category'].'" '.$default.'>'.$row['category'].'</option>';			
						}
					}
					//$category .= '<option value="'.$detail->category.'" selected="selected">'.$detail->category.'</option>';
					//$category .= '<option value="Random">Random</option>';
					//$category .= '<option value="Fiesty">Fiesty</option>';
					//$category .= '<option value="Romance">Romance</option>';
					$category .= '</select>';
					
					$data['category'] = $category;
					
					$data['model'] = 'question_categories';
					$data['success'] = true;
					
			}else {
				$data['success'] = false;
			}
			
			echo json_encode($data);
			
		}
		
		/***
		* Function to handle questions
		* table
		***/		
		public function question_categories(){
			
			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//redirect('admin/login/','refresh');
				
			}else{			
					
					$username = $this->session->userdata('admin_username');	
					
					$data['users'] = $this->Admin->get_user($username);
					
					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
					
					$data['categories_array'] = $this->Question_categories->get_categories();	
					
					$data['count'] = $this->Question_categories->count_categories();
						
					//assign page title name
					$data['pageTitle'] = 'Question Categories';
							
					//assign page title name
					$data['pageID'] = 'question_categories';
									
					//load header and page title
					$this->load->view('admin_pages/header', $data);
						
					//load main body
					$this->load->view('admin_pages/question_categories_page', $data);	
				
					//load footer
					$this->load->view('admin_pages/footer');
									
			}
		}

		
		/**
		* Function to validate add category
		*
		*/			
		public function add_category(){

			if($this->session->userdata('admin_logged_in')){ 

				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('category','Category','required|trim|xss_clean|is_unique[question_categories.category]');
				
				$this->form_validation->set_message('required', '%s cannot be blank!');
				$this->form_validation->set_message('is_unique', 'Category already exists! Please enter a new category!');
				$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			
				if($this->form_validation->run()){
		
						$data = array(
							'category' => $this->input->post('category'),
						);
						$table = 'question_categories';	
						
						$insert_id = $this->Admin->add_to_db($table, $data);
						
						if($insert_id){
						
							$detail = $this->db->select('*')->from('question_categories')->where('id', $insert_id)->get()->row();	
							$data['id'] = $detail->id;
							
							$data['category'] = $detail->category;
							
							$this->session->set_flashdata('category_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut(600); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> A new category has been added!</div>');
							$data['success'] = true;
							$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> A new category has been added!</div>';
						
						}else{
							$this->session->set_flashdata('category_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut(600); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> The new category has not been added!</div>');
							$data['success'] = false;
							$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> The new category has not been added!</div>';
						
						}				
				}
				else {
					
					$data['success'] = false;
					$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.validation_errors().'</div>';
					//$data['errors'] = $this->form_validation->error_array();
					//$this->addhand();	
				}

				// Encode the data into JSON
				$this->output->set_content_type('application/json');
				$data = json_encode($data);

				// Send the data back to the client
				$this->output->set_output($data);
				//echo json_encode($data);	
			}else{
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);
			}
		}

		
		/**
		* Function to validate update security 
		* question
		*/			
		public function update_category(){
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('category','Category','required|trim|xss_clean');
			
			$this->form_validation->set_message('required', '%s cannot be blank!');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			   	
			if ($this->form_validation->run()){
				
				$id = $this->input->post('categoryID');
						
				$edit_data = array(
					'category' => $this->input->post('category'),
				);
				
				if ($this->Question_categories->update_category($edit_data, $id)){	
				
					$this->session->set_flashdata('category_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Category updated!</div>');
					
					$data['success'] = true;
					$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Category has been updated!</div>';	
				}
				
			}else {
				$data['success'] = false;
				$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> There are errors on the form!'.validation_errors().'</div>';
			}
			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			//echo json_encode($data);			
		}
		
		

		/**
		* Function to handle jquery display and edit
		* quiz questions 
		* 
		*/	
		public function question_details(){
			
			$detail = $this->db->select('*')->from('questions')->where('id',$this->input->post('id'))->get()->row();
			
			$id = $this->input->post('id');

			if($detail){

					$data['id'] = $detail->id;
					
					$data['headerTitle'] = $detail->question;			

					$data['question'] = $detail->question;
					
					$category = '<select name="category" class="form-control">';
					
					$this->db->from('question_categories');
					$this->db->order_by('id');
					$result = $this->db->get();
					if($result->num_rows() > 0) {
						foreach($result->result_array() as $row){
							$default = ($row['category'] == $detail->category)?'selected':'';
							$category .= '<option value="'.$row['category'].'" '.$default.'>'.$row['category'].'</option>';			
						}
					}
					//$category .= '<option value="'.$detail->category.'" selected="selected">'.$detail->category.'</option>';
					//$category .= '<option value="Random">Random</option>';
					//$category .= '<option value="Fiesty">Fiesty</option>';
					//$category .= '<option value="Romance">Romance</option>';
					$category .= '</select>';
					
					$data['category'] = $category;
					$data['option_1'] = $detail->option_1;
					$data['option_1_image'] = $detail->option_1_image;
					$data['option_2'] = $detail->option_2;
					$data['option_2_image'] = $detail->option_2_image;
					
					$data['model'] = 'questions';
					$data['success'] = true;
					
					
			}else {
				$data['success'] = false;
			}
			
			echo json_encode($data);
			
		}
		
		/***
		* Function to handle questions
		* table
		***/		
		public function questions(){
			
			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//redirect('admin/login/','refresh');
				
			}else{			
					
					$username = $this->session->userdata('admin_username');	
					
					$data['users'] = $this->Admin->get_user($username);
					
					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
					
					$config = array();
					$config["base_url"] = base_url().'admin/questions';
					
					if($this->input->get('search') != ''){
						
						$search = html_escape($this->input->get('search'));
						$data['count'] = $this->Questions->count_search_questions($search);
						
						$data['display_option'] = 'Showing Results for "<strong><em>'.$search.'</em></strong>" <a href="'.base_url().'admin/questions"  >Show All</a>';
						$config["base_url"] = base_url().'admin/questions?search='.$search.'';
						$config["total_rows"] = $this->Questions->count_search_questions($search);
						$config["per_page"] = $this->Questions->count_search_questions($search);
						$config["uri_segment"] = 3;
						$choice = $config["total_rows"] / $config["per_page"];
						$config["num_links"] = round($choice);
					
						$this->pagination->initialize($config);
						
						if($this->uri->segment(3) > 0)
							$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
						else
							$offset = $this->uri->segment(3);					
						
						$data['questions_array'] = $this->Questions->search_questions($search, $config["per_page"], $offset);	
						
					}else{
					
						$table = 'questions';
						
						$data['count'] = $this->Admin->count_all($table);
						$data['display_option'] = 'Showing '.$this->Admin->count_all($table).' records';
						
						$config["total_rows"] = $this->Admin->count_all($table);
						$config["per_page"] = 10;
						$config["uri_segment"] = 3;
						$choice = $config["total_rows"] / $config["per_page"];
						$config["num_links"] = round($choice);
					
						$this->pagination->initialize($config);
						
						if($this->uri->segment(3) > 0)
							$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
						else
							$offset = $this->uri->segment(3);					
						
						$data['questions_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	
					
					}
					
					$data['pagination'] = $this->pagination->create_links();	
	
					//$data['questions_array'] = $this->Admin->get_security_questions();	

					//$data['count'] = $this->Admin->count_questions();
					
					//assign page title name
					$data['pageTitle'] = 'Quiz Questions';
							
					//assign page title name
					$data['pageID'] = 'questions';
									
					//load header and page title
					$this->load->view('admin_pages/header', $data);
						
					//load main body
					$this->load->view('admin_pages/questions_page', $data);	
				
					//load footer
					$this->load->view('admin_pages/footer');
									
			}
		}
		
		private function set_upload_questions_options(){   
			//upload a question image options
			$config = array();
			$path = './uploads/questions/'.$insert_id.'/';
			if(!is_dir($path)){
				mkdir($path,0777);
			}
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size'] = 2048000;
			$config['max_width'] = 3048;
			$config['max_height'] = 2048;
			$config['overwrite']     = FALSE;

			return $config;
		}
		
		/**
		* Function to validate add question
		*
		*/			
		public function add_question(){

			if($this->session->userdata('admin_logged_in')){ 

				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('question','Question','required|trim|xss_clean|is_unique[questions.question]');
				$this->form_validation->set_rules('category','Category','required|trim|xss_clean');
				
				$this->form_validation->set_rules('option_1','Option 1','required|trim|xss_clean');
				$this->form_validation->set_rules('option_2','Option 2','required|trim|xss_clean');
				
				$this->form_validation->set_message('required', '%s cannot be blank!');
				$this->form_validation->set_message('is_unique', 'Question already exists! Please enter a new question!');
				$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			
				if($this->form_validation->run()){
		
						$data = array(
							'question' => $this->input->post('question'),
							'category' => $this->input->post('category'),
							'option_1' => ucfirst($this->input->post('option_1')),
							'option_2' => ucfirst($this->input->post('option_2')),
						);
						$table = 'questions';	
						
						$insert_id = $this->Admin->add_to_db($table, $data);
						
						if($insert_id){
							
							$file1 = '';
							$file2 = '';
								
							if(isset($_FILES["image_1"]) && isset($_FILES["image_2"])){
								
								$path = './uploads/questions/'.$insert_id.'/';
								if(!is_dir($path))
								{
									mkdir($path,0777);
								}
								
								$i = 1;
								$files = array();
								
				
								foreach ($_FILES as $key => $value) {
									
									if (!empty($value['name'])) {
										
										$config['upload_path'] = $path;
										$config['allowed_types'] = 'gif|jpg|jpeg|png';
										$config['max_size'] = 2048000;
										
										$config['max_width'] = 3048;
										$config['max_height'] = 2048;
										$this->load->library('upload', $config);
										if($i == 1){
											//$file1 = $insert_id.'_1.jpg';
											$config['file_name'] = $insert_id.'_1.jpg';
										}
										if($i == 2){
											//$file2 = $insert_id.'_2.jpg';
											$config['file_name'] = $insert_id.'_2.jpg';
										}
										$this->upload->initialize($config);
										
										if (!$this->upload->do_upload($key)) {
											$data['upload_msg'] = $this->upload->display_errors('', '');
										}else{
											//$files[$i] = $this->upload->data();
											$upload_data = $this->upload->data();
											if (isset($upload_data['file_name'])){
												$files[$i] = $upload_data['file_name'];
											}	
											$i++;
										}
									}
								}
								$file1 = $files[1];
								$file2 = $files[2];
								
								$image_data = array(
									'option_1_image' => $file1,	
									'option_2_image' => $file2,
								);
							
								$this->Questions->update_question($image_data, $insert_id);
								
							}
							
						
							$detail = $this->db->select('*')->from('questions')->where('id', $insert_id)->get()->row();	
							$data['id'] = $detail->id;
							
							$data['question'] = $detail->question;
							$data['category'] = $detail->category;
							$data['option_1'] = $detail->option_1;
							$data['option_1_image'] = '<img class="media-object" src="'.base_url().'uploads/questions/'.$detail->id.'/'.$detail->option_1_image.'" width="25" height="20" alt="option_1_image">';
							$data['option_2'] = $detail->option_2;
							$data['option_2_image'] = '<img class="media-object" src="'.base_url().'uploads/questions/'.$detail->id.'/'.$detail->option_2_image.'" width="25" height="20" alt="option_1_image">';
							
							$this->session->set_flashdata('question_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut(600); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> A new question has been added!</div>');
							$data['success'] = true;
							$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> A new question has been added!</div>';
						
						}else{
							$this->session->set_flashdata('question_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut(600); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> The new question has not been added!</div>');
							$data['success'] = false;
							$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> The new question has not been added!</div>';
						
						}				
				}
				else {
					
					$data['success'] = false;
					$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.validation_errors().'</div>';
					//$data['errors'] = $this->form_validation->error_array();
					//$this->addhand();	
				}

				// Encode the data into JSON
				$this->output->set_content_type('application/json');
				$data = json_encode($data);

				// Send the data back to the client
				$this->output->set_output($data);
				//echo json_encode($data);	
			}else{
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);
			}
		}

		
		/**
		* Function to validate update quiz 
		* question
		*/			
		public function update_question(){
			
			$file1 = $this->input->post('old_image_1');
			$file2 = $this->input->post('old_image_2');
			
			$question_id = $this->input->post('questionID');
			
			if(isset($_FILES["edit_option_1_image"])){

				$path = './uploads/questions/'.$question_id.'/';
				if(!is_dir($path)){
						mkdir($path,0777);
				}
				$config['upload_path'] = $path;
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['max_size'] = 2048000;
				$config['max_width'] = 3048;
				$config['max_height'] = 2048;
				$config['file_name'] = $question_id.'_1.jpg';		
				$this->load->library('upload', $config);	

				$this->upload->overwrite = true;
					
				if($this->upload->do_upload('edit_option_1_image')){
						
					$upload_data = $this->upload->data();
						
					if (isset($upload_data['file_name'])){
						$file1 = $upload_data['file_name'];
					}
								
				}else{
					if($this->upload->display_errors()){
					//	$data['upload_error'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> There are errors with the photo!<br/>'.$this->upload->display_errors().'</div>';
					}
				}			
			}
			
			if(isset($_FILES["edit_option_2_image"])){

				$path = './uploads/questions/'.$question_id.'/';
				if(!is_dir($path)){
						mkdir($path,0777);
				}
				$config['upload_path'] = $path;
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['max_size'] = 2048000;
				$config['max_width'] = 3048;
				$config['max_height'] = 2048;
				$config['file_name'] = $question_id.'_2.jpg';		
				$this->load->library('upload', $config);	

				$this->upload->overwrite = true;
				
				$this->upload->initialize($config);
					
				if($this->upload->do_upload('edit_option_2_image')){
						
					$upload_data = $this->upload->data();
						
					if (isset($upload_data['file_name'])){
						$file2 = $upload_data['file_name'];
					}		
				}else{
					if($this->upload->display_errors()){
					//	$data['upload_error'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> There are errors with the photo!<br/>'.$this->upload->display_errors().'</div>';
					}
				}				
			}	
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('full_question','Question','required|trim|xss_clean');
			$this->form_validation->set_rules('category','Category','required|trim|xss_clean');
			
			$this->form_validation->set_rules('edit_option_1','Option 1','required|trim|xss_clean');
			$this->form_validation->set_rules('edit_option_2','Option 2','required|trim|xss_clean');
				
			$this->form_validation->set_message('required', '%s cannot be blank!');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			   	
			if ($this->form_validation->run()){
							
				$edit_data = array(
					'question' => $this->input->post('full_question'),
					'category' => $this->input->post('category'),
					'option_1' => ucfirst($this->input->post('edit_option_1')),
					'option_1_image' => $file1,
					'option_2' => ucfirst($this->input->post('edit_option_2')),
					'option_2_image' => $file2,
					
				);
				
				if ($this->Questions->update_question($edit_data, $question_id)){	
				
					$this->session->set_flashdata('question_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Question updated!</div>');
					
					$data['success'] = true;
					$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Question has been updated!</div>';	
				}
				
			}else {
				$data['success'] = false;
				$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> There are errors on the form!'.validation_errors().'</div>';
			}
			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			//echo json_encode($data);			
		}
		
		
		/***
		* Function to handle questions
		*
		***/		
		public function answers(){
			
			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//redirect('admin/login/','refresh');
				
			}else{			
					
					$username = $this->session->userdata('admin_username');	
					
					$data['users'] = $this->Admin->get_user($username);
					
					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
					
					$config = array();
					$config["base_url"] = base_url()."admin/answers";
					
					$table = 'answers';
					$config["total_rows"] = $this->Admin->count_all($table);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
				
					$this->pagination->initialize($config);
					
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
					
					$data['quiz_answers_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	
					
					$data['pagination'] = $this->pagination->create_links();	

					$data['count'] = $this->Admin->count_all($table);	

					//$data['questions_array'] = $this->Admin->get_security_questions();	

					//$data['count'] = $this->Admin->count_questions();
					
					//assign page title name
					$data['pageTitle'] = 'Quiz Answers';
							
					//assign page title name
					$data['pageID'] = 'quiz_answers';
									
					//load header and page title
					$this->load->view('admin_pages/header', $data);
						
					//load main body
					$this->load->view('admin_pages/quiz_answers_page', $data);	
				
					//load footer
					$this->load->view('admin_pages/footer');
									
			}
		}
				
		
		/***
		* Function for payments
		*
		***/		
		public function payments(){

			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//$this->login();
				//redirect('admin/login/','refresh');
				
			}else{ 
			
					$username = $this->session->userdata('username');

					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
				
					$data['users'] = $this->Admin->get_user($username);

					$config = array();
					$config["base_url"] = base_url()."admin/payments";
					
					$table = 'payments';
					$config["total_rows"] = $this->Admin->count_all($table);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
		
					$this->pagination->initialize($config);
												
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
						
					$data['payments_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	
					
					$data['count'] = $this->Admin->count_all($table);
					
					$data['pagination'] = $this->pagination->create_links();
				
					//assign page title name
					$data['pageTitle'] = 'Payments';
				
					//assign page ID
					$data['pageID'] = 'payments';
								
					//load header and page title
					$this->load->view('admin_pages/header', $data);
				
					//load main body
					$this->load->view('admin_pages/payments_page');
				
					//load footer
					$this->load->view('admin_pages/footer');
									
			}	
		}	
			

		/***
		* Function for payment methods
		*
		***/		
		public function card_payment_methods(){

			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//$this->login();
				//redirect('admin/login/','refresh');
				
			}else{ 
			
					$username = $this->session->userdata('admin_username');

					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
				
					$data['users'] = $this->Admin->get_user($username);

					$config = array();
					$config["base_url"] = base_url()."admin/card_payment_methods";
					
					$table = 'card_payment_methods';
					$config["total_rows"] = $this->Admin->count_all($table);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
					
					$this->pagination->initialize($config);
												
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
						
					$data['card_payment_methods_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	
					
					$data['count'] = $this->Admin->count_all($table);
					
					$data['pagination'] = $this->pagination->create_links();
				
					//assign page title name
					$data['pageTitle'] = 'Card Payment Methods';
				
					//assign page ID
					$data['pageID'] = 'payment_methods';
								
					//load header and page title
					$this->load->view('admin_pages/header', $data);
				
					//load main body
					$this->load->view('admin_pages/card_payment_methods_page');
				
					//load footer
					$this->load->view('admin_pages/footer');
									
			}	
		}	

		/***
		* Function for payment methods
		*
		***/		
		public function paypal_payment_methods(){

			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//$this->login();
				//redirect('admin/login/','refresh');
				
			}else{ 
			
					$username = $this->session->userdata('admin_username');

					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
				
					$data['users'] = $this->Admin->get_user($username);

					$config = array();
					$config["base_url"] = base_url()."admin/paypal_payment_methods";
					
					$table = 'paypal_payment_methods';
					$config["total_rows"] = $this->Admin->count_all($table);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
					
					$this->pagination->initialize($config);
												
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
						
					$data['paypal_payment_methods_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	
					
					$data['count'] = $this->Admin->count_all($table);
					
					$data['pagination'] = $this->pagination->create_links();
				
					//assign page title name
					$data['pageTitle'] = 'PayPal Payment Methods';
				
					//assign page ID
					$data['pageID'] = 'payment_methods';
								
					//load header and page title
					$this->load->view('admin_pages/header', $data);
				
					//load main body
					$this->load->view('admin_pages/paypal_payment_methods_page');
				
					//load footer
					$this->load->view('admin_pages/footer');
									
			}	
		}	
			
		/***
		* Function for contact us
		*
		***/		
		public function contact_us(){

			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//$this->login();
				redirect('admin/login/','refresh');
				
			}else{ 
			
					$username = $this->session->userdata('admin_username');

					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
				
					$data['users'] = $this->Admin->get_user($username);

					$config = array();
					$config["base_url"] = base_url()."admin/contact_us";
					
					$table = 'contact_us';
					$config["total_rows"] = $this->Admin->count_all($table);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
					
					$this->pagination->initialize($config);
					
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
					
					$data['contact_us_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	
					
					$data['count'] = $this->Admin->count_all($table);
					
					$data['pagination'] = $this->pagination->create_links();
				
					//assign page title name
					$data['pageTitle'] = 'Contact Us';
				
					//assign page ID
					$data['pageID'] = 'contact_us';
								
					//load header and page title
					$this->load->view('admin_pages/header', $data);
				
					//load main body
					$this->load->view('admin_pages/contact_us_messages_page');
				
					//load footer
					$this->load->view('admin_pages/footer');
									
			}	
		}	
			
			

		/***
		* Function for notifications
		*
		***/		
		public function alerts(){

			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//$this->login();
				//redirect('admin/login/','refresh');
				
			}else{ 
			
					$username = $this->session->userdata('admin_username');

					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
									
					$data['users'] = $this->Admin->get_user($username);
				
					$activities = $this->Site_activities->get_activities();
					
					$activity_group = '<a href="#" class="list-group-item"><i class="fa fa-star-o" aria-hidden="true"></i> No activities yet</a>';
					
					if(!empty($activities)){
						foreach($activities as $activity){
							//get users name
							$fullname = '';
							$query = $this->db->get_where('users', array('username' => $activity->username));
							if($query){
								foreach ($query->result() as $row){
									$fullname = $row->first_name.'-'.$row->last_name[0].'.';
								}							
							}	
							//get time ago
							$activity_time = $this->Site_activities->time_elapsed_string(strtotime($activity->activity_time));
							$icon = '<i class="fa fa-list-alt" aria-hidden="true"></i>';
							if($activity->keyword == 'Security'){
								$icon = '<i class="fa fa-lock" aria-hidden="true"></i>';
							}
							if($activity->keyword == 'Update'){
								$icon = '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>';
							}
							if($activity->keyword == 'Complete' || $activity->keyword == 'Finish'){
								$icon = '<i class="fa fa-th-list" aria-hidden="true"></i>';
							}
							
							$activity_group = '<a href="#" class="list-group-item">';
							$activity_group .= '<span class="badge">'.$activity_time.'</span>';
							$activity_group .= $icon .' <strong><em>'.$fullname.'</em></strong> '.$activity->description;
							$activity_group .= '</a>';
							
						}
					}
					
					$data['activity_group'] = $activity_group;
					
					//assign page title name
					$data['pageTitle'] = 'Alerts';
				
					//assign page ID
					$data['pageID'] = 'alerts';
								
					//load header and page title
					$this->load->view('admin_pages/header', $data);
				
					//load main body
					$this->load->view('admin_pages/alerts_list_page');
				
					//load footer
					$this->load->view('admin_pages/footer');					
			}	
		}	
					

		/***
		* Function for admin profile
		*
		***/		
		public function profile(){

			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//$this->login();
				//redirect('admin/login/','refresh');
				
			}else{ 
			
				$username = $this->session->userdata('admin_username');

				$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	
				$data['messages_unread'] = $this->Messages->count_unread_messages($username);			
				$data['users'] = $this->Admin->get_user($username);

				//assign page title name
				$data['pageTitle'] = 'Profile';
				
				//assign page ID
				$data['pageID'] = 'profile';
								
				//load header and page title
				$this->load->view('admin_pages/header', $data);
				
				//load main body
				$this->load->view('admin_pages/profile_page');
				
				//load footer
				$this->load->view('admin_pages/footer');
									
			}	
		}	
			

		/***
		* Function for admin settings
		*
		***/		
		public function settings(){

			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//$this->login();
				//redirect('admin/login/','refresh');
				
			}else{ 
			
				$username = $this->session->userdata('admin_username');

				$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

				$data['messages_unread'] = $this->Messages->count_unread_messages($username);
				
				$data['users'] = $this->Admin->get_user($username);

				//assign page title name
				$data['pageTitle'] = 'Settings';
				
				//assign page ID
				$data['pageID'] = 'settings';
								
				//load header and page title
				$this->load->view('admin_pages/header', $data);
				
				//load main body
				$this->load->view('admin_pages/settings_page');
				
				//load footer
				$this->load->view('admin_pages/footer');
									
			}	
		}	

			

		/**
		* Function to validate update admin settings
		* form
		*/			
		public function settings_update(){
			
			$admin_username = $this->session->userdata('username');
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			
			$this->form_validation->set_rules('new_password','New Password','required|trim|xss_clean');
			$this->form_validation->set_rules('confirm_new_password','Confirm New Password','required|matches[new_password]|trim|xss_clean');
			
			if ($this->form_validation->run()){	

				$data = array(
					'admin_password' => md5($this->input->post('new_password')),
				);

				if ($this->Admin->update_admin($data)){	
				
					$this->session->set_flashdata('admin_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Your password has been updated!</div>');
							
					//update complete redirects to success page
					redirect('admin/profile/');	
				}
				
			}else {
				//$url = 'edit/hand_users/'.$hand_id;
				//Go back to the Edit Details Page if validation fails
				$this->settings();
			}
		
		}
		
			
		/***
		* Function for error message
		*
		***/
		public function error(){
			
			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//$this->login();
				//redirect('admin/login/','refresh');
				
			}else{ 	
			
				$username = $this->session->userdata('admin_username');	
						
				$data['users'] = $this->Admin->get_user($username);
						
				$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

				$data['messages_unread'] = $this->Messages->count_unread_messages($username);
				
				//assign page title name
				$data['pageTitle'] = 'Error';
									
				//assign page title name
				$data['pageID'] = 'error';
											
				//load header and page title
				$this->load->view('admin_pages/header', $data);
								
				//load main body
				$this->load->view('admin_pages/error_page', $data);	
					
				//load footer
				$this->load->view('admin_pages/footer');
			}	
		}
		


		/**
		* Function to handle jquery display and edit
		* security questions 
		* 
		*/	
		public function security_question_details(){
			
			$detail = $this->db->select('*')->from('security_questions')->where('id',$this->input->post('id'))->get()->row();
			
			$id = $this->input->post('id');

			if($detail){

					$data['id'] = $detail->id;
					
					$data['headerTitle'] = $detail->question;			

					$data['question'] = $detail->question;
					
					$data['model'] = 'questions';
					$data['success'] = true;
					
					
			}else {
				$data['success'] = false;
			}
			
			echo json_encode($data);
			
		}
				
		/***
		* Function to handle security questions
		*
		***/		
		public function security_questions(){
			
			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//redirect('admin/login/','refresh');
				
			}else{			
					
					$admin_username = $this->session->userdata('admin_username');	
					
					$data['users'] = $this->Admin->get_user($admin_username);
					
					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($admin_username);
				

					$config = array();
					$config["base_url"] = base_url()."admin/security_questions";
					
					$table = 'security_questions';
					$config["total_rows"] = $this->Admin->count_all($table);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
				
					$this->pagination->initialize($config);
					
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
					
					$data['questions_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	
					
					$data['pagination'] = $this->pagination->create_links();	

					$data['count'] = $this->Admin->count_all($table);	

					//$data['questions_array'] = $this->Admin->get_security_questions();	

					//$data['count'] = $this->Admin->count_questions();
					
					//assign page title name
					$data['pageTitle'] = 'Security Questions';
							
					//assign page title name
					$data['pageID'] = 'security_questions';
									
					//load header and page title
					$this->load->view('admin_pages/header', $data);
						
					//load main body
					$this->load->view('admin_pages/security_questions_page', $data);	
				
					//load footer
					$this->load->view('admin_pages/footer');
									
			}
		}		

		
		/**
		* Function to validate add security_question
		*
		*/			
		public function add_security_question(){

			if($this->session->userdata('admin_logged_in')){ 

				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('security_question','Security Question','required|trim|xss_clean|is_unique[security_questions.question]');
				
				$this->form_validation->set_message('required', '%s cannot be blank!');
				$this->form_validation->set_message('is_unique', 'Security Question already exists! Please enter a new question!');
				$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			
				if($this->form_validation->run()){
		
						$data = array(
							'question' => $this->input->post('security_question'),
						);
						$table = 'security_questions';	
						
						$insert_id = $this->Admin->add_to_db($table, $data);
						
						if($insert_id){
						
							$detail = $this->db->select('*')->from('security_questions')->where('id', $insert_id)->get()->row();	
							$data['id'] = $detail->id;
							
							$data['question'] = $detail->question;
							
							$this->session->set_flashdata('question_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut(600); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> A new question has been added!</div>');$data['success'] = true;
							$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> A new question has been added!</div>';
						
						}else{
							$this->session->set_flashdata('question_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut(600); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> The new question has not been added!</div>');
							$data['success'] = false;
							$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> The new question has not been added!</div>';
						
						}				
				}
				else {
					
					$data['success'] = false;
					$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.validation_errors().'</div>';
					//$data['errors'] = $this->form_validation->error_array();
					//$this->addhand();	
				}

				// Encode the data into JSON
				$this->output->set_content_type('application/json');
				$data = json_encode($data);

				// Send the data back to the client
				$this->output->set_output($data);
				//echo json_encode($data);	
			}else{
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);
			}
		}

		
		/**
		* Function to validate update security 
		* question
		*/			
		public function update_security_question(){
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('security_question','Security Question','required|trim|xss_clean');
			
			$this->form_validation->set_message('required', '%s cannot be blank!');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			   	
			if ($this->form_validation->run()){
				
				$id = $this->input->post('squestionID');
						
				$edit_data = array(
					'question' => $this->input->post('security_question'),
				);
				
				if ($this->Security_questions->update_question($edit_data, $id)){	
				
					$this->session->set_flashdata('question_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Question updated!</div>');
					
					$data['success'] = true;
					$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Question has been updated!</div>';	
				}
				
			}else {
				$data['success'] = false;
				$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> There are errors on the form!'.validation_errors().'</div>';
			}
			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			//echo json_encode($data);			
		}
		

		/**
		* Function to handle jquery display and edit
		* quiz questions 
		* 
		*/	
		public function product_category_details(){
			
			$detail = $this->db->select('*')->from('product_categories')->where('id',$this->input->post('id'))->get()->row();
			
			$id = $this->input->post('id');

			if($detail){

					$data['id'] = $detail->id;
					
					$data['headerTitle'] = $detail->category_name;			

					$data['category_name'] = $detail->category_name;
					
					$category = '<select name="product_category" id="product_category" class="form-control">';
					
					$this->db->from('product_categories');
					$this->db->order_by('id');
					$result = $this->db->get();
					if($result->num_rows() > 0) {
						foreach($result->result_array() as $row){
							$default = ($row['category_name'] == $detail->category_name)?'selected':'';
							$category .= '<option value="'.$row['category_name'].'" '.$default.'>'.$row['category_name'].'</option>';			
						}
					}
					//$category .= '<option value="'.$detail->category.'" selected="selected">'.$detail->category.'</option>';
					//$category .= '<option value="Random">Random</option>';
					//$category .= '<option value="Fiesty">Fiesty</option>';
					//$category .= '<option value="Romance">Romance</option>';
					$category .= '</select>';
					
					$data['category'] = $category;
					
					$data['model'] = 'product_categories';
					$data['success'] = true;
					
			}else {
				$data['success'] = false;
			}
			
			echo json_encode($data);
			
		}
		
		/***
		* Function to handle product categories
		* table
		***/		
		public function product_categories(){
			
			if(!$this->session->userdata('admin_logged_in')){
								
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);							
				//redirect('admin/login/','refresh');
				
			}else{			
					
					$username = $this->session->userdata('admin_username');	
					
					$data['users'] = $this->Admin->get_user($username);
					
					$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

					$data['messages_unread'] = $this->Messages->count_unread_messages($username);
					
					$config = array();
					$config["base_url"] = base_url()."admin/product_categories";
					
					if($this->input->get('search') != ''){
							
							$search = html_escape($this->input->get('search'));
							$data['count'] = $this->Product_categories->count_search_categories($search);
							
							$data['display_option'] = 'Showing Results for "<strong><em>'.$search.'</em></strong>" <a href="'.base_url().'admin/users"  >Show All</a>';
							
							$config["total_rows"] = $this->Product_categories->count_search_categories($search);
							$config["per_page"] = $this->Product_categories->count_search_categories($search);
							$config["uri_segment"] = 3;
							$choice = $config["total_rows"] / $config["per_page"];
							$config["num_links"] = round($choice);
						
							$this->pagination->initialize($config);
							
							if($this->uri->segment(3) > 0)
								$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
							else
								$offset = $this->uri->segment(3);					
							
							$data['categories_array'] = $this->Product_categories->search_categories($search, $config["per_page"], $offset);	
							
					}else{	
					
						$data['display_option'] = '<strong>Showing All</strong>';
							
						$table = 'product_categories';
					
						$config["total_rows"] = $this->Admin->count_all($table);
						$config["per_page"] = 10;
						$config["uri_segment"] = 3;
						$choice = $config["total_rows"] / $config["per_page"];
						$config["num_links"] = round($choice);

						$this->pagination->initialize($config);
										
						if($this->uri->segment(3) > 0)
							$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
						else
							$offset = $this->uri->segment(3);					
								
						//call the model function to get the messages data
					   // $data['notification_array'] = $this->bids->get_user_notifications($email_address, $config["per_page"], $data['page']);	
						//call the model function to get the posts data
						$data['categories_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	

						$data['count'] = $this->Admin->count_all($table);
					}	
					
					$data['pagination'] = $this->pagination->create_links();
					
					//assign page title name
					$data['pageTitle'] = 'Product Categories';
							
					//assign page title name
					$data['pageID'] = 'product_categories';
									
					//load header and page title
					$this->load->view('admin_pages/header', $data);
						
					//load main body
					$this->load->view('admin_pages/product_categories_page', $data);	
				
					//load footer
					$this->load->view('admin_pages/footer');
									
			}
		}

		
		/**
		* Function to validate add category
		*
		*/			
		public function add_product_category(){

			if($this->session->userdata('admin_logged_in')){ 

				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('category_name','Category Name','required|trim|xss_clean|is_unique[product_categories.category_name]');
				
				$this->form_validation->set_message('required', '%s cannot be blank!');
				$this->form_validation->set_message('is_unique', 'Category already exists! Please enter a new category!');
				$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			
				if($this->form_validation->run()){
		
						$data = array(
							'category_name' => $this->input->post('category_name'),
						);
						$table = 'product_categories';	
						
						$insert_id = $this->Admin->add_to_db($table, $data);
						
						if($insert_id){
						
							$detail = $this->db->select('*')->from('product_categories')->where('id', $insert_id)->get()->row();	
							$data['id'] = $detail->id;
							
							$data['category_name'] = $detail->category_name;
							
							$this->session->set_flashdata('category_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut(600); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> A new category has been added!</div>');
							$data['success'] = true;
							$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> A new category has been added!</div>';
						
						}else{
							$this->session->set_flashdata('category_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut(600); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> The new category has not been added!</div>');
							$data['success'] = false;
							$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> The new category has not been added!</div>';
						
						}				
				}
				else {
					
					$data['success'] = false;
					$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.validation_errors().'</div>';
					//$data['errors'] = $this->form_validation->error_array();
					//$this->addhand();	
				}

				// Encode the data into JSON
				$this->output->set_content_type('application/json');
				$data = json_encode($data);

				// Send the data back to the client
				$this->output->set_output($data);
				//echo json_encode($data);	
			}else{
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);
			}
		}

		
		/**
		* Function to validate update product 
		* category
		*/			
		public function update_product_category(){
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('category_name','Category Name','required|trim|xss_clean');
			
			$this->form_validation->set_message('required', '%s cannot be blank!');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			   	
			if ($this->form_validation->run()){
				
				$id = $this->input->post('categoryID');
						
				$edit_data = array(
					'category_name' => $this->input->post('category_name'),
				);
				
				if ($this->Product_categories->update_category($edit_data, $id)){	
				
					$this->session->set_flashdata('category_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Category updated!</div>');
					
					$data['success'] = true;
					$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Category has been updated!</div>';	
				}
				
			}else {
				$data['success'] = false;
				$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> There are errors on the form!'.validation_errors().'</div>';
			}
			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			//echo json_encode($data);			
		}
		
		
		/**
		* Function to handle display
		* product details
		* 
		*/	
		public function image_details(){
			
			$detail = $this->db->select('*')->from('product_images')->where('image_name',$this->input->post('image_name'))->get()->row();
			
			$image_name = $this->input->post('image_name');

			if($detail){

					$data['id'] = $detail->id;
					
					$data['headerTitle'] = $detail->image_name;			
					$data['product_id'] = $detail->product_id;
					
					$thumbnail = '<img src="'.base_url().'uploads/products/'.$detail->product_id.'/'.$detail->image_name.'" class="img-responsive img-rounded" width="240" height="280" />';
					
					$data['thumbnail'] = $thumbnail;
					
					$data['date_added'] = date("F j, Y", strtotime($detail->date_added));
					
					$data['model'] = 'product_images';
					$data['success'] = true;
					
					
			}else {
				$data['success'] = false;
			}
			
			echo json_encode($data);
			
		}

				
		/**
		* Function to handle display
		* product details
		* 
		*/	
		public function product_details(){
			
			$detail = $this->db->select('*')->from('products')->where('id',$this->input->post('id'))->get()->row();
			
			$id = $this->input->post('id');

			if($detail){

					$data['id'] = $detail->id;
					
					$data['headerTitle'] = ucwords($detail->name);			
					$category = '<select name="product_category" class="form-control">';
					
					$this->db->from('product_categories');
					$this->db->order_by('id');
					$result = $this->db->get();
					if($result->num_rows() > 0) {
						foreach($result->result_array() as $row){
							$default = ($row['category_name'] == $detail->category)?'selected':'';
							$category .= '<option value="'.$row['category_name'].'" '.$default.'>'.$row['category_name'].'</option>';			
						}
					}
				
					$category .= '</select>';
					
					$data['select_category'] = $category;
					
					$select_gender = '<select name="product_category" class="form-control">';
					$select_gender .= '<option value="0" >Select Gender</option>';
					$select_gender .= '<option value="Male" >Male</option>';
					$select_gender .= '<option value="Female" >Female</option>';
					$select_gender .= '</select>';
					
					$data['select_gender'] = $select_gender;
					
					$thumbnail = '';
					$mini_thumbnail = '';
					$filename = FCPATH.'uploads/products/'.$detail->id.'/'.$detail->image;
					
					if(file_exists($filename)){
						$thumbnail = '<img id="img" src="'.base_url().'uploads/products/'.$detail->id.'/'.$detail->id.'.jpg" class="img-responsive img-rounded img-thumbnail" width="280" height="310" />';
						$mini_thumbnail = '<img src="'.base_url().'uploads/products/'.$detail->id.'/'.$detail->id.'.jpg" class="img-responsive img-rounded img-thumbnail" width="140" height="150" />';
					}
					
					else if($detail->image == '' || $detail->image == null){
						$thumbnail = '<img id="img" src="'.base_url().'assets/images/icons/no-default-thumbnail.png" class="img-responsive img-rounded img-thumbnail" width="280" height="310" />';
						$mini_thumbnail = '<img src="'.base_url().'assets/images/icons/no-default-thumbnail.png" class="img-responsive img-rounded img-thumbnail" width="140" height="150" />';
					}
					
					else{
						$thumbnail = '<img id="img" src="'.base_url().'uploads/products/'.$detail->id.'/'.$detail->image.'" class="img-responsive img-rounded img-thumbnail" width="280" height="310" />';
						$mini_thumbnail = '<img src="'.base_url().'uploads/products/'.$detail->id.'/'.$detail->image.'" class="img-responsive img-rounded img-thumbnail" width="140" height="150" />';
					}	
					$data['thumbnail'] = $thumbnail;
					$data['mini_thumbnail'] = $mini_thumbnail;
					
					$product_images = $this->Products->get_product_images($detail->id);
					$count = $this->Products->count_product_images($detail->id);
					$images_list = '<div class="row thumbnail-row">';
					
					if(!empty($product_images)){
						$col = 'col-xs-3';
						if($count > 2 && $count < 4 ){
							$col = 'col-xs-4';
						}
						if($count == 2){
							$col = 'col-xs-2';
						}
						
						foreach($product_images as $images){
							$images_list .= '<div class="'.$col.' nopadding"><img src="'.base_url().'uploads/products/'.$detail->id.'/'.$images->image_name.'" id="'.$images->image_name.'" class="img-responsive img-thumbnail" onclick="changeImage(\''.base_url().'uploads/products/'.$detail->id.'/'.$images->image_name.'\')" width="80" height="90" /></div>';
						}
					}
					$images_list .= '</div>';
					$data['image_row'] = $images_list;
					$data['name'] = ucwords($detail->name);
					$data['category'] = $detail->category;
					
					$gender = $detail->gender;
					if($gender == '' || $gender == '0' || $gender == null){
						$gender = '0';
					}
					$data['gender'] = $gender;
					$data['product_reference'] = $detail->reference_id;
					$data['price'] = number_format($detail->price, 2);
					$data['description'] = stripslashes(wordwrap(nl2br($detail->description), 54, "\n", true));
					
					$quantity_available = $detail->quantity_available;
					
					$available = $quantity_available.' units';
					
					if($quantity_available == 1){
						$available = '1 unit';
					}
					$data['quantity_available'] = $available;
					
					$status = 'In Stock';
					if($detail->quantity_available == 0){
						$status = 'Not in Stock';
					}
					$data['quantity_status'] = $status;
					
					$data['date_added'] = date("F j, Y", strtotime($detail->date_added));
					
					$data['model'] = 'products';
					$data['success'] = true;
					
					
			}else {
				$data['success'] = false;
			}
			
			echo json_encode($data);
			
		}

		
		/***
		* Function to handle products
		*
		***/		
		public function products(){
			
			if(!$this->session->userdata('admin_logged_in')){
				
				$url = 'admin/login?redirectURL='.urlencode(current_url());
				redirect($url);				
				//redirect('admin/login/','refresh');
				
			}else{  
			
				$username = $this->session->userdata('admin_username');	
				
				$data['users'] = $this->Admin->get_user($username);
				
				$data['header_messages_array'] = $this->Admin->get_admin_header_messages();	

				$data['messages_unread'] = $this->Messages->count_unread_messages($username);
				
				$config = array();
				$config["base_url"] = base_url()."admin/products";
				if($this->input->get('search') != ''){
						
						$search = html_escape($this->input->get('search'));
						$data['count'] = $this->Products->count_search_products($search);
						
						$data['display_option'] = 'Showing Results for "<strong><em>'.$search.'</em></strong>" <a href="'.base_url().'admin/products"  >Show All</a>';
						
						$config["total_rows"] = $this->Products->count_search_products($search);
						$config["per_page"] = 10;
						$config["uri_segment"] = 3;
						$choice = $config["total_rows"] / $config["per_page"];
						$config["num_links"] = round($choice);
					
						$this->pagination->initialize($config);
						
						if($this->uri->segment(3) > 0)
							$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
						else
							$offset = $this->uri->segment(3);					
						
						$data['products_array'] = $this->Products->search_products($search, $config["per_page"], $offset);	
						
				}
				else if($this->input->post('show_category') == 'All'){	
					
					$category = '<select name="show_category" id="show_category" class="form-control">';
					$category .= '<option value="All" selected="selected">All</option>';
					
					$this->db->from('product_categories');
					$this->db->order_by('id');
					$result = $this->db->get();
					if($result->num_rows() > 0) {
						foreach($result->result_array() as $row){
							$category .= '<option value="'.$row['category_name'].'">'.$row['category_name'].'</option>';
						}
					}
					$category .= '</select>';
					
					$data['category'] = $category;
					
					$data['products'] = 'products';
					
					$data['display_option'] = '<strong>Showing All</strong>';
						
					$table = 'products';
				
					$config["total_rows"] = $this->Admin->count_all($table);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);

					$this->pagination->initialize($config);
									
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
							
					//call the model function to get the messages data
				   // $data['notification_array'] = $this->bids->get_user_notifications($email_address, $config["per_page"], $data['page']);	
					//call the model function to get the posts data
					$data['products_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	

					$data['count'] = $this->Admin->count_all($table);
				}
				else if($this->input->post('show_category') != ''){
						
						$category_name = html_escape($this->input->post('show_category'));
						$data['count'] = $this->Products->count_products_by_category($category_name);
						
						$category = '<select name="show_category" id="show_category" class="form-control">';
						$category .= '<option value="All">All</option>';
						
						$this->db->from('product_categories');
						$this->db->order_by('id');
						$result = $this->db->get();
						if($result->num_rows() > 0) {
							foreach($result->result_array() as $row){
								$default = ($row['category_name'] == $category_name)?'selected':'';
								$category .= '<option value="'.$row['category_name'].'" '.$default.'>'.$row['category_name'].'</option>';
							}
						}
						$category .= '</select>';
						
						$data['category'] = $category;
						
						$data['products'] = $category_name;
						
						$data['display_option'] = 'Showing Results for "<strong><em>'.$category_name.'</em></strong>" <a href="'.base_url().'admin/products"  >Show All</a>';
						
						$config["total_rows"] = $this->Products->count_products_by_category($category_name);
						$config["per_page"] = 10;
						$config["uri_segment"] = 3;
						$choice = $config["total_rows"] / $config["per_page"];
						$config["num_links"] = round($choice);
					
						$this->pagination->initialize($config);
						
						if($this->uri->segment(3) > 0)
							$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
						else
							$offset = $this->uri->segment(3);					
						
						$data['products_array'] = $this->Products->get_products_by_category($category_name, $config["per_page"], $offset);	
						
				}
				else{	
					
					$category = '<select name="show_category" id="show_category" class="form-control">';
					$category .= '<option value="All" selected="selected">All</option>';
					
					$this->db->from('product_categories');
					$this->db->order_by('id');
					$result = $this->db->get();
					if($result->num_rows() > 0) {
						foreach($result->result_array() as $row){
							$category .= '<option value="'.$row['category_name'].'">'.$row['category_name'].'</option>';
						}
					}
					$category .= '</select>';
					$data['category'] = $category;
					
					$data['products'] = 'products';
					
					$data['display_option'] = '<strong>Showing All</strong>';
						
					$table = 'products';
				
					$config["total_rows"] = $this->Admin->count_all($table);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);

					$this->pagination->initialize($config);
									
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
							
					//call the model function to get the messages data
				   // $data['notification_array'] = $this->bids->get_user_notifications($email_address, $config["per_page"], $data['page']);	
					//call the model function to get the posts data
					$data['products_array'] = $this->Admin->get_all($table, $config["per_page"], $offset);	

					$data['count'] = $this->Admin->count_all($table);
				}	
				$data['pagination'] = $this->pagination->create_links();
				
				//assign page title name
				$data['pageTitle'] = 'Products List';
						
				//assign page title name
				$data['pageID'] = 'products_list';
								
				//load header and page title
				$this->load->view('admin_pages/header', $data);
					
				//load main body
				$this->load->view('admin_pages/products_list_page', $data);	
				
				//load footer
				$this->load->view('admin_pages/footer');
								
			}
		}
		
		
		/**
		* Function to validate add product
		*
		*/			
		public function add_product(){

				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('product_name','Product name','required|trim|xss_clean|is_unique[products.name]');
				$this->form_validation->set_rules('product_category','Product Category','required|trim|xss_clean|callback_category_check');
				$this->form_validation->set_rules('product_gender','Product Gender','required|trim|xss_clean|callback_gender_check');
				$this->form_validation->set_rules('product_price','Product price','required|trim|xss_clean');
				$this->form_validation->set_rules('product_description','Product description','required|trim|xss_clean');
				$this->form_validation->set_rules('product_quantity_available','Product Quantity','trim|xss_clean');
				
				$this->form_validation->set_message('required', '%s cannot be blank!');
				$this->form_validation->set_message('is_unique', 'Duplicate item!');
				
				$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			
				if($this->form_validation->run()){
					
						$product_price = floatval(preg_replace('/[^\d\.]/', '', $this->input->post('product_price')));
						$category_id = '';
						switch ($this->input->post('product_category')) {
						case 'Accessories':
							$category_id = '1';
							break;
						case 'Cologne':
							$category_id = '2';
							break;
						case 'Dresses':
							$category_id = '3';
							break;
						case 'Perfumes':
							$category_id = '2';
							break;
						case 'Shoes':
							$category_id = '4';
							break;
						case 'Shirts':
							$category_id = '5';
							break;
						case 'Pants':
							$category_id = '6';
							break;
						case 'Tops':
							$category_id = '5';
							break;
						case 'Skirts':
							$category_id = '6';
							break;
						default:
							$category_id = '9';
					}
						
						//generate a unique product reference id
						$random_string1 = substr(str_shuffle("0123456789"), 0, 4);
						$random_string2 = substr(str_shuffle("0123456789"), 0, 3);
						
						$reference_id = $category_id .''.$random_string1.'/'.$random_string2;
						
						//ensure the reference number is unique
						while(!$this->Products->is_unique_ref($reference_id)){
							
							$random_string1 = substr(str_shuffle("0123456789"), 0, 4);
							$random_string2 = substr(str_shuffle("0123456789"), 0, 3);
						
							$reference_id = $category_id .''.$random_string1.'/'.$random_string2;
						}
						
						
						
						$add = array(
							'reference_id' => $reference_id,
							'name' => $this->input->post('product_name'),			
							'category' => $this->input->post('product_category'),
							'gender' => $this->input->post('product_gender'),
							'price' => $product_price,
							'description' => $this->input->post('product_description'),
							'quantity_available' => $this->input->post('product_quantity_available'),
							
							'date_added' => date('Y-m-d H:i:s'),
						
						);
						
						//$table = 'users';	
						//$this->Admin->add_to_db($table, $data)
						
						$insert_id = $this->Store->insert_product($add);
						
						if($insert_id){
							
							if(isset($_FILES["product_image"])){
								
								$file_name = '';
								
								$path = './uploads/products/'.$insert_id.'/';
								if(!is_dir($path))
								{
									mkdir($path,0777);
								}
								$config['upload_path'] = $path;
								$config['allowed_types'] = 'gif|jpg|jpeg|png';
								$config['max_size'] = 2048000;
								$config['max_width'] = 3048;
								$config['max_height'] = 2048;
									
								$config['file_name'] = $insert_id.'.jpg';
								
								$this->load->library('upload', $config);	

								$this->upload->overwrite = true;
								if($this->upload->do_upload('product_image')){
							
									$upload_data = $this->upload->data();
										
									if (isset($upload_data['file_name'])){
										$file_name = $upload_data['file_name'];
									}				
								}else{
									$data['upload_error'] = $this->upload->display_errors();
								}
								$image_data = array(
									'image' => $file_name,		
								);
								$this->Products->update_product($image_data,$insert_id);	
							}	
				
							$detail = $this->db->select('*')->from('products')->where('id', $insert_id)->get()->row();	
							$data['id'] = $detail->id;
							$data['name'] = $detail->name;
							$data['category'] = $detail->category;
							$data['gender'] = $detail->gender;
							$data['price'] = $detail->price;
							$data['quantity_available'] = $detail->quantity_available;
							$data['date_added'] = date("F j, Y", strtotime($detail->date_added));
							
							//prepare buttons
							$data['editButton'] = '<a data-toggle="modal" data-target="#editModal" class="btn btn-warning edit_product"  id="'.$detail->id.'" title="Click to Edit"><i class="fa fa-pencil"></i></a>';
							$data['deleteButton'] = '<a data-toggle="modal" data-target="#deleteModal" class="btn btn-danger delete_product"  id="'.$detail->id.'" title="Click to Delete"><i class="fa fa-trash"></i></a>';
							
							$this->session->set_flashdata('product_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> A new product has been added!</div>');
							$data['success'] = true;
							$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Product has been added!</div>';

							//update complete redirects to success page
							//redirect('admin/users');							
						}else{
							$this->session->set_flashdata('product_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> The product has not been added!</div>');
							$data['success'] = false;
							$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Product not added!</div>';
		
							//update complete redirects to success page
							//redirect('admin/users');							
						}				
				}
				else {
					
					$data['success'] = false;
					$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Product not added!'.validation_errors().'</div>';
					
					//$this->add_user();	
				}

			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			//echo json_encode($data);			
		}
		
		
		/**
		* Function to validate update product 
		* form
		*/			
		public function update_product(){
				
			if(isset($_FILES["new_product_image"])){
					
				$product_id = $this->input->post('productID');
				//$upload = false;
					
				$path = './uploads/products/'.$product_id.'/';
				
				if(!is_dir($path)){
					mkdir($path,0777);
				}
				
				$config['upload_path'] = $path;
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['max_size'] = 2048000;
				$config['max_width'] = 3048;
				$config['max_height'] = 2048;
						
				$config['file_name'] = $product_id.'.jpg';
					
				$this->load->library('upload', $config);	

				$this->upload->overwrite = true;
										
			}
				
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('product_name','Product Name','required|trim|xss_clean');
			$this->form_validation->set_rules('product_category','Product Category','required|trim|xss_clean|callback_category_check');
			$this->form_validation->set_rules('product_gender','Product Gender','required|trim|xss_clean|callback_gender_check');
			$this->form_validation->set_rules('product_price','Product Price','required|trim|xss_clean');
			$this->form_validation->set_rules('product_description','Product Description','required|trim|xss_clean');
			$this->form_validation->set_rules('product_quantity_available','Product Quantity','trim|xss_clean');
				
			$this->form_validation->set_message('required', '%s cannot be blank!');
				
			if ($this->form_validation->run()){
				
				$product = $this->Products->get_product($product_id);
				$new_product_image = '';
				
				if($this->upload->do_upload('new_product_image')){
						
					$upload_data = $this->upload->data();
						
					$file_name = '';
					if (isset($upload_data['file_name'])){
						$file_name = $upload_data['file_name'];
					}
					$new_product_image = $file_name;				
				}else{
					if($this->upload->display_errors()){
						$data['upload_error'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> There are errors with the photo!<br/>'.$this->upload->display_errors().'</div>';

					}
					//$data['upload_error'] = $this->upload->display_errors();
					foreach($product as $p){
						$new_product_image = $p->image;
					}
				}	
				
				$product_price = floatval(preg_replace('/[^\d\.]/', '', $this->input->post('product_price')));
				
				
				$update = array(
					'image' => $new_product_image,
					'name' => $this->input->post('product_name'),
					'category' => $this->input->post('product_category'),
					'gender' => $this->input->post('product_gender'),
					'price' => $product_price,
					'description' => $this->input->post('product_description'),
					'quantity_available' => $this->input->post('product_quantity_available'),
				);
				
				if ($this->Products->update_product($update, $product_id)){	
				
					$this->session->set_flashdata('product_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Product updated!</div>');
					$data['success'] = true;
					$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Product has been updated!</div>';
				}
				
			}else {
				$data['success'] = false;
				$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> There are errors on the form!'.validation_errors().'</div>';
			}
			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			//echo json_encode($data);			
		}
		

		/**
		* Function to ensure a question is selected 
		* 
		*/			
		public function category_check(){
			
			$product_category = $this->input->post('product_category');
			
			if($product_category == '0'){
				$this->form_validation->set_message('category_check', 'Please select at least one category!');					
				return FALSE;
			}
			
			return TRUE;
		
		}	

		/**
		* Function to ensure a question is selected 
		* 
		*/			
		public function gender_check(){
			
			$product_gender = $this->input->post('product_gender');
			
			if($product_gender == '0'){
				$this->form_validation->set_message('gender_check', 'Please select a gender!');					
				return FALSE;
			}
			
			return TRUE;
		
		}	
		
		
		/**
		* Function to upload multiple images for product 
		* 
		*/			
		public function upload_product_images(){
				
			if(isset($_FILES["images"])){
				
				$append = '';
				$name_array = array();
				$error_array = array();
				$upload_count = '';
				
				$count = count($_FILES['images']['size']);	
				$product_id = $this->input->post('prod_id');
				//$existing_images_count = $this->db->where('product_id', $product_id)->get('product_images')->num_rows();
				$existing_images_count = $this->db->where('product_id', $product_id)->count_all('product_images');
				
				if($existing_images_count == '' || $existing_images_count == 0){
					$append = 1;
				}else{
					$append = $existing_images_count + 1;
				}
				//$upload = false;
				foreach($_FILES as $key=>$value){
					
					for($s=0; $s<=$count-1; $s++) {
						
						
						
						$_FILES['images']['name']=$value['name'][$s];
						$_FILES['images']['type'] = $value['type'][$s];
						$_FILES['images']['tmp_name'] = $value['tmp_name'][$s];
						$_FILES['images']['error'] = $value['error'][$s];
						$_FILES['images']['size'] = $value['size'][$s]; 	
						
						//ensure only files with input are processed
						if ($_FILES['images']['size'] > 0) {
							
							$config['upload_path'] = './uploads/products/'.$product_id.'/';
							$config['allowed_types'] = 'gif|jpg|jpeg|png';
							$config['max_size'] = 2048000;
							$config['max_width'] = 3048;
							$config['max_height'] = 2048;
							$ext = $append + $s;
							$config['file_name'] = $product_id.'_'.$ext.'.jpg';
							$append++;
							
							$this->load->library('upload', $config);	
							
							if($this->upload->do_upload('images')){
									
								$upload_data = $this->upload->data();
									
								$file_name = '';
								if (isset($upload_data['file_name'])){
									$file_name = $upload_data['file_name'];
								}
								
								$db_data = array(
									'product_id' => $product_id,
									'image_name'=> $file_name,
									'date_added' => date('Y-m-d H:i:s'), 
									);
								$this->db->insert('product_images',$db_data);				
							}else{
								if($this->upload->display_errors()){
									$error_array[] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> There are errors with the photo!<br/>'.$this->upload->display_errors().'</div>';

								}
							}
							
						}
						
						
					}
				}	
				$errors= implode(',', $error_array);
				if($errors != ''){
					$this->session->set_flashdata('image_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Image errors!</div>');
					$data['success'] = false;
					$data['notif'] = '<div class="alert alert-danger text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$errors.'</div>';
				
				}else{
					$this->session->set_flashdata('image_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Image added!</div>');
					$data['success'] = true;
					$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Image added!</div>';
				}
			}
	
			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			//echo json_encode($data);			
		}
		
				
		
		/**
		* Function to log out user
		*
		*/        
		public function logout() {
			
				$this->session->unset_userdata('admin_logged_in');
				$this->session->unset_userdata('admin_username');
				$this->session->unset_userdata('login_time');
				
				$this->session->sess_destroy();	
				//log out successful, redirects to log in page
				redirect('admin/login');				
		
		}
		
		
}
