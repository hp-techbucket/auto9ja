<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trader extends CI_Controller {

		/**
		* Function to the account
		* index
		*/	
		public function index(){
			$this->dashboard();
		}


		public function login(){
			
			if($this->session->userdata('trader_logged_in')){
					
					//user already logged in, redirects to account page
					redirect('trader/dashboard');
			}	
			else {
					
					if($this->input->get('redirectURL') != ''){
						$url = $this->input->get('redirectURL');
						$this->session->set_flashdata('redirectURL', $url);	
					}
					//assign page title name
					$data['pageTitle'] = 'Trader Login';
					
					//assign page ID
					$data['pageID'] = 'trader_login';
					
					//load main body
					$this->load->view('trader_account_pages/trader_login_page', $data);
			}		

		}

		/**
		* Function to validate login
		*
		*/
        public function login_validation() {
			
            $this->session->keep_flashdata('redirectURL');
			
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
				
            $this->form_validation->set_rules('email_address','Email','required|trim|callback_max_login_attempts|callback_validate_credentials');
            $this->form_validation->set_rules('password','Password','required|trim');
			
			$this->form_validation->set_message('required', '%s cannot be blank!');
            
            if ($this->form_validation->run()){
				
				$data = array(
					'email_address' => $this->input->post('email_address'),
					'trader_logged_in' => 1,
					
				);
				$this->session->set_userdata($data);

				if($this->Traders->check_isset_security_info()){
					
					//first time login redirects to set memorable information page
					redirect('trader/set_security_information');
					
				}else {			
				
					//redirects to account page
					redirect('trader/dashboard');
				}
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
			
			if ($this->Traders->can_log_in()){
			
				$email_address = $this->input->post('email_address');
				
				//check last login time from the logins table
				$last_login = $this->Logins->get_last_login_time($email_address);
				
				//if there is a record then update users record
				//otherwise ignore
				if($last_login){
					foreach($last_login as $login){
						$this->Logins->update_trader_login_time($email_address, $login->login_time);
					}
				}
				
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
		* Function to check if the user has logged in wrongly
		* more than 3 times in 24 hours
		*/			
		public function max_login_attempts(){
			
			$email_address = $this->input->post('email_address');
			
			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			$min_date = strtotime("-1 day", $date);
			
			$max_date = date('Y-m-d H:i:s', time());
			$min_date = date('Y-m-d H:i:s', $min_date);
			
			$this->db->select('*');
			$this->db->from('failed_logins');
			$this->db->where('email_address', $email_address);
			
			$this->db->where("attempt_time BETWEEN '$min_date' AND '$max_date'", NULL, FALSE);

			$query = $this->db->get();
			
			if ($query->num_rows() < 3){
				
				return TRUE;
				
			}else {
				
				$this->form_validation->set_message('max_login_attempts', 'You have surpassed the allowed number of login attempts in 24 hours! Please contact Customer Service!');
				
				return FALSE;
			}
			
		}
		

		
		
		/**
		* Function to access user account
		* landing page / dashboard
		*/		
		public function dashboard(){
			 
			if($this->session->userdata('trader_logged_in')){ 
				
				//check if redirect url is set
				$redirect = '';
				if($this->session->flashdata('redirectURL')){
					$redirect = $this->session->flashdata('redirectURL');
					//redirect user to previous url
					//$url = 'trader/'.$redirect;
					redirect($redirect);
				}
				
				$email_address = $this->session->userdata('email_address');
				
				//get users details
				$data['users'] = $this->Traders->get_trader($email_address);
				
						
				//GET USER ACCOUNT ACTIATION
				//STATUS
				$activation_status = '';
				$activation = '';
				$last_login =  '';
				
				foreach($data['users'] as $user){
					
					$activation_status = $user->activation_status;
					$last_login = $user->last_login;
				}
				
				if($last_login == '0000-00-00 00:00:00' || $last_login == ''){ 
					$last_login = 'Never'; 
				}else{ 
					$last_login = date("F j, Y, g:i a", strtotime($last_login)); 
				} 
				$data['last_login'] = $last_login;
				
				if($activation_status == '0'){
					$activation = '
								<div class="alert alert-danger">
									<div class="row">
										<div class="col-sm-1">
											<i class="fa fa-exclamation-triangle fa-4x" aria-hidden="true"></i>
										</div>
										<div class="col-sm-11">
										<h4 class="text-danger">
											Your Account is inactive
										</h4><p>You cannot list any vehicles at this time, please contact <a title="Send Message to Customer Support" data-toggle="modal" data-target="#newMessageModal" style="cursor:pointer; ">Customer Support</a></p>
										</div>
									</div>
								</div>';
				}
				$data['activation'] = $activation;
				
				//get unread messages
				$data['header_messages_array'] = $this->Messages->get_header_messages();
				
				//set payments count
				$count_payments = $this->Invoices->count_payments($email_address);
				if($count_payments == '' || $count_payments == null){
					$count_payments = 0;
				}
				$data['payments_count'] = $count_payments;
				
				//GET COUNT OF UNREAD MESSAGES
				$messages_unread = $this->Messages->count_unread_messages($email_address);
				
				if($messages_unread == '' || $messages_unread == null){
					$messages_unread = 0;
				}
				$data['messages_unread'] = $messages_unread;
				
				//***********USER PROFILE COMPLETION*************//
				$percentage_completion = $this->Traders->profile_completion($email_address);
				
				$profile_completion = '';
				
				//$data['incomplete_columns'] = $percentage_completion[1];
				
				if($percentage_completion != '100%'){
					$profile_completion = '<h3 align="center">Your profile is '.$percentage_completion.' complete.</h3>
						<div class="row">					
							<div class="col-xs-12" align="center">
								<div class="customPanel text-warning">
									<i class="fa fa-exclamation-circle fa-3x "></i><br/>
									Profile								
								</div>
								
							</div>	
						</div>';
				}
				
				$data['profile_completion'] = $profile_completion;
				//***********END USER PROFILE COMPLETION*************//
				
				$data['activity_group'] = $activity_group;
				
				//assign page title name
				$data['pageTitle'] = 'My Dashboard';
				
				//assign page ID
				$data['pageID'] = 'dashboard';
							
				//load header
				$this->load->view('trader_account_pages/header', $data);
				
				//load main body
				$this->load->view('trader_account_pages/trader_dashboard_page', $data);
				
				//load main footer
				$this->load->view('pages/footer');				
				
			}
			else {
					$url = 'trader/login?redirectURL='.urlencode(current_url());
					redirect($url);
					//redirect('home/login/?redirectURL=dashboard');
			} 		
		}
		
	
		
		public function profile(){
			
			if($this->session->userdata('trader_logged_in')){
			
				$email_address = $this->session->userdata('email_address');
				
				//get users details
				$data['users'] = $this->Customers->get_customer($email_address);
				
				$first_name = '';
				$last_name = '';
				$security_question = '';
				$security_answer = '';
				$country_name = '';
				$state_name = '';
				$city_name = '';
				
				$day_value = '';
				$month_value = '';
				$year_value = '';
				
				foreach($data['users'] as $user){
					
					$first_name = $user->first_name;
					$last_name = $user->last_name;
					$country_name = $user->country;
					$state_name = $user->state;
					$city_name = $user->city;
					$security_question = $user->security_question;
					$security_answer = $user->security_answer;
					
					//path to user photo folder				
					$photoPath = FCPATH.'uploads/users/b/'.$user->id.'/'.$user->profile_photo;
					
					//path to user banner folder
					$bannerPath = FCPATH.'uploads/users/b/'.$user->id.'/'.$user->banner_photo;
					
					if(!file_exists($bannerPath)){
						//no record in db, diplay default avatar
						$bannerPath = '<img src="'.base_url().'assets/images/backgrounds/header-placeholder.png" width="100%" height="100%" id="header_banner" class="img-responsive" />';
					}
					//check for record of user photo in db
					if($user->profile_photo == '' || $user->profile_photo == null){
						//no record in db, diplay default avatar
						$data['thumbnail'] = '<img src="'.base_url().'assets/images/icons/avatar.jpg" width="50" height="50" />';
					}
					//check if record in db is url thus facebook
					elseif(filter_var($user->profile_photo, FILTER_VALIDATE_URL)){
						//diplay facebook avatar
						$data['thumbnail'] = '<img src="'.$user->profile_photo.'" class="fb_profile" width="50" height="50" />';
					}
					//check if folder exists in website
					elseif(!file_exists($photoPath)){
						//no record in db, diplay default avatar
						$data['thumbnail'] = '<img src="'.base_url().'assets/images/icons/avatar.jpg" width="50" height="50" />';
					}
					//diplay uploaded avatar
					else{
						$data['thumbnail'] = '<img src="'.base_url().'uploads/users/b/'.$user->id.'/'.$user->profile_photo.'" width="130" height="125" />';
					}	

					
					if($user->last_login == '0000-00-00 00:00:00'){
						$data['last_login'] = 'Never';
					}else{
						$data['last_login'] = date('F j, Y',strtotime($user->last_login)) .' ('.$this->Customers->time_elapsed_string(strtotime($user->last_login)).')';
					}
					
					if($user->banner_photo == '' || $user->banner_photo == null || !file_exists($bannerPath)){			
						$data['banner'] = '<img src="'.base_url().'assets/images/backgrounds/header-placeholder.png" width="100%" height="100%" id="header_banner" class="img-responsive" />';
					}else{
						$data['banner'] = '<img src="'.base_url().'uploads/users/b/'.$user->id.'/'.$user->banner_photo.'" width="100%" height="100%" id="header_banner" class="img-responsive" />';			
					} 
					
					if($user->birthday != '0000-00-00') { 
					
						$day_value = date("d",strtotime($user->birthday));
						$month_value = date("m",strtotime($user->birthday));
						$year_value = date("Y",strtotime($user->birthday));
					
					}
					
				}
				//obtain facebook details if they exist		
				/*$fbuser = $this->facebook->getUser();
				
				if ($fbuser) {
					$data['user_profile'] = $this->facebook->api('/me?fields=id');
					$fb_user_id = $data['user_profile']['id'];
					$data['thumbnail'] = '<img src="https://graph.facebook.com/'.$fb_user_id.'/picture" class="fb_profile" width="50" height="50" />';
				}
				*/
				$security_questions = '';
				$this->db->from('security_questions');
				$this->db->order_by('id');
				$result = $this->db->get();
				if($result->num_rows() > 0) {
					foreach($result->result_array() as $row){
						$default = ($row['question'] == $security_question)?'selected':'';
						$security_questions .= '<option value="'.$row['question'].'" '.$default.'>'.$row['question'].'</option>';			
					}
				}
				 
				//get watchlist count
				$watchlist_count = $this->Watchlist->count_watchlist($email_address);
				
				if($watchlist_count == '' || $watchlist_count == null){
					$watchlist_count = 0;
				}
				$data['watchlist_count'] = $watchlist_count;
				
				//get shipping status count
				$shipping_count = $this->Shipping_status->count_shipping($email_address);
				
				if($shipping_count == '' || $shipping_count == null){
					$shipping_count = 0;
				}
				$data['shipping_count'] = $shipping_count;
				
				//get unread messages
				$data['header_messages_array'] = $this->Messages->get_header_messages();
				
				//set saved searches count
				$data['saved_searches_count'] = 0;
				
				//set payments count
				$count_payments = $this->Invoices->count_payments($email_address);
				if($count_payments == '' || $count_payments == null){
					$count_payments = 0;
				}
				$data['payments_count'] = $count_payments;
				
				
				//display pending payments count 
				$pending_payments_count = $this->Invoices->count_pending_payments($email_address);
				if($pending_payments_count == '' || $pending_payments_count == null){
					$pending_payments_count = 0;
				}
				$data['pending_payments_count'] = $pending_payments_count;
				
				
				//***********USER PROFILE COMPLETION*************//
				$percentage_completion = $this->Customers->profile_completion($email_address);
				
				$profile_completion = '';
				
				//$data['incomplete_columns'] = $percentage_completion[1];
				
				if($percentage_completion[0] == '100%'){
					/*$profile_completion = '<h3 align="center">Your profile is '.$percentage_completion[0].' complete.</h3>
						<div class="row">					
							<div class="col-xs-12" align="center">
								<div class="customPanel">
									<i class="fa fa-check fa-3x lemonColour"></i><br/>
									Profile
								</div>
								
							</div>	
						</div>';*/
						$profile_completion = '';
				}else{
					$profile_completion = '<h3 align="center">Your profile is '.$percentage_completion[0].' complete.</h3>
						<div class="row">					
							<div class="col-xs-12" align="center">
								<div class="customPanel text-warning">
									<i class="fa fa-exclamation-circle fa-3x "></i><br/>
									Profile								
								</div>
								
							</div>	
						</div>
						<h3 align="center">
								'.$percentage_completion[1].'
								</h3>';
				}
				
				$data['profile_completion'] = $profile_completion;
				//***********END USER PROFILE COMPLETION*************//
				
				//set cart count
				$data['cart_count'] = 0;
					
				//check if user has started shopping
				//update cart accordingly
				if($this->session->userdata('cart_array')){ 
					
					//obtain the shopping session id
					$session_id = $this->session->userdata('cart_array');
						
					$cart_count = $this->Cart->count_cart($session_id);
					if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
					}
					$data['cart_count'] = $cart_count;
						
				}	
				
				//***********COUNTRY, STATE AND CITY*************//
				//country list dropdown
				$country_options = '<select name="country" id="customer_country">';
				$country_options .= '<option value="0" selected="selected">Select Country</option>';
				
				$this->db->from('countries');
				$this->db->order_by('id');
				$result = $this->db->get();
				if($result->num_rows() > 0) {
					foreach($result->result_array() as $row){
						$default_country = ($row['name'] == $country_name)?'selected':'';
						$country_options .= '<option value="'.$row['id'].'" '.$default_country.'>'.$row['name'].'</option>';			
					}
				}
				$country_options .= '</select>';
				$data['country_options'] = $country_options;
				
				//get state ID
				$state_id = '';
				$states = $this->db->select('id')->from('states')->where('name', $state_name)->get()->row();

				if(!empty($states)){
					$state_id = $states->id;
				}
				$state_options = '<select name="state" id="customer_state">';
				
				if($state_name == '' || $state_name == null){
					$state_options .= '<option value="0" selected="selected">Select State</option>';
				}else{
					$state_options .= '<option value="'.$state_id.'" selected="'.$state_name.'">'.$state_name.'</option>';
				}
				$state_options .= '</select>';
				$data['state_options'] = $state_options;
				
				//get city ID
				$city_id = '';
				$cities = $this->db->select('id')->from('cities')->where('name', $city_name)->get()->row();

				if(!empty($cities)){
					$city_id = $cities->id;
				}
				$city_options = '<select name="city" id="customer_city">';
				
				if($city_name == '' || $city_name == null){
					$city_options .= '<option value="0" selected="selected">Select City</option>';
				}else{
					$city_options .= '<option value="'.$city_id.'" selected="'.$city_name.'">'.$city_name.'</option>';
				}
				$city_options .= '</select>';
				$data['city_options'] = $city_options;
				
				//***********DATE OF BIRTH*************//
				//dob year
				$birth_year = '<select name="year" id="dob-year">';
				
				for($i=date("Y")-50;$i<=date("Y");$i++) {
					$sel = ($i == $year_value) ? 'selected' : '';
					$birth_year .= "<option value=".$i." ".$sel.">".$i."</option>";  
				}
				$birth_year .= '</select>';
				$data['birth_year'] = $birth_year;
				
				//dob month select
				$birth_month = '<select name="month" id="dob-month">';
				
				for($month = 1; $month <= 12; $month++){
					$default_month = ($month == $month_value)?'selected':'';
					$birth_month .= '<option value="'.sprintf("%02d", $month).'" '.$default_month.'>'.sprintf("%02d", $month).'</option>';
				}
				$birth_month .= '</select>';
				$data['birth_month'] = $birth_month;
				
				//dob day select
				$birth_day = '<select name="day" id="dob-day">';
				
				for($day = 1; $day <= 31; $day++){
					$default_day = ($day == $day_value)?'selected':'';
					$birth_day .= '<option value="'.sprintf("%02d", $day).'" '.$default_day.'>'.sprintf("%02d", $day).'</option>';
				}
				$birth_day .= '</select>';
				$data['birth_day'] = $birth_day;
				
				//***********END DATE OF BIRTH*************//
				
				//get unread messages count
				$messages_unread = $this->Messages->count_unread_messages($email_address);
				
				if($messages_unread == '' || $messages_unread == null){
					$messages_unread = 0;
				}
				$data['messages_unread'] = $messages_unread;
				
				//get list of security questions
				//and user's set question and answer
				$data['list_of_questions'] =  $this->Security_questions->get_security_questions();
				$data['security_questions'] =  $security_questions;
				$data['security_answer'] =  $security_answer;
			
				
				//assign additional breadcrumb
				$data['breadcrumb'] = '<li><a href="javascript:void(0)" onclick="location.href=\''.base_url('trader/settings/').'\'" title="Private Inbox" ><i class="glyphicon glyphicon-cog"></i> Settings</a></li>';
				
				$data['page_breadcrumb'] = '<i class="fa fa-user"></i> Profile';
				
				//assign page title name
				$data['pageTitle'] = 'Profile';
				
				//assign page ID
				$data['pageID'] = 'customer_profile';
										
				//load header
				$this->load->view('trader_account_pages/header', $data);
					
				//load main body
				$this->load->view('trader_account_pages/user_profile_page', $data);  			

				//load main footer
				$this->load->view('pages/footer');				
				
			}else {
				//user not logged in, redirects to login page
				$url = 'trader/login?redirectURL='.urlencode(current_url());
				redirect($url);
									
				//redirect('home/login/?redirectURL=profile');
			}			
			
		}	


		/**
		* Function to validate update_profile 
		* information
		*/		
		public function update_profile(){
			
			if($this->session->userdata('trader_logged_in')){ 
				
				$email_address = $this->session->userdata('email_address');
				
				$user_id = $this->input->post('user_id');
				
				if(isset($_FILES["upload_photo"])){

					$path = './uploads/users/b/'.$user_id.'/';
					
					if(!is_dir($path)){
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
				
				$this->form_validation->set_rules('tagline','Tagline','trim|xss_clean');
				$this->form_validation->set_rules('address','Address','trim|xss_clean');
				$this->form_validation->set_rules('city','City','trim|xss_clean|callback_validate_city');
				$this->form_validation->set_rules('postcode','Postcode','trim|xss_clean');
				$this->form_validation->set_rules('state','State','trim|xss_clean|callback_validate_state');
				$this->form_validation->set_rules('country','Country','trim|xss_clean|callback_validate_country');
				
				$this->form_validation->set_rules('mobile','Mobile','trim|xss_clean|regex_match[/^[0-9\+\(\)\/-]+$/]');
				$this->form_validation->set_rules('day','Day','trim|numeric|exact_length[2]');
				$this->form_validation->set_rules('month','Month','trim|numeric|exact_length[2]');
				$this->form_validation->set_rules('year','Year','trim|numeric|exact_length[4]');

				$this->form_validation->set_rules('description','Profile Description','trim|xss_clean');
					
				$this->form_validation->set_message('required', '%s cannot be blank!');
				$this->form_validation->set_message('numeric', '%s must be digits!');
				$this->form_validation->set_message('regex_match', 'Please enter a valid phone number!');
				$this->form_validation->set_message('exact_length', '%s must be 2 digits!');
					
				if ($this->form_validation->run()){
					
					//get users details
					$user = $this->Customers->get_customer($email_address);
				
					$profile_photo = '';
						
					if($this->upload->do_upload('upload_photo')){
								
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
						
					$birthday = $this->input->post('year').'-'.$this->input->post('month').'-'.$this->input->post('day');
					
					$country_name = $this->Countries->get_country($this->input->post('country'));
					$state_name = $this->Countries->get_state($this->input->post('state'));
					$city_name = $this->Countries->get_city($this->input->post('city'));
							
					$data = array(
						'profile_photo' => $profile_photo,				
						'tagline' => $this->input->post('tagline'),
						'address' => $this->input->post('address'),
						'city' => $city_name,
						'postcode' => $this->input->post('postcode'),
						'state' => $state_name,
						'country' => $country_name,
						
						'mobile' => $this->input->post('mobile'),
						'birthday' => $birthday,
						'profile_description' => $this->input->post('description'),
										
					);
					
					if ($this->Customers->update_customer($data)){
							
						//update activities table
						$description = 'updated profile';
				
						$activity = array(			
							'user_email' => $email_address,
							'description' => $description,
							'keyword' => 'Profile',
							'activity_time' => date('Y-m-d H:i:s'),
						);
						
						$this->Site_activities->insert_activity($activity);
									
						$this->session->set_flashdata('profile_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Your profile has been updated!</div>');
						
						$first_name = '';
							
						foreach($user as $u){
								
							$first_name = $u->first_name;
						}
								
						//send email
						$ci = get_instance();
						$ci->load->library('email');
							
						$config['protocol'] = "smtp";
						$config['validate'] = 'FALSE';
						$config['smtp_host'] = "ssl://cp-in-1.webhostbox.net";
						$config['smtp_port'] = "465";
						$config['smtp_user'] = "getextra@global-sub.com"; 
						$config['smtp_pass'] = "1234567";
						$config['charset'] = "utf-8";
						$config['mailtype'] = "html";
						$config['newline'] = "\r\n";

						$ci->email->initialize($config);

						//setup email function
						$ci->email->from('getextra@global-sub.com', 'Auto9ja');
						$ci->email->to($email_address);
						$this->email->reply_to('getextra@gmail.com', 'Auto9ja');
						$ci->email->subject('Updated Profile');
							
							
						//compose email message
						$message = "<div style='font-size: 1.0em; border: 1px solid #D0D0D0; border-radius: 3px; margin: 5px; padding: 10px; '>";
						$message .= '<div align="center" id="logo"><a href="'.base_url().'" title="Auto9ja">'.img('assets/images/logo/logo2.png').'</a></div><br/>';
							
						$message = '<p>Hello '.$first_name. ',</p>';
						$message .= '<p>Your account has been updated</p>';
						$message .= '<p>If you did not make this change. Please contact us immediately.</p>';
						$message .= "</div>";
							
						$ci->email->message($message);
							
						$ci->email->send();
							
						//update complete redirects to account page
						redirect('trader/profile');	
							
					}else {
						$this->profile();
					}
				}else{
					$this->profile();
				}
			}
			else {
					redirect('login');
			} 	
		}
		

		/**
		* Function to validate city 
		* selected
		*/			
		public function validate_city(){
			
			//obtain posted value
			$city = $this->input->post('city');
			
			//check if selected or default
			if ($city == '0')
			{
				//no city selected
				$this->form_validation->set_message('validate_city', 'Please select a city!');
				return FALSE;
			}
			else{
				return TRUE;
			}
		}	

		/**
		* Function to validate state 
		* selected
		*/			
		public function validate_state(){
			
			//obtain posted value
			$state = $this->input->post('state');
			
			//check if selected or default
			if ($state == '0')
			{
				//no state selected
				$this->form_validation->set_message('validate_state', 'Please select a state!');
				return FALSE;
			}
			else{
				return TRUE;
			}
		}	

		/**
		* Function to validate country 
		* selected
		*/			
		public function validate_country(){
			
			//obtain posted value
			$country = $this->input->post('country');
			
			//check if selected or default
			if ($country == '0')
			{
				//no country selected
				$this->form_validation->set_message('validate_country', 'Please select a country!');
				return FALSE;
			}
			else{
				return TRUE;
			}
		}	

	
		
		public function settings(){
			
			if($this->session->userdata('trader_logged_in')){
			
				$email_address = $this->session->userdata('email_address');
				
				//get users details
				$data['users'] = $this->Customers->get_customer($email_address);

				$security_question = '';
				$security_answer = '';

				foreach($data['users'] as $user){
	
					$security_question = $user->security_question;
					$security_answer = $user->security_answer;
			
				}
				
				$security_questions = '';
				$this->db->from('security_questions');
				$this->db->order_by('id');
				$result = $this->db->get();
				if($result->num_rows() > 0) {
					foreach($result->result_array() as $row){
						$default = ($row['question'] == $security_question)?'selected':'';
						$security_questions .= '<option value="'.$row['question'].'" '.$default.'>'.$row['question'].'</option>';			
					}
				}
				 
				//get watchlist count
				$watchlist_count = $this->Watchlist->count_watchlist($email_address);
				
				if($watchlist_count == '' || $watchlist_count == null){
					$watchlist_count = 0;
				}
				$data['watchlist_count'] = $watchlist_count;
				
				//get shipping status count
				$shipping_count = $this->Shipping_status->count_shipping($email_address);
				
				if($shipping_count == '' || $shipping_count == null){
					$shipping_count = 0;
				}
				$data['shipping_count'] = $shipping_count;
				
				//get unread messages
				$data['header_messages_array'] = $this->Messages->get_header_messages();
				
				//set saved searches count
				$data['saved_searches_count'] = 0;
				
				//set payments count
				$count_payments = $this->Invoices->count_payments($email_address);
				if($count_payments == '' || $count_payments == null){
					$count_payments = 0;
				}
				$data['payments_count'] = $count_payments;
				
				
				//display pending payments count 
				$pending_payments_count = $this->Invoices->count_pending_payments($email_address);
				if($pending_payments_count == '' || $pending_payments_count == null){
					$pending_payments_count = 0;
				}
				$data['pending_payments_count'] = $pending_payments_count;
				
				
				//set cart count
				$data['cart_count'] = 0;
					
				//check if user has started shopping
				//update cart accordingly
				if($this->session->userdata('cart_array')){ 
					
					//obtain the shopping session id
					$session_id = $this->session->userdata('cart_array');
						
					$cart_count = $this->Cart->count_cart($session_id);
					if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
					}
					$data['cart_count'] = $cart_count;
						
				}	
				
				//get unread messages count
				$messages_unread = $this->Messages->count_unread_messages($email_address);
				
				if($messages_unread == '' || $messages_unread == null){
					$messages_unread = 0;
				}
				$data['messages_unread'] = $messages_unread;
				
				//get list of security questions
				//and user's set question and answer
				$data['list_of_questions'] =  $this->Security_questions->get_security_questions();
				$data['security_questions'] =  $security_questions;
				$data['security_answer'] =  $security_answer;
			
				
				//assign additional breadcrumb
				$data['breadcrumb'] = '<li><a href="javascript:void(0)" onclick="location.href=\''.base_url('trader/profile/').'\'" title="Private Inbox" ><i class="fa fa-user"></i> Profile</a></li>';
				
				$data['page_breadcrumb'] = '<i class="glyphicon glyphicon-cog"></i> Settings';
				
				//assign page title name
				$data['pageTitle'] = 'Settings';
				
				//assign page ID
				$data['pageID'] = 'settings';
										
				//load header
				$this->load->view('trader_account_pages/header', $data);
					
				//load main body
				$this->load->view('trader_account_pages/account_settings_page', $data);  			

				//load main footer
				$this->load->view('pages/footer');				
				
			}else {
				//user not logged in, redirects to login page
				$url = 'trader/login?redirectURL='.urlencode(current_url());
				redirect($url);
									
				//redirect('home/login/?redirectURL=profile');
			}			
			
		}	

		

		/**
		* Function to update password
		*
		*/			
		public function update_password(){
			
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('old_password','Old Password','trim|callback_validate_old_password');
            $this->form_validation->set_rules('new_password','New Password','required|trim|xss_clean');
			$this->form_validation->set_rules('confirm_new_password','Confirm New Password','required|trim|matches[new_password]|xss_clean');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			  
            $this->form_validation->set_message('required', '% cannot be blank!');
			
			if ($this->form_validation->run()){
				
				$email_address = $this->session->userdata('email_address');

				$data = array(
					'password' => md5($this->input->post('new_password')),
				);
						
				if ($this->Customers->update_customer($data)){
					
					//update activities table
					$description = 'changed password';
				
					$activity = array(			
						'user_email' => $email_address,
						'description' => $description,
						'keyword' => 'Security',
						'activity_time' => date('Y-m-d H:i:s'),
					);
						
					$this->Site_activities->insert_activity($activity);
									
					//$url = $this->logout();
					
					$this->session->set_flashdata('password_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000); setTimeout(function() { window.location = "'.base_url('trader/logout').'"; }, 9000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Your password has been updated! You will be redirected shortly to login again for security reasons.</div>');
					
					$user = $this->Customers->get_customer($email_address);

					$first_name = '';
						
					foreach($user as $u){
						$first_name = $u->first_name;
					}
							
					//send email
					$ci = get_instance();
					$ci->load->library('email');
						
					$config['protocol'] = "smtp";
					$config['validate'] = 'FALSE';
					$config['smtp_host'] = "ssl://cp-in-1.webhostbox.net";
					$config['smtp_port'] = "465";
					$config['smtp_user'] = "getextra@global-sub.com"; 
					$config['smtp_pass'] = "1234567";
					$config['charset'] = "utf-8";
					$config['mailtype'] = "html";
					$config['newline'] = "\r\n";

					$ci->email->initialize($config);

					//setup email function
					$ci->email->from('getextra@global-sub.com', 'Auto9ja');
					$ci->email->to($email_address);
					$this->email->reply_to('getextra@gmail.com', 'Auto9ja');
					$ci->email->subject('Password change');
						
						
					//compose email message
					$message = "<div style='font-size: 1.0em; border: 1px solid #D0D0D0; border-radius: 3px; margin: 5px; padding: 10px; '>";
					$message .= '<div align="center" id="logo"><a href="'.base_url().'" title="Auto9ja">'.img('assets/images/logo/logo2.png').'</a></div><br/>';
					
					$message .= "<p>Hello ";
					$message .= $first_name. ",</p>";
					$message .= "<p>Your password has been updated.</p>";
					$message .= "<p>If you did not make this change. Please contact us immediately.</p>";
					$message .= "</div>";
						
					$ci->email->message($message);
						
					$ci->email->send();
						
						//update complete redirects to update details success page
						redirect('trader/profile');
						
				}else{
					redirect('trader/profile');
				}
			}else {
				$this->profile();
			}
		}
		

		/**
		* Function to validate_old_password 
		* 
		*/			
		public function validate_old_password(){
			
			//get users email from session
			$email_address = $this->session->userdata('email_address');
			
			//get users details
			$user = $this->Customers->get_customer($email_address);
			
			//initiate variable
			$password = '';
			//obtain posted password
			$post_password = md5($this->input->post('old_password'));
			
			//get password on record			
			foreach($user as $u){		
				$password = $u->password;
			}
			//compare passwords
			if ($post_password != $password)
			{
				//passwords don't match
				$this->form_validation->set_message('validate_old_password', 'Old Password doesn\'t match what we have on record!');
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}	
		
		
		/**
		* Function to validate update memorable information
		*
		*/			
		public function update_security(){
			
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('security_question','Security Question','trim|callback_question_option_check');
            $this->form_validation->set_rules('security_answer','Security Answer','required|trim|xss_clean');

            $this->form_validation->set_message('required', 'An answer is required!');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			   
			if ($this->form_validation->run()){

				//get users email from session
				$email_address = $this->session->userdata('email_address');
				
				//obtain post values
				$data = array(
					'security_question' => $this->input->post('security_question'),
					'security_answer' => $this->input->post('security_answer'),
				);
				
				//update users security info				
				if ($this->Customers->update_customer($data)){
					//update activities table
					$description = 'changed security information';
				
					$activity = array(			
						'user_email' => $email_address,
						'description' => $description,
						'keyword' => 'Settings',
						'activity_time' => date('Y-m-d H:i:s'),
					);
						
					$this->Site_activities->insert_activity($activity);
									
					//$url = $this->logout();
					$url = base_url('trader/logout');
					
					$this->session->set_flashdata('security_info_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000); </script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Your security information has been updated! You will be redirected shortly to login again for security reasons.</div><script type="text/javascript" language="javascript"> setTimeout(function() { window.location = "'.$url.'"; }, 9000);</script>');
					
					//$this->session->set_flashdata('security_info_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Your security information has been updated! You will be redirected shortly to login again for security reasons.</div>');
					
					//get users details
					$user = $this->Customers->get_customer($email_address);
					
					//initialise variable
					$first_name = '';
					
					//get first name	
					foreach($user as $u){
						$first_name = $u->first_name;
					}
							
					//send email
					$ci = get_instance();
					$ci->load->library('email');
						
					$config['protocol'] = "smtp";
					$config['validate'] = 'FALSE';
					$config['smtp_host'] = "ssl://cp-in-1.webhostbox.net";
					$config['smtp_port'] = "465";
					$config['smtp_user'] = "getextra@global-sub.com"; 
					$config['smtp_pass'] = "1234567";
					$config['charset'] = "utf-8";
					$config['mailtype'] = "html";
					$config['newline'] = "\r\n";

					$ci->email->initialize($config);

					//setup email function
					$ci->email->from('getextra@global-sub.com', 'Auto9ja');
					$ci->email->to($email_address);
					$this->email->reply_to('getextra@gmail.com', 'Auto9ja');
					$ci->email->subject('Changes to your account');
						
						
					//compose email message
					$message = "<div style='font-size: 1.0em; border: 1px solid #D0D0D0; border-radius: 3px; margin: 5px; padding: 10px; '>";
					$message .= '<div align="center" id="logo"><a href="'.base_url().'" title="Auto9ja">'.img('assets/images/logo/logo2.png').'</a></div><br/>';
					
					$message .= "<p>Hello ";
					$message .= $first_name. ",</p>";
					$message .= "<p>Your security information has been updated</p>";
					$message .= "<p>If you did not make this change. Please contact us immediately.</p>";
					$message .= "</div>";
						
					$ci->email->message($message);
						
					$ci->email->send();
						
					//update complete redirects to update details success page
					redirect('trader/profile');	
						
				}else{
					
					redirect('trader/profile');
				}
			}else {
				
				$this->profile();
			}
		}		
		

		/**
		* Function to set security 
		* information
		*/					
		public function set_security_information(){
			
			if($this->session->userdata('trader_logged_in')){
				
				//set cart count
				$data['cart_count'] = 0;
					
				//check if user has started shopping
				//update cart accordingly
				if($this->session->userdata('cart_array')){ 
					
					//obtain the shopping session id
					$session_id = $this->session->userdata('cart_array');
						
					$cart_count = $this->Cart->count_cart($session_id);
					if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
					}
					$data['cart_count'] = $cart_count;
						
				}	
				
				$data['list_of_questions'] =  $this->Security_questions->get_security_questions();
				
				
				//security_questions list dropdown
				$security_questions = '<select name="security_questions" id="security_questions" class="form-control">';
				$security_questions .= '<option value="Select A Question">Select A Question</option>';
				$this->db->from('security_questions');
				$this->db->order_by('id');
				$result = $this->db->get();
				if($result->num_rows() > 0) {
					foreach($result->result_array() as $row){
						$security_questions .= '<option value="'.$row['question'].'">'.$row['question'].'</option>';			
					}
				}
				$security_questions .= '</select>';
				$data['security_questions'] = $security_questions;
				
			
				$email_address = $this->session->userdata('email_address');
				
				$data['users'] = $this->Customers->get_customer($email_address);
				
				$data['header_messages_array'] = $this->Messages->get_header_messages();
				
				$messages_unread = $this->Messages->count_unread_messages($email_address);
				
				if($messages_unread == '' || $messages_unread == null){
					$messages_unread = 0;
				}
				
				$data['messages_unread'] = $messages_unread;
				
				//assign page title name
				$data['pageTitle'] = 'Set Security Information';
				
				//assign page title name
				$data['pageID'] = 'set_security';
				
				//load main header
				$this->load->view('pages/header', $data);				
												
				//load main body
				$this->load->view('trader_account_pages/set_security_information_page', $data);  					
				
				//load main footer
				$this->load->view('pages/footer');				
								
			}else {
				//user not logged in, redirects to login page
				//$this->login();	
				$url = 'trader/login?redirectURL='.urlencode(current_url());
				redirect($url);
									
				//redirect('home/login/?redirectURL=set_security_information');
				
			}
		}
		

		/**
		* Function to validate memorable 
		* information
		*/		
		public function security_info_validation(){
			
            $this->load->library('form_validation');
			
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			$this->form_validation->set_rules('security_questions','Security Question','required|trim|xss_clean');
            $this->form_validation->set_rules('security_answer','Security Answer','required|trim|xss_clean|min_length[3]');
			
			$this->form_validation->set_message('required', 'Security Answer cannot be blank!');
			
			$this->form_validation->set_message('min_length', 'Security answer must be longer than 3 characters!');
            
			if ($this->form_validation->run()){
					
					$email_address = $this->session->userdata('email_address');
					
					$data = array(
						'security_question' => $this->input->post('security_questions'),
						'security_answer' => $this->input->post('security_answer'),
					);
					if ($this->Customers->update_customer($data)){
						
						//update activities table
						$description = 'updated security info';
			
						$activity = array(
								
								'user_email' => $email_address,
								'description' => $description,
								'keyword' => 'Security',
								'activity_time' => date('Y-m-d H:i:s'),
						);
						$this->Site_activities->insert_activity($activity);
						
						
						$this->session->set_flashdata('security_info_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".commentDiv").fadeOut("slow"); }, 5000);</script><div class="commentDiv text-center"><i class="fa fa-check-circle"></i> Your security information has been updated!</div>');
					
						$user = $this->Customers->get_customer($email_address);
						
						$first_name = '';
						
						foreach($user as $u){
							
							$first_name = $u->first_name;
						}
							
						//send email
						$ci = get_instance();
						$ci->load->library('email');
						
						$config['protocol'] = "smtp";
						$config['validate'] = 'FALSE';
						$config['smtp_host'] = "ssl://cp-in-1.webhostbox.net";
						$config['smtp_port'] = "465";
						$config['smtp_user'] = "getextra@global-sub.com"; 
						$config['smtp_pass'] = "1234567";
						$config['charset'] = "utf-8";
						$config['mailtype'] = "html";
						$config['newline'] = "\r\n";

						$ci->email->initialize($config);

						//setup email function
						$ci->email->from('getextra@global-sub.com', 'Auto9ja');
						$ci->email->to($email_address);
						$this->email->reply_to('getextra@gmail.com', 'Auto9ja');
						$ci->email->subject('Changes to your account');
						
						
						//compose email message
						$message = "<div style='font-size: 1.0em; border: 1px solid #D0D0D0; border-radius: 3px; margin: 5px; padding: 10px; '>";
						$message .= '<div align="center" id="logo"><a href="'.base_url().'" title="Auto9ja">'.img('assets/images/logo/logo2.png').'</a></div><br/>';
					
						$message .= "<p>Hello ";
						$message .= $first_name. ",</p>";
						$message .= "<p>Your memorable information has been updated</p>";
						$message .= "<p>If you did not make this change. Please contact us immediately.</p>";
						$message .= "</div>";
						
						$ci->email->message($message);
						
						$ci->email->send();
						
						//update complete redirects to account page
						redirect('trader/dashboard');	
						
					}else {
							$this->set_security_information();
					}
			}else{
				$this->set_security_information();
			}
		}
		

		/**
		* Function to ensure a question is selected 
		* 
		*/			
		public function question_option_check(){
			
			$str1 = $this->input->post('security_question');
			
			if ($str1 == '' )
			{
				$this->form_validation->set_message('question_option_check', 'Please select a question');
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}			
		
	


		/**
		* Function display private messages 
		* 
		*/				
		public function messages(){
			
			if($this->session->userdata('trader_logged_in')){	
				
				redirect('message/inbox');
				
			}else{
				$url = 'trader/login?redirectURL='.urlencode(current_url());
				redirect($url);
									
				//redirect('home/login/?redirectURL=messages');
			}	
		}


		/**
		* Function to display users watchlist
		* 
		*/			
		public function watchlist(){
			
			if($this->session->userdata('trader_logged_in')){
				
				
				//set cart count
				$data['cart_count'] = 0;
					
				//check if user has started shopping
				//update cart accordingly
				if($this->session->userdata('cart_array')){ 
					
					//obtain the shopping session id
					$session_id = $this->session->userdata('cart_array');
						
					$cart_count = $this->Cart->count_cart($session_id);
					if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
					}
					$data['cart_count'] = $cart_count;
						
				}	
				
				$email_address = $this->session->userdata('email_address');
				
				//set payments count
				$count_payments = $this->Invoices->count_payments($email_address);
				if($count_payments == '' || $count_payments == null){
					$count_payments = 0;
				}
				$data['payments_count'] = $count_payments;
				
				//display pending payments count 
				$pending_payments_count = $this->Invoices->count_pending_payments($email_address);
				if($pending_payments_count == '' || $pending_payments_count == null){
					$pending_payments_count = 0;
				}
				$data['pending_payments_count'] = $pending_payments_count;
				
				
				$data['users'] = $this->Customers->get_customer($email_address);
				
				//get watchlist count
				$watchlist_count = $this->Watchlist->count_watchlist($email_address);
				
				if($watchlist_count == '' || $watchlist_count == null){
					$watchlist_count = 0;
				}
				$data['watchlist_count'] = $watchlist_count;
				
				//get shipping status count
				$shipping_count = $this->Shipping_status->count_shipping($email_address);
				
				if($shipping_count == '' || $shipping_count == null){
					$shipping_count = 0;
				}
				$data['shipping_count'] = $shipping_count;
				
				//get unread messages
				$data['header_messages_array'] = $this->Messages->get_header_messages();
				
				//set saved searches count
				$data['saved_searches_count'] = 0;
				
				$messages_unread = $this->Messages->count_unread_messages($email_address);
				
				if($messages_unread == '' || $messages_unread == null){
					$messages_unread = 0;
				}
				
				$data['messages_unread'] = $messages_unread;
				
				$config = array();
				
				
				if($this->input->get('search') != ''){
						
						$search = html_escape($this->input->get('search'));
						// get search string
						$search = html_escape($this->input->get('search'));
						$search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;
						$count = $this->Watchlist->count_search($search, $email_address);
						if($count == '' || $count == null){
							$count = 0;
						}
				
						$data['count'] = $count;
						
						$data['display_option'] = 'Showing Results for "<strong><em>'.$search.'</em></strong>" <a href="'.base_url("trader/statements").'"  >Show All</a>';
						
						$config["base_url"] = base_url("trader/watchlist/$search");
						$config["total_rows"] = $count;
						$config["per_page"] = 10;
						$config["uri_segment"] = 3;
						$choice = $config["total_rows"] / $config["per_page"];
						$config["num_links"] = round($choice);
					
						$this->pagination->initialize($config);
						
						if($this->uri->segment(3) > 0)
							$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
						else
							$offset = $this->uri->segment(3);					
						
						$data['watchlist_array'] = $this->Watchlist->get_search($email_address, $search, $config["per_page"], $offset);	
					
				}else{	
					
					$data['display_option'] = '<strong>Showing All</strong>';
					$config["base_url"] = base_url("trader/watchlist");
					
					$config["total_rows"] = $this->Watchlist->count_watchlist($email_address);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);

					$this->pagination->initialize($config);
						
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
							
					//call the model function to get the Watchlist data
					$data['watchlist_array'] = $this->Watchlist->get_customer_watchlist($email_address, $config["per_page"], $offset);	
						
					$data["count"] = $this->Watchlist->count_watchlist($email_address);
				}
				$data['pagination'] = $this->pagination->create_links();
				
				//assign additional breadcrumb
				$data['breadcrumb'] = '';
				
				$data['page_breadcrumb'] = '<i class="fa fa-bell"></i> Watchlist';
				
				//assign page title name
				$data['pageTitle'] = 'Watchlist';
				
				//assign page ID
				$data['pageID'] = 'watchlist';
									
				//load header
				$this->load->view('trader_account_pages/header', $data);
								
				//load main body
				$this->load->view('trader_account_pages/watchlist_page', $data);				
				
				//load main footer
				$this->load->view('pages/footer');				
												
			}else{
				$url = 'trader/login?redirectURL='.urlencode(current_url());
				redirect($url);
			}	
		}	
		
		
		/**
		* Function to display statement
		* history
		*/			
		public function statements(){
			
			if($this->session->userdata('trader_logged_in')){
				
				
				//set cart count
				$data['cart_count'] = 0;
					
				//check if user has started shopping
				//update cart accordingly
				if($this->session->userdata('cart_array')){ 
					
					//obtain the shopping session id
					$session_id = $this->session->userdata('cart_array');
						
					$cart_count = $this->Cart->count_cart($session_id);
					if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
					}
					$data['cart_count'] = $cart_count;
						
				}	
				
				$email_address = $this->session->userdata('email_address');
				
				$data['users'] = $this->Customers->get_customer($email_address);
				
				//set payments count
				$count_payments = $this->Invoices->count_payments($email_address);
				if($count_payments == '' || $count_payments == null){
					$count_payments = 0;
				}
				$data['payments_count'] = $count_payments;
				 
				//display pending payments count 
				$pending_payments_count = $this->Invoices->count_pending_payments($email_address);
				if($pending_payments_count == '' || $pending_payments_count == null){
					$pending_payments_count = 0;
				}
				$data['pending_payments_count'] = $pending_payments_count;
				
				
				//get watchlist count
				$watchlist_count = $this->Watchlist->count_watchlist($email_address);
				
				if($watchlist_count == '' || $watchlist_count == null){
					$watchlist_count = 0;
				}
				$data['watchlist_count'] = $watchlist_count;
				
				//get shipping status count
				$shipping_count = $this->Shipping_status->count_shipping($email_address);
				
				if($shipping_count == '' || $shipping_count == null){
					$shipping_count = 0;
				}
				$data['shipping_count'] = $shipping_count;
				
				//get unread messages
				$data['header_messages_array'] = $this->Messages->get_header_messages();
				
				//set saved searches count
				$data['saved_searches_count'] = 0;
				
				$messages_unread = $this->Messages->count_unread_messages($email_address);
				
				if($messages_unread == '' || $messages_unread == null){
					$messages_unread = 0;
				}
				
				$data['messages_unread'] = $messages_unread;
				
				$config = array();

				if($this->input->get('search') != ''){
						
					$search = html_escape($this->input->get('search'));
					// get search string
					$search = html_escape($this->input->get('search'));
					$search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;
					$count = $this->Transactions->count_search($search, $email_address);
					if($count == '' || $count == null){
						$count = 0;
					}
				
					$data['count'] = $count;
						
					$data['display_option'] = 'Showing Results for "<strong><em>'.$search.'</em></strong>" <a href="'.base_url("trader/statements").'"  >Show All</a>';
						
					$config["base_url"] = base_url("trader/statements/$search");
					$config["total_rows"] = $count;
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
					
					$this->pagination->initialize($config);
						
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
						
					$data['statements_array'] = $this->Transactions->get_search($email_address, $search, $config["per_page"], $offset);	
					
				}else{	
					
					$data['display_option'] = '<strong>Showing All</strong>';
					$config["base_url"] = base_url("trader/statements");
					
					$config["total_rows"] = $this->Transactions->count_transactions($email_address);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);

					$this->pagination->initialize($config);
						
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
							
					//call the model function to get the payments data
					$data['statements_array'] = $this->Transactions->get_transactions($email_address, $config["per_page"], $offset);	
						
					$data["count"] = $this->Transactions->count_transactions($email_address);
				}
				$data['pagination'] = $this->pagination->create_links();
				
				//assign additional breadcrumb
				$data['breadcrumb'] = '';
				
				$data['page_breadcrumb'] = '<i class="fa fa-exchange"></i> Transactions';
				
				//assign page title name
				$data['pageTitle'] = 'Transactions';
				
				//assign page ID
				$data['pageID'] = 'statements';
									
				//load header
				$this->load->view('trader_account_pages/header', $data);
								
				//load main body
				$this->load->view('trader_account_pages/statements_page', $data);				
				
				//load main footer
				$this->load->view('pages/footer');				
												
			}else{
				$url = 'trader/login?redirectURL='.urlencode(current_url());
				redirect($url);
			}	
		}	
		
		
		/**
		* Function to display payments
		* history
		*/			
		public function payments(){
			
			if($this->session->userdata('trader_logged_in')){
				
				//set cart count
				$data['cart_count'] = 0;
					
				//check if user has started shopping
				//update cart accordingly
				if($this->session->userdata('cart_array')){ 
					
					//obtain the shopping session id
					$session_id = $this->session->userdata('cart_array');
						
					$cart_count = $this->Cart->count_cart($session_id);
					if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
					}
					$data['cart_count'] = $cart_count;
						
				}	
				
				$email_address = $this->session->userdata('email_address');
				
				//set payments count
				$count_payments = $this->Invoices->count_payments($email_address);
				if($count_payments == '' || $count_payments == null){
					$count_payments = 0;
				}
				$data['payments_count'] = $count_payments;
				
				//display pending payments count 
				$pending_payments_count = $this->Invoices->count_pending_payments($email_address);
				if($pending_payments_count == '' || $pending_payments_count == null){
					$pending_payments_count = 0;
				}
				$data['pending_payments_count'] = $pending_payments_count;
				
				
				$data['users'] = $this->Customers->get_customer($email_address);
				
				//get watchlist count
				$watchlist_count = $this->Watchlist->count_watchlist($email_address);
				
				if($watchlist_count == '' || $watchlist_count == null){
					$watchlist_count = 0;
				}
				$data['watchlist_count'] = $watchlist_count;
				
				//get shipping status count
				$shipping_count = $this->Shipping_status->count_shipping($email_address);
				
				if($shipping_count == '' || $shipping_count == null){
					$shipping_count = 0;
				}
				$data['shipping_count'] = $shipping_count;
				
				//get unread messages
				$data['header_messages_array'] = $this->Messages->get_header_messages();
				
				//set saved searches count
				$data['saved_searches_count'] = 0;
				
				$messages_unread = $this->Messages->count_unread_messages($email_address);
				
				if($messages_unread == '' || $messages_unread == null){
					$messages_unread = 0;
				}
				
				$data['messages_unread'] = $messages_unread;
				
				
				$config = array();
				
				if($this->input->get('search') != ''){
						
					$search = html_escape($this->input->get('search'));
					// get search string
					$search = html_escape($this->input->get('search'));
					$search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;
					$count = $this->Invoices->count_search($search, $email_address);
					if($count == '' || $count == null){
						$count = 0;
					}

					$data['count'] = $count;
						
					$data['display_option'] = 'Showing Results for "<strong><em>'.$search.'</em></strong>" <a href="'.base_url("trader/payments").'"  >Show All</a>';
						
					$config["base_url"] = base_url("trader/payments/$search");
					$config["total_rows"] = $count;
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
					
					$this->pagination->initialize($config);
						
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
						
					$data['my_payments_array'] = $this->Invoices->get_search($email_address, $search, $config["per_page"], $offset);	
					
				}else{	
					
					$data['display_option'] = '<strong>Showing All</strong>';
					$config["base_url"] = base_url("trader/payments");
					
					$config["total_rows"] = $this->Invoices->count_payments($email_address);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);

					$this->pagination->initialize($config);
						
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
							
					//call the model function to get the payments data
					$data['my_payments_array'] = $this->Invoices->get_payments($email_address, $config["per_page"], $offset);	
						
					$data["count"] = $this->Invoices->count_payments($email_address);
				}
				
				$data['pagination'] = $this->pagination->create_links();
				
				//assign additional breadcrumb
				$data['breadcrumb'] = '';
				
				$data['page_breadcrumb'] = '<i class="fa fa-history"></i> Payment History';
				
				//assign page title name
				$data['pageTitle'] = 'Payment History';
				
				//assign page ID
				$data['pageID'] = 'payments';
									
				//load header
				$this->load->view('trader_account_pages/header', $data);
								
				//load main body
				$this->load->view('trader_account_pages/payments_page', $data);				
				
				//load main footer
				$this->load->view('pages/footer');				
												
			}else{
				$url = 'trader/login?redirectURL='.urlencode(current_url());
				redirect($url);
			}	
		}	
		
		
		
		/**
		* Function to display pending payments
		* history
		*/			
		public function pending_payments(){
			
			if($this->session->userdata('trader_logged_in')){
				
				
				//set cart count
				$data['cart_count'] = 0;
					
				//check if user has started shopping
				//update cart accordingly
				if($this->session->userdata('cart_array')){ 
					
					//obtain the shopping session id
					$session_id = $this->session->userdata('cart_array');
						
					$cart_count = $this->Cart->count_cart($session_id);
					if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
					}
					$data['cart_count'] = $cart_count;
						
				}	
				
				$email_address = $this->session->userdata('email_address');
				
				//set payments count
				$count_payments = $this->Invoices->count_payments($email_address);
				if($count_payments == '' || $count_payments == null){
					$count_payments = 0;
				}
				
				$data['payments_count'] = $count_payments;
				
				//display pending payments count 
				$pending_payments_count = $this->Invoices->count_pending_payments($email_address);
				if($pending_payments_count == '' || $pending_payments_count == null){
					$pending_payments_count = 0;
				}
				$data['pending_payments_count'] = $pending_payments_count;
				
				$data['users'] = $this->Customers->get_customer($email_address);
				
				//get watchlist count
				$watchlist_count = $this->Watchlist->count_watchlist($email_address);
				
				if($watchlist_count == '' || $watchlist_count == null){
					$watchlist_count = 0;
				}
				$data['watchlist_count'] = $watchlist_count;
				
				//get shipping status count
				$shipping_count = $this->Shipping_status->count_shipping($email_address);
				
				if($shipping_count == '' || $shipping_count == null){
					$shipping_count = 0;
				}
				$data['shipping_count'] = $shipping_count;
				
				//get unread messages
				$data['header_messages_array'] = $this->Messages->get_header_messages();
				
				//set saved searches count
				$data['saved_searches_count'] = 0;
				
				$messages_unread = $this->Messages->count_unread_messages($email_address);
				
				if($messages_unread == '' || $messages_unread == null){
					$messages_unread = 0;
				}
				
				$data['messages_unread'] = $messages_unread;
				
				$config = array();
				
				if($this->input->get('search') != ''){
						
					$search = html_escape($this->input->get('search'));
					// get search string
					$search = html_escape($this->input->get('search'));
					$search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;
					$count = $this->Invoices->count_pending_search($search, $email_address);
					if($count == '' || $count == null){
						$count = 0;
					}

					$data['count'] = $count;
						
					$data['display_option'] = 'Showing Results for "<strong><em>'.$search.'</em></strong>" <a href="'.base_url("trader/pending_payments").'"  >Show All</a>';
						
					$config["base_url"] = base_url("trader/pending_payments/$search");
					$config["total_rows"] = $count;
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
					
					$this->pagination->initialize($config);
						
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
						
					$data['my_payments_array'] = $this->Invoices->get_pending_search($email_address, $search, $config["per_page"], $offset);	
					
				}else{	
					
					$data['display_option'] = '<strong>Showing All</strong>';
					$config["base_url"] = base_url("trader/pending_payments");
					
					$config["total_rows"] = $this->Invoices->count_pending_payments($email_address);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);

					$this->pagination->initialize($config);
						
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
							
					//call the model function to get the payments data
					$data['my_payments_array'] = $this->Invoices->get_pending_payments($email_address, $config["per_page"], $offset);	
						
					$data["count"] = $this->Invoices->count_pending_payments($email_address);
				}
				
				$data['pagination'] = $this->pagination->create_links();
				
				//assign additional breadcrumb
				$data['breadcrumb'] = '';
				
				$data['page_breadcrumb'] = '<i class="fa fa-exclamation-circle"></i> Pending Payments';
				
				//assign page title name
				$data['pageTitle'] = 'Pending Payments';
				
				//assign page ID
				$data['pageID'] = 'pending_payments';
									
				//load header
				$this->load->view('trader_account_pages/header', $data);
								
				//load main body
				$this->load->view('trader_account_pages/pending_payments_page', $data);				
				
				//load main footer
				$this->load->view('pages/footer');				
												
			}else{
				$url = 'trader/login?redirectURL='.urlencode(current_url());
				redirect($url);
			}	
		}	
		
		
		/**
		* Function to display shipping status
		* history
		*/			
		public function shipping(){
			
			if($this->session->userdata('trader_logged_in')){
				
				
				//set cart count
				$data['cart_count'] = 0;
					
				//check if user has started shopping
				//update cart accordingly
				if($this->session->userdata('cart_array')){ 
					
					//obtain the shopping session id
					$session_id = $this->session->userdata('cart_array');
						
					$cart_count = $this->Cart->count_cart($session_id);
					if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
					}
					$data['cart_count'] = $cart_count;
						
				}	
				
				$email_address = $this->session->userdata('email_address');
				
				//set payments count
				$count_payments = $this->Invoices->count_payments($email_address);
				if($count_payments == '' || $count_payments == null){
					$count_payments = 0;
				}
				
				$data['payments_count'] = $count_payments;
				
				//display pending payments count 
				$pending_payments_count = $this->Invoices->count_pending_payments($email_address);
				if($pending_payments_count == '' || $pending_payments_count == null){
					$pending_payments_count = 0;
				}
				$data['pending_payments_count'] = $pending_payments_count;
				
				$data['users'] = $this->Customers->get_customer($email_address);
				
				//get watchlist count
				$watchlist_count = $this->Watchlist->count_watchlist($email_address);
				
				if($watchlist_count == '' || $watchlist_count == null){
					$watchlist_count = 0;
				}
				$data['watchlist_count'] = $watchlist_count;
				
				//get shipping status count
				$shipping_count = $this->Shipping_status->count_shipping($email_address);
				
				if($shipping_count == '' || $shipping_count == null){
					$shipping_count = 0;
				}
				$data['shipping_count'] = $shipping_count;
				
				//get unread messages
				$data['header_messages_array'] = $this->Messages->get_header_messages();
				
				//set saved searches count
				$data['saved_searches_count'] = 0;
				
				$messages_unread = $this->Messages->count_unread_messages($email_address);
				
				if($messages_unread == '' || $messages_unread == null){
					$messages_unread = 0;
				}
				
				$data['messages_unread'] = $messages_unread;
				
				$config = array();
				
				if($this->input->get('search') != ''){
						
					$search = html_escape($this->input->get('search'));
					// get search string
					$search = html_escape($this->input->get('search'));
					$search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;
					$count = $this->Shipping_status->count_search($search, $email_address);
					if($count == '' || $count == null){
						$count = 0;
					}

					$data['count'] = $count;
						
					$data['display_option'] = 'Showing Results for "<strong><em>'.$search.'</em></strong>" <a href="'.base_url("trader/shipping").'"  >Show All</a>';
						
					$config["base_url"] = base_url("trader/shipping/$search");
					$config["total_rows"] = $count;
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
					
					$this->pagination->initialize($config);
						
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
						
					$data['shipping_array'] = $this->Shipping_status->get_search($email_address, $search, $config["per_page"], $offset);	
					
				}else{	
					
					$data['display_option'] = '<strong>Showing All</strong>';
					$config["base_url"] = base_url("trader/pending_payments");
					
					$config["total_rows"] = $this->Shipping_status->count_shipping($email_address);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);

					$this->pagination->initialize($config);
						
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
							
					//call the model function to get the payments data
					$data['shipping_array'] = $this->Shipping_status->shipping_status($email_address, $config["per_page"], $offset);	
						
					$data["count"] = $this->Shipping_status->count_shipping($email_address);
				}
				
				$data['pagination'] = $this->pagination->create_links();
				
				//assign additional breadcrumb
				$data['breadcrumb'] = '';
				
				$data['page_breadcrumb'] = '<i class="fa fa-share-square"></i> Shipping Status';
				
				//assign page title name
				$data['pageTitle'] = 'Shipping Status';
				
				//assign page ID
				$data['pageID'] = 'shipping';
									
				//load header
				$this->load->view('trader_account_pages/header', $data);
								
				//load main body
				$this->load->view('trader_account_pages/shipping_page', $data);				
				
				//load main footer
				$this->load->view('pages/footer');				
												
			}else{
				$url = 'trader/login?redirectURL='.urlencode(current_url());
				redirect($url);
			}	
		}	
				
		
		/**
		* Function to display payment 
		* methods
		*/			
		public function billing(){
			
			if($this->session->userdata('trader_logged_in')){
				
				//set cart count
				$data['cart_count'] = 0;
					
				//check if user has started shopping
				//update cart accordingly
				if($this->session->userdata('cart_array')){ 
					
					//obtain the shopping session id
					$session_id = $this->session->userdata('cart_array');
						
					$cart_count = $this->Cart->count_cart($session_id);
					if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
					}
					$data['cart_count'] = $cart_count;
						
				}	
				
				$email_address = $this->session->userdata('email_address');
				
				//set payments count
				$count_payments = $this->Invoices->count_payments($email_address);
				if($count_payments == '' || $count_payments == null){
					$count_payments = 0;
				}
				$data['payments_count'] = $count_payments;
				
				//display pending payments count 
				$pending_payments_count = $this->Invoices->count_pending_payments($email_address);
				if($pending_payments_count == '' || $pending_payments_count == null){
					$pending_payments_count = 0;
				}
				$data['pending_payments_count'] = $pending_payments_count;
				
				
				$data['users'] = $this->Customers->get_customer($email_address);
				
				//get watchlist count
				$watchlist_count = $this->Watchlist->count_watchlist($email_address);
				
				if($watchlist_count == '' || $watchlist_count == null){
					$watchlist_count = 0;
				}
				$data['watchlist_count'] = $watchlist_count;
				
				//get shipping status count
				$shipping_count = $this->Shipping_status->count_shipping($email_address);
				
				if($shipping_count == '' || $shipping_count == null){
					$shipping_count = 0;
				}
				$data['shipping_count'] = $shipping_count;
				
				//get unread messages
				$data['header_messages_array'] = $this->Messages->get_header_messages();
				
				//set saved searches count
				$data['saved_searches_count'] = 0;
				
				$messages_unread = $this->Messages->count_unread_messages($email_address);
				
				if($messages_unread == '' || $messages_unread == null){
					$messages_unread = 0;
				}
				
				$data['messages_unread'] = $messages_unread;
				

				$data['bank_details_array'] = $this->Bank_payment_methods->get_bank_details($email_address);	
				
				//assign additional breadcrumb
				$data['breadcrumb'] = '';
				
				$data['page_breadcrumb'] = '<i class="fa fa-money"></i> Billing Methods';
				
				//assign page title name
				$data['pageTitle'] = 'Billing Methods';
				
				//assign page ID
				$data['pageID'] = 'billing_methods';
									
				//load header
				$this->load->view('trader_account_pages/header', $data);
								
				//load main body
				$this->load->view('trader_account_pages/billing_methods_page');				
				
				//load main footer
				$this->load->view('pages/footer');				
												
			}else{
				$url = 'trader/login?redirectURL='.urlencode(current_url());
				redirect($url);
									
				//redirect('home/login/?redirectURL=payment_methods');
			}	
		}		
				



		/**
		* Function for password recovery page
		*
		*/
		public function password_reset(){
			
				//assign page title name
				$data['pageTitle'] = 'Password Recovery';
			
				//assign page ID
				$data['pageID'] = 'password_reset';

				//load header and page title
				//$this->load->view('trader_account_pages/header', $data);
				
				//load main body
				$this->load->view('trader_account_pages/password_recovery_page', $data);  							
		}
		
		/**
		* Function for password recovery validation
		*
		*/		
		public function password_recovery_validation(){

			$this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> ', '</div>');
				
            $this->form_validation->set_rules('email_address','Email Address','required|trim|xss_clean|valid_email|callback_validate_email');
			
			$this->form_validation->set_message('required', '%s cannot be blank!');

			if ($this->form_validation->run()){
				
				$data = array(
					'email_address' => $this->input->post('email_address'),
				);
				$this->session->set_userdata($data);
				
				//redirects to contact us page
				redirect('trader/confirm_security');				
				
			}else {
				$this->password_recovery();
			}
		
		}
		
		/**
		* Function to validate email
		*
		*/			
		public function validate_email(){
			
			if ($this->Traders->email_exists()){
				return TRUE;
			}
			else {
				$this->form_validation->set_message('validate_email', 'No record of this email address.');
				return FALSE;
			}			
						
		}
		
		/**
		* Function to confirm memorable info
		*
		*/			
		public function confirm_security(){
			
			$email_address = $this->session->userdata('email_address');

			$trader = $this->Traders->get_trader($email_address);
			$security_question = '';
					
			foreach($trader as $user){
				$data['security_question'] = $user->security_question;
			}
									
				//assign page title name
				$data['pageTitle'] = 'Confirm Security Information';

				//assign page ID
				$data['pageID'] = 'confirm_security';
				
				//load header and page title
				//$this->load->view('trader_account_pages/header', $data);
				
				//load main body
				$this->load->view('trader_account_pages/confirm_memorable_information_page', $data);  									
		}
		
		/**
		* Function to validate memorable info
		*
		*/			
		public function security_validation(){
			
			$this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
				
            $this->form_validation->set_rules('security_answer','Security Answer','required|trim|xss_clean|min_length[3]|callback_max_reset_attempts|callback_validate_answer');

            $this->form_validation->set_message('required', '%s cannot be blank!');	
			$this->form_validation->set_message('min_length', 'Your answer should be at least 3 characters in length!');			

			if ($this->form_validation->run()){
				//redirects to update_password page
				redirect('trader/new_password');
			}else {
				$this->confirm_security();
			}			
		}

		/**
		* Function to validate answer to 
		* memorable questions
		*/			
		public function validate_answer(){
			
			if ($this->Traders->answer_exists()){
				
				return TRUE;
			}
			else {
				$this->form_validation->set_message('validate_answer', 'Incorrect answer.');
				
				$this->Logins->insert_failed_reset();
				
				return FALSE;
			}			
		}
		
		
		/**
		* Function to check if the user has tried to reset wrongly
		* more than 3 times in 24 hours
		*/			
		public function max_reset_attempts(){
			
			$email_address = $this->session->userdata('email_address');
			
			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			$min_date = strtotime("-1 day", $date);
			
			$max_date = date('Y-m-d H:i:s', time());
			$min_date = date('Y-m-d H:i:s', $min_date);
			
			$this->db->select('*');
			$this->db->from('failed_resets');
			$this->db->where('email_address', $email_address);
			
			$this->db->where("attempt_time BETWEEN '$min_date' AND '$max_date'", NULL, FALSE);

			$query = $this->db->get();
			
			if ($query->num_rows() < 3){		
				return TRUE;	
			}else {	
				$this->form_validation->set_message('max_reset_attempts', 'You have surpassed the allowed number of reset attempts in 24 hours! Please contact Customer Service!');	
				return FALSE;
			}
		}

				
		

		/**
		* Function to display the update password
		* form
		*/			
		public function new_password(){
			
			if($this->session->userdata('email_address')){ 
			
				$email_address = $this->session->userdata('email_address');

				$trader = $this->Traders->get_trader($email_address);
				
				//assign page title name
				$data['pageTitle'] = 'Update Password';
				
				//load header and page title
				$this->load->view('trader_account_pages/header', $data);
				
				//load main body
				$this->load->view('trader_account_pages/update_password_page');
			}
			else {
				
				///email address not stored in session, redirects to login page
				redirect('trader/password_reset/','refresh');
			}				
		}
				
		/**
		* Function to validate new password
		*
		*/			
		public function new_password_validation(){
			
			$this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
				
            $this->form_validation->set_rules('password','New Password','required|trim|xss_clean|min_length[3]|matches[confirm_password]');
            $this->form_validation->set_rules('confirm_password','Confirm Password','required|trim|xss_clean');
           
            $this->form_validation->set_message('required', '%s cannot be blank!');			
			$this->form_validation->set_message('min_length', 'Your password should be at least 3 characters in length!');			
			$this->form_validation->set_message('matches', 'The passwords do not match!');
			
			
			if ($this->form_validation->run()){
				
				$data = array(			
					'password' => $this->input->post('password'),
				);
				
				if($this->Traders->update_trader($data)){
					
					$email_address = $this->session->userdata('email_address');
					
					//send email
					$ci = get_instance();
					$ci->load->library('email');
					$config['protocol'] = "smtp";
					$config['validate'] = 'FALSE';
					$config['smtp_host'] = "ssl://cp-in-1.webhostbox.net";
					$config['smtp_port'] = "465";
					$config['smtp_user'] = "getextra@global-sub.com"; 
					$config['smtp_pass'] = "1234567";
					$config['charset'] = "utf-8";
					$config['mailtype'] = "html";
					$config['newline'] = "\r\n";

					$ci->email->initialize($config);

					//setup email function
					$ci->email->from('getextra@global-sub.com', 'Auto9ja');
					$ci->email->to($email_address);
					$this->email->reply_to('getextra@gmail.com', 'Auto9ja');
					$ci->email->subject('Your password has been updated');
					
					//compose email message
					$message = "<div style='font-size: 1.0em; border: 1px solid #D0D0D0; border-radius: 3px; margin: 5px; padding: 10px; '>";
					$message .= '<div align="center" id="logo"><a href="'.base_url().'" title="Get Extra Hands">'.img('assets/images/logo/logo2.png').'</a></div><br/>';
					$message .= "<p>Your password has been updated</p>";
					$message .= "<p>If you did not make this change. Please contact us immediately.</p>";
					$message .= "</div>";
					
					$ci->email->message($message);
					
					$ci->email->send();
				
					//redirects to password updated page
					redirect('trader/password_updated');	
					
				}else{
					
					$this->new_password();
				}
			}else {
				
				$this->new_password();
			}			
		}

		/**
		* Function for new password creation
		* success page
		*/	
		public function password_updated(){
			
			$email_address = $this->session->userdata('email_address');

			$this->session->unset_userdata('email_address');
			
			$this->session->sess_destroy();		

			//assign page title name
			$data['pageTitle'] = 'Password Update Success!';
				
			//assign page ID
			$data['pageID'] = 'update_password';
										
			//load header and page title
			$this->load->view('trader_account_pages/header', $data);
					
			//load main body
			$this->load->view('trader_account_pages/update_password_success');
			
		
		}


		
		
		public function logout(){
			
			if($this->session->userdata('trader_logged_in')){
				
				$email_address = $this->session->userdata('email_address');
				
				$this->session->unset_userdata('trader_logged_in');
				$this->session->unset_userdata('email_address');
				$this->session->unset_userdata('login_time');
				$this->session->unset_userdata('answered_count');
				$this->session->unset_userdata('page_count');
				$this->session->sess_destroy();
				$this->facebook->destroySession();
				 
				//redirects to logged out page
				redirect('trader/login');
			
			}else{
				
				//redirects to logged out page
				redirect('login');				
			}
			
		}




}	
		