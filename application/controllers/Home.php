<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public function __construct() {
		
		parent::__construct();

		// Include two files from google-php-client library in controller
		//require_once APPPATH . "libraries/google-api-php-client/vendor/autoload.php";
		include_once APPPATH . "libraries/google-api-php-client/src/Google/Client.php";
		include_once APPPATH . "libraries/google-api-php-client/src/Google/Service/Oauth2.php";

	}
	
	
	public function index()
	{
		//set cart count
		$data['cart_count'] = 0;
		$email_address = $this->session->userdata('email_address');	
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
		if($this->session->userdata('logged_in')){ 
			//obtain the user details if logged in
			
			$data['users'] = $this->Customers->get_customer($email_address);	
		} 
		if($this->session->userdata('seller_logged_in')){ 
			//obtain the user details if logged in
			
			$data['users'] = $this->Traders->get_trader($email_address);	
		} 
		$messages_unread = $this->Messages->count_unread_messages($email_address);
				
		if($messages_unread == '' || $messages_unread == null){
			$messages_unread = 0;
		}
		$data['messages_unread'] = $messages_unread;
		
		//vehicle type list dropdown
		$vehicle_type = '<select name="vehicle_type" class="form-control custom-select" id="vehicle_type">';
				
		$this->db->from('vehicle_types');
		$this->db->order_by('id');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			foreach($result->result_array() as $row){
				$vehicle_type .= '<option value="'.$row['name'].'">'.$row['name'].'</option>';			
			}
		}
		$vehicle_type .= '</select>';
		$data['vehicle_type'] = $vehicle_type;
		
		//vehicle make list dropdown
		$vehicle_make = '<select name="vehicle_make" class="form-control custom-select" id="vehicle_make">';
		$vehicle_make .= '<option value="All">All Makes</option>';		
		$this->db->from('vehicle_makes');
		$this->db->order_by('id');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			foreach($result->result_array() as $row){
				$vehicle_make .= '<option value="'.$row['id'].'">'.$row['title'].'</option>';			
			}
		}
		$vehicle_make .= '</select>';
		$data['vehicle_make'] = $vehicle_make;
		
		$data['vehicle_types'] = $this->Vehicle_types->get_all_types();
				
		//assign page title name
		$data['pageTitle'] = 'Home';
		
		//assign page ID
		$data['pageID'] = 'home';
		
		$this->load->view('pages/header', $data);
		
		$this->load->view('pages/home_page', $data);
		
		$this->load->view('pages/footer');
	}
	

	public function about()
	{
		//set cart count
		$data['cart_count'] = 0;
		$email_address = $this->session->userdata('email_address');	
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
		if($this->session->userdata('logged_in')){ 
			//obtain the user details if logged in
			
			$data['users'] = $this->Customers->get_customer($email_address);	
		} 
		if($this->session->userdata('seller_logged_in')){ 
			//obtain the user details if logged in
			
			$data['users'] = $this->Traders->get_trader($email_address);	
		} 
		$messages_unread = $this->Messages->count_unread_messages($email_address);
				
		if($messages_unread == '' || $messages_unread == null){
			$messages_unread = 0;
		}
		$data['messages_unread'] = $messages_unread;
				
		
		
		//assign page title name
		$data['pageTitle'] = 'About';
		
		//assign page ID
		$data['pageID'] = 'about';
				
		$this->load->view('pages/header', $data);
		
		$this->load->view('pages/about_page', $data);
		
		$this->load->view('pages/footer');
	}	
	

	public function become_a_seller()
	{
		//set cart count
		$data['cart_count'] = 0;
		$email_address = $this->session->userdata('email_address');	
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
		if($this->session->userdata('logged_in')){ 
			//obtain the user details if logged in
			
			$data['users'] = $this->Customers->get_customer($email_address);	
		} 
		if($this->session->userdata('seller_logged_in')){ 
			//obtain the user details if logged in
			
			$data['users'] = $this->Traders->get_trader($email_address);	
		} 
		$messages_unread = $this->Messages->count_unread_messages($email_address);
				
		if($messages_unread == '' || $messages_unread == null){
			$messages_unread = 0;
		}
		$data['messages_unread'] = $messages_unread;
		
		//assign page title name
		$data['pageTitle'] = 'Become a seller';
		
		//assign page ID
		$data['pageID'] = 'become_a_seller';
				
		$this->load->view('pages/header', $data);
		
		$this->load->view('pages/become_a_seller_page', $data);
		
		$this->load->view('pages/footer');
	}	

	

	public function faq()
	{
		//set cart count
		$data['cart_count'] = 0;
		$email_address = $this->session->userdata('email_address');	
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
		if($this->session->userdata('logged_in')){ 
			//obtain the user details if logged in
			
			$data['users'] = $this->Customers->get_customer($email_address);	
		} 
		if($this->session->userdata('seller_logged_in')){ 
			//obtain the user details if logged in
			
			$data['users'] = $this->Traders->get_trader($email_address);	
		} 
		$messages_unread = $this->Messages->count_unread_messages($email_address);
				
		if($messages_unread == '' || $messages_unread == null){
			$messages_unread = 0;
		}
		$data['messages_unread'] = $messages_unread;
				
		if($messages_unread == '' || $messages_unread == null){
			$messages_unread = 0;
		}
		$data['messages_unread'] = $messages_unread;
		
		//assign page title name
		$data['pageTitle'] = 'FAQ';
		
		//assign page ID
		$data['pageID'] = 'faq';
				
		$this->load->view('pages/header', $data);
		
		$this->load->view('pages/faq_page', $data);
		
		$this->load->view('pages/footer');
	}	
	
	
	public function contact_us()
	{
		//set cart count
		$data['cart_count'] = 0;
		$email_address = $this->session->userdata('email_address');	
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
		if($this->session->userdata('logged_in')){ 
			//obtain the user details if logged in
			
			$data['users'] = $this->Customers->get_customer($email_address);	
		} 
		if($this->session->userdata('seller_logged_in')){ 
			//obtain the user details if logged in
			
			$data['users'] = $this->Traders->get_trader($email_address);	
		} 
		$messages_unread = $this->Messages->count_unread_messages($email_address);
				
		if($messages_unread == '' || $messages_unread == null){
			$messages_unread = 0;
		}
		$data['messages_unread'] = $messages_unread;
		
		//assign page title name
		$data['pageTitle'] = 'Contact Us';
		
		//assign page ID
		$data['pageID'] = 'contact_us';
				
		$this->load->view('pages/header', $data);
		
		$this->load->view('pages/contact_us_page', $data);
		
		$this->load->view('pages/footer');
	}	


		/**
		* Function to validate messages from 
		* the contact us page
		*/			
		public function contact_us_validation() {
            
            $this->load->library('form_validation');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			
            $this->form_validation->set_rules('contact_us_name','Name','required|trim|xss_clean|min_length[4]|callback_check_double_messaging');
            $this->form_validation->set_rules('contact_us_telephone','Telephone number','required|trim|xss_clean|regex_match[/^[0-9\+\(\)\/-]+$/]');
			$this->form_validation->set_rules('contact_us_email','Email','required|trim|valid_email');
			$this->form_validation->set_rules('contact_us_subject','Subject','required|trim|xss_clean|min_length[2]');
			$this->form_validation->set_rules('contact_us_message','Message','required|trim|xss_clean|min_length[6]');
            
			$this->form_validation->set_message('required', '%s cannot be blank!');
			$this->form_validation->set_message('min_length', '%s is too short!');
			$this->form_validation->set_message('regex_match', 'Please enter a valid telephone number!');
			$this->form_validation->set_message('valid_email', 'Please enter a valid email address!');
			
			if ($this->form_validation->run()){
				
					//obtain users ip address
					$ipaddress = $this->Logins->ip();	
					
					//array of all post variables
					$contact_data = array(
						'contact_name' => $this->input->post('contact_us_name'),
						'contact_telephone' => $this->input->post('contact_us_telephone'),
						'contact_email' => $this->input->post('contact_us_email'),
						'contact_subject' => $this->input->post('contact_us_subject'),
						'contact_message' => $this->input->post('contact_us_message'),
						'ip_address' => $ipaddress,
						'contact_us_date' => date('Y-m-d H:i:s'),
					);
				
					$this->Contact_us->add_contact_us($contact_data);

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
					$ci->email->from('getextra@global-sub.com', 'Avenue 1-OH-1');
					$this->email->reply_to('getextra@gmail.com', 'Avenue 1-OH-1');
					$ci->email->to('getextra@gmail.com');
					$ci->email->subject('Contact Us Message From '. $this->input->post('contact_us_name'));
					
					
					//compose email message
					$message = "<div style='font-size: 1.0em; border: 1px solid #D0D0D0; border-radius: 3px; margin: 5px; padding: 10px; '>";
					$message .= '<div align="center" id="logo"><a href="'.base_url().'" title="Avenue 1-OH-1">'.img('assets/images/logo/logo.png').'</a></div><br/>';
					$message .= "<p>Name: ". $this->input->post('contact_us_name'). ",</p>";
					$message .= "<p>Telephone: ". $this->input->post('contact_us_telephone'). ",</p>";
					$message .= "<p>Email: ". $this->input->post('contact_us_email'). ",</p>";
					$message .= "<p>Subject: ". $this->input->post('contact_us_subject'). ",</p>";
					$message .= "<p>Message: ". $this->input->post('contact_us_message'). ",</p>";
					$message .= "</div>";
					
					$ci->email->message($message);
					
					$ci->email->send();
					
					
					$this->session->set_flashdata('message_sent', '<div class="alert alert-success text-center" role="alert"><i class="fa fa-check-circle"></i> Your message has been sent!</div>');
					
					//redirects to contact us page
					redirect('contact_us');	
					//$data['success'] = true;
					//$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Your message has been sent!</div>';
				
				
			}else {
					//redirects to contact us page
					$this->contact_us();	
					//$data['success'] = false;
					//$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Message not sent!</div>';
					//$data['errors'] = '<div class="alert alert-danger text-center" role="alert">'.validation_errors().'</div>';
					
			}
                //echo json_encode($data);
        }

		/**
		* Function to check_double_post 
		* 
		*/			
		public function check_double_messaging(){
			
			//obtain users ip address
			$ipaddress = $this->Logins->ip();

			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			$min_date = strtotime("-20 second", $date);
			
			$max_date = date('Y-m-d H:i:s', time());
			$min_date = date('Y-m-d H:i:s', $min_date);
			
			$this->db->select('*');
			$this->db->from('contact_us');
			$this->db->where('ip_address', $ipaddress);
			
			$this->db->where("contact_us_date BETWEEN '$min_date' AND '$max_date'", NULL, FALSE);

			$query = $this->db->get();
			
			if ($query->num_rows() >= 1){	
				$this->form_validation->set_message('check_double_messaging', 'You must wait at least 20 seconds before you send another message!');
				return FALSE;
			}else {
				
				return TRUE;
			}	
		}			
		

		
		/**
		* Function to handle user sign up
		*
		*/			
        public function social() {
			
			//$client_id = '527809971240-naffv5gdi5uttpofptudkii0o3pc7n8b.apps.googleusercontent.com';
			//$client_secret = 'ESCOl8Mdq-IGFtFhWRFM_CSm';
			//$redirect_uri = 'http://localhost/websites/getextra/oauth2login/googlecallback/';
			//$simple_api_key = 'AIzaSyAKZ9IkXH8OB0T09bYACGsll_oqQmYubCE';
			
			// Create Client Request to access Google API
			$client = new Google_Client();
			$client->setApplicationName($this->config->item('application_name', 'google'));
			$client->setClientId($this->config->item('client_id', 'google'));
			$client->setClientSecret($this->config->item('client_secret', 'google'));
			$client->setRedirectUri($this->config->item('redirect_uri', 'google'));
			$client->setDeveloperKey($this->config->item('api_key', 'google'));
			$client->addScope("https://www.googleapis.com/auth/userinfo.email");
			
			// Send Client Request
		//	$objOAuthService = new Google_Service_Oauth2($client);			
			
			
			// Requested permissions - optional
			$fbpermissions = array(
				'public_profile',
				'email',
				//'user_location',
			);
			// Store users facebook login url
			$data['fblogin'] = $this->facebook->getLoginUrl(array(
				'redirect_uri' => site_url('oauth2login/facebookcallback'), 
				'scope' => $fbpermissions // permissions here
			));
			
			// Store users Google login url
			$authUrl = $client->createAuthUrl();
			$data['googlelogin'] = $authUrl;
			
			//assign page title name
			$data['pageTitle'] = 'Social';
			
			//assign page ID
			$data['pageID'] = 'social';
			
			//load social header
			$this->load->view('pages/social-header', $data);
			
			//load main body
            $this->load->view('pages/social_page', $data);  
			
			//load social footer
			$this->load->view('pages/social-footer');

        }		
			
	
	public function login()
	{
	
		if($this->session->userdata('logged_in')){
				
				//assign page title name
				redirect('account/dashboard');
		}
		else {
				
			if($this->input->get('redirectURL') != ''){
					$url = $this->input->get('redirectURL');
					$this->session->set_flashdata('redirectURL', $url);	
			}
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
			
			// Create Client Request to access Google API
			$client = new Google_Client();
			$client->setApplicationName($this->config->item('application_name', 'google'));
			$client->setClientId($this->config->item('client_id', 'google'));
			$client->setClientSecret($this->config->item('client_secret', 'google'));
			$client->setRedirectUri($this->config->item('redirect_uri', 'google'));
			$client->setDeveloperKey($this->config->item('api_key', 'google'));
			$client->addScope("https://www.googleapis.com/auth/userinfo.email");
			
			// Send Client Request
		//	$objOAuthService = new Google_Service_Oauth2($client);			
			
			
			// Requested permissions - optional
			$fbpermissions = array(
				'public_profile',
				'email',
				//'user_location',
			);
			// Store users facebook login url
			$data['fblogin'] = $this->facebook->getLoginUrl(array(
				'redirect_uri' => site_url('oauth2login/facebookcallback'), 
				'scope' => $fbpermissions // permissions here
			));
			
			// Store users Google login url
			$authUrl = $client->createAuthUrl();
			$data['googlelogin'] = $authUrl;
			
			
			//assign page title name
			$data['pageTitle'] = 'Account Login';
				
			//assign page ID
			$data['pageID'] = 'login';

			//load social header
			$this->load->view('pages/header', $data);
											
			//load main body
			$this->load->view('pages/user_login_page', $data);

			//load social footer
			$this->load->view('pages/footer');
		}
		
	}
		
		/**
		* Function to validate user login
		* information
		*/
        public function login_validation() {
			
            $this->session->keep_flashdata('redirectURL');
			
            $this->load->library('form_validation');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			
			$this->form_validation->set_rules('email_address','Email address','required|trim|callback_max_login_attempts|callback_validate_credentials');
            $this->form_validation->set_rules('password','Password','required|trim');
            
            $this->form_validation->set_message('required', '%s cannot be blank!');
			
			if ($this->form_validation->run()){

				$data = array(
					'email_address' => $this->input->post('email_address'),
					'logged_in' => 1,
				);
				
				$this->session->set_userdata($data);
				

				if($this->Customers->check_isset_security_info()){
					
					//redirects to set memorable information page
					redirect('set_security_information');
					
				}else {			
					//redirects to account page
					redirect('account/dashboard');	
				}		
            }
            else {		
				//redirects to login page
				$this->login();	
            }
        }
		
		/**
		* Function to validate username
		* during login
		*/		
		public function validate_credentials() {
			
			if ($this->Customers->can_log_in()){
				$email_address = $this->input->post('email_address');
				
				//check last login time from the logins table
				$last_login = $this->Logins->get_last_login_time($email_address);
				
				//if there is a record then update users record
				//otherwise ignore
				if($last_login){
					foreach($last_login as $login){
						$this->Logins->update_login_time($email_address, $login->login_time);
					}
				}
				
				$this->Logins->insert_login();
				
				return TRUE;
			}
			else {
				
				$this->Logins->insert_failed_login();
				
				$this->form_validation->set_message('validate_credentials', 'Incorrect email address or password.');
				
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
		* Function to handle user sign up
		*
		*/			
        public function register() {
			
			if($this->session->userdata('logged_in')){
				
				//if user logged in redirect to their account page
				redirect('account/dashboard');
				
			}else{
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
				
				// Create Client Request to access Google API
				$client = new Google_Client();
				$client->setApplicationName($this->config->item('application_name', 'google'));
				$client->setClientId($this->config->item('client_id', 'google'));
				$client->setClientSecret($this->config->item('client_secret', 'google'));
				$client->setRedirectUri($this->config->item('redirect_uri', 'google'));
				$client->setDeveloperKey($this->config->item('api_key', 'google'));
				$client->addScope("https://www.googleapis.com/auth/userinfo.email");
				
				// Send Client Request
			//	$objOAuthService = new Google_Service_Oauth2($client);			
				
				
				// Requested permissions - optional
				$fbpermissions = array(
					'public_profile',
					'email',
					//'user_location',
				);
				// Store users facebook login url
				$data['fblogin'] = $this->facebook->getLoginUrl(array(
					'redirect_uri' => site_url('oauth2login/facebookcallback'), 
					'scope' => $fbpermissions // permissions here
				));
				
				// Store users Google login url
				$authUrl = $client->createAuthUrl();
				$data['googlelogin'] = $authUrl;
				
				
				//assign page title name
				$data['pageTitle'] = 'Sign Up';
			
				//assign page ID
				$data['pageID'] = 'sign_up';

				//load social header
				$this->load->view('pages/header', $data);
															
				//load main body
				$this->load->view('pages/user_registration_page', $data); 

				//load social footer
				$this->load->view('pages/footer');				
			}
			
        }

		/**
		* Function to handle user sign up
		*
		*/			
        public function signup() {
			
			if($this->session->userdata('logged_in')){
				
				//if user logged in redirect to their account page
				redirect('account/dashboard');
				
			}else{
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
				//assign page title name
				$data['pageTitle'] = 'Sign Up';
			
				//assign page ID
				$data['pageID'] = 'sign_up';

				//country list dropdown
				$data['list_of_countries'] =  $this->Countries->get_country_list();
		
				$country_options = '<select name="country" id="country_id">';
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

				//load social header
				$this->load->view('pages/social-header', $data);
															
				//load main body
				$this->load->view('pages/user_signup_page', $data); 

				//load social footer
				$this->load->view('pages/social-footer');				
			}
			
        }


		/**
		* Function to handle signup validation
		*
		*/	        
        public function signup_validation() {
            
            $this->load->library('form_validation');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			
			$this->form_validation->set_rules('first_name','First Name','required|trim|xss_clean');
            $this->form_validation->set_rules('last_name','Last Name','required|trim|xss_clean');
            
            $this->form_validation->set_rules('email_address','Email','required|trim|valid_email|is_unique[customers.email_address]|is_unique[temp_customers.email_address]|xss_clean');
			
            $this->form_validation->set_rules('password','Password','required|trim|xss_clean');
			$this->form_validation->set_rules('confirm_password','Confirm Password','required|trim|matches[password]|xss_clean');
            
			$this->form_validation->set_message('is_unique', 'This %s is already registered!');
			
			$this->form_validation->set_message('required', '%s cannot be blank!');
			
			$this->form_validation->set_message('matches', 'The passwords do not match!');
			
			$this->form_validation->set_message('regex_match', 'Please enter a valid phone number!');
							
			
			if ($this->form_validation->run()){
				
				//generate a random key
				$activation_key = md5(uniqid());
				
				//generate a random code 6 digit number
				//$code = mt_rand(100000, 999999);
				
				//add new user to the temp database
				if ($this->Customers->add_temp_customers($activation_key)){
					
					//save email in flash session
					$email = $this->input->post('email_address');
					$this->session->set_flashdata('email', $email);
					
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
					
					//send email
					//$this->load->library('email', array(
					//	'mailtype' => 'html'
					//));

					//setup email function
					$ci->email->from('getextra@global-sub.com', 'Auto9ja');
					$ci->email->to($email);
					$this->email->reply_to('getextra@gmail.com', 'Auto9ja');
					$ci->email->subject('Activate your account.');
				
					$full_name = $this->input->post('first_name').' '.$this->input->post('last_name'); 
					//$encoded_email = $this->encrypt->encode($this->input->post('email_address'));
					$encoded_email = urlencode($this->input->post('email_address'));
					$activation_link = base_url("account/activation/")."".$activation_key."/".$encoded_email;
					
					//compose email message
					$message = "<div style='font-size: 1.0em; border: 1px solid #D0D0D0; border-radius: 3px; margin: 5px; padding: 10px; '>";
					$message .= '<div align="center" id="logo"><a href="'.base_url().'" title="Auto9ja"><img src="'.base_url().'assets/images/logo/logo2.png" alt="Logo"></a></div><br/>';
					$message .= "<p>Hello ". $full_name. ",</p>";
					$message .= "<p>This is to confirm that your account has been registered.</p>";
					//$message .= "<p>Please enter the code below to activate your account:</p>";
					//$message .= "<p>Activation Code: ".$code."</p>";
					$message .= "<p>Click the link below to activate your account:</p>";
					$message .= "<p><a href='".$activation_link."'>Activate your account here</a>.</p>";
					$message .= "<p>The link expires in 24 hours.</p>";
					$message .= "</div>";
					
					$ci->email->message($message);
					
					$ci->email->send();
					
					//signup successful, redirects to final page
					redirect('activation');	
				} 	
            }
            else {
				//registration failed and shows the signup page again
                $this->register();
            }
        }		
		

		/**
		* Function to ensure a country is selected 
		* 
		*/			
		public function country_option_check(){
			
			$str1 = $this->input->post('country');
			
			if ($str1 == 'Select Country' )
			{
				$this->form_validation->set_message('country_option_check', 'Please select a question');
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		
		/**
		* Function to handle signup success
		*
		*/		
		public function activation(){
			
			if($this->session->userdata('logged_in')){
				
				//if user logged in redirect to their account page
				redirect('account/dashboard');
				
			}else{
				//set cart count
				$data['cart_count'] = 0;
				
				$this->session->keep_flashdata('email');				
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
							
				//assign page title name
				$data['pageTitle'] = 'Sign Up Success!';

				//assign page ID
				$data['pageID'] = 'sign_up_success';
				
				//load social header
				$this->load->view('pages/header', $data);
															
				//load main body
				$this->load->view('pages/signup_success_page', $data);	
				
				//load social footer
				$this->load->view('pages/footer');
			
			}			
	
		}
				
		
		/**
		* Function to activate the user account
		* @param $string Activation key
		*/		
		public function activationOld(){
			
			if($this->session->userdata('logged_in')){
				
				//if user logged in redirect to their account page
				redirect('account/dashboard');
				
			}else{
				
				//assign page title name
				$data['pageTitle'] = 'Activation';
			
				//assign page ID
				$data['pageID'] = 'activation';			
				
				//load social header
				$this->load->view('pages/header', $data);
															
				//load main body
				$this->load->view('pages/user_activation_page', $data);  
				
				//load social footer
				$this->load->view('pages/footer');
															
			}			

		}


		/**
		* Function to handle signup validation
		*
		*/	        
        public function activation_validation() {
            
            $this->load->library('form_validation');
			
			$this->session->keep_flashdata('email');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
			
			$this->form_validation->set_rules('activation_code','Activation Code','required|trim|xss_clean|callback_validate_code|callback_max_activation_attempts');

			$this->form_validation->set_message('required', '%s cannot be blank!');

			if ($this->form_validation->run()){
				
				$code = $this->input->post('activation_code');
				
				if ($this->Customers->activate_user($code)){
					
					redirect('activation/success');
				}
            }
            else {
				//activation failed and shows the activation page again
                $this->activation();
            }
        }	

		/**
		* Function to validate code
		* 
		*/		
		public function validate_code() {
			
			$code = $this->input->post('activation_code');
			$email = $this->session->flashdata('email');
			
			if ($this->Customers->is_valid_code($code,$email)){
				return TRUE;
			}
			else {
				$this->form_validation->set_message('validate_code', 'Please enter a valid code!');
				return FALSE;
			}
		}

			
		/**
		* Function to check if the user has entered
		* the activation code wrongly
		* more than 3 times in 24 hours
		*/			
		public function max_activation_attempts(){
			
			//$ip_address = $this->input->post('ip_address');
			//obtain users ip address
			$ipaddress = $ipaddress = $this->Logins->ip();			
			
			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			$min_date = strtotime("-1 day", $date);
			
			$max_date = date('Y-m-d H:i:s', time());
			$min_date = date('Y-m-d H:i:s', $min_date);
			
			$this->db->select('*');
			$this->db->from('activation_attempts');
			$this->db->where('ip_address', $ipaddress);
			
			$this->db->where("attempt_time BETWEEN '$min_date' AND '$max_date'", NULL, FALSE);

			$query = $this->db->get();
			
			if ($query->num_rows() < 3){	
				return TRUE;	
			}else {	
				$this->form_validation->set_message('max_activation_attempts', 'You have surpassed the allowed number of activation attempts, please contact Customer Service!');
				return FALSE;
			}
		}	
	
	
		
		/**
		* Function to activate the user account
		* @param $string Activation key
		*/		
		public function account_activation($activation_key = null, $email = null){
			
			$email = urldecode($email);
			
			if ($this->Customers->is_valid_key($activation_key, $email)){
				
				if ($this->Customers->add_user($activation_key, $email)){
					
					redirect('activation/success');
					
				}else {
					redirect('activation/error');
				}
				
			} else {
					redirect('activation/error');
			}
		}				
	
		
		/**
		* Function to activation success
		* @param  
		*/		
		public function activation_success(){
			
			if($this->session->userdata('logged_in')){
				
				//if user logged in redirect to their account page
				redirect('account/dashboard');
				
			}else{
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
											
				//assign page title name
				$data['pageTitle'] = 'Account Activated!';
				
				//assign page ID
				$data['pageID'] = 'account_activated';
				
				//load social header
				$this->load->view('pages/header', $data);
																
				//load main body
				$this->load->view('pages/account_activated', $data);	

				//load social footer
				$this->load->view('pages/footer');

			}
			
		}	
	
		
		/**
		* Function to activated error
		* @param
		*/		
		public function activation_error(){
			
			if($this->session->userdata('logged_in')){
				
				//if user logged in redirect to their account page
				redirect('account/dashboard');
				
			}else{
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
										
				//assign page title name
				$data['pageTitle'] = 'Activation Failed!';
				
				//assign page ID
				$data['pageID'] = 'activated_failed';
				
				//load social header
				$this->load->view('pages/header', $data);
																		
				//load main body
				$this->load->view('pages/activation_failed', $data);

				//load social footer
				$this->load->view('pages/footer');
																
			}
			
		}	


	public function empty_cart(){
		
		if($this->input->get('cmd') && $this->input->get('cmd') == "emptycart"){
			
			$this->session->unset_userdata('cart_array');
			redirect('home/listing');
			
		}
	}

	
	public function update_cart(){
		
		if($this->input->post('item_to_adjust') && $this->input->post('item_to_adjust') != ""){
			
			// execute some code
			$item_to_adjust = html_escape($this->input->post('item_to_adjust'));
			$quantity = html_escape($this->input->post('quantity'));
			$quantity = preg_replace('#[^0-9]#i', '', $quantity); // filter everything but numbers
			
			if ($quantity >= 100) { 
				$quantity = 99; 
			}
			if ($quantity < 1){ 
				// Access the array and run code to remove that array index
				$key_to_remove = html_escape($this->input->post('index'));
				if (count($_SESSION["cart_array"]) <= 1) {
					unset($_SESSION["cart_array"]);
				} else {
					unset($_SESSION["cart_array"]["$key_to_remove"]);
					sort($_SESSION["cart_array"]);
				} 
			}
			if ($quantity == ""){ 
				$quantity = 1; 
			}
			$i = 0;
			foreach ($_SESSION["cart_array"] as $each_item) { 
				$i++;
				while (list($key, $value) = each($each_item)) {
					if ($key == "product_id" && $value == $item_to_adjust) {
						// That item is in cart already so let's adjust its quantity using array_splice()
						array_splice($_SESSION["cart_array"], $i-1, 1, array(array("product_id" => $item_to_adjust, "quantity" => $quantity)));
					} // close if condition
				} // close while loop
			} // close foreach loop	
			
			$this->session->set_flashdata('product_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box2").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box2 text-center"><i class="fa fa-check-circle"></i> Item updated!</div>');
			//$data['success'] = true;		
			redirect('home/listing');			
		}
	}

	
	public function remove_item(){
		
		if($this->input->post('index_to_remove') && $this->input->post('index_to_remove') != ""){
			
			// Access the array and run code to remove that array index
			$key_to_remove = $this->input->post('index_to_remove');
			if (count($_SESSION["cart_array"]) <= 1) {
				unset($_SESSION["cart_array"]);
			} else {
				unset($_SESSION["cart_array"]["$key_to_remove"]);
				sort($_SESSION["cart_array"]);
			}
			
			redirect('home/listing');
			
		}
	}

	
	public function add_cart_item(){
		
		if($this->input->post('vehicle_id')){
			
			$vehicle_id = $this->input->post('vehicle_id'); 
			$quantity = 1;
			$wasFound = false;
			$i = 0;
			
			// If the cart session variable is not set or cart array is empty
			if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) { 
				// RUN IF THE CART IS EMPTY OR NOT SET
				$_SESSION["cart_array"] = array(0 => array("vehicle_id" => $vehicle_id, "quantity" => $quantity));
			}else {
				// RUN IF THE CART HAS AT LEAST ONE ITEM IN IT
				foreach ($_SESSION["cart_array"] as $each_item) { 
					$i++;
					while (list($key, $value) = each($each_item)) {
						if ($key == "vehicle_id" && $value == $vehicle_id) {
							  // That item is in cart already so let's adjust its quantity using array_splice()
							  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("vehicle_id" => $vehicle_id, "quantity" => $each_item['quantity'] + 0)));
							  $wasFound = true;
						} // close if condition
					} // close while loop
				} // close foreach loop
				if ($wasFound == false) {
					array_push($_SESSION["cart_array"], array("vehicle_id" => $vehicle_id, "quantity" => $quantity));
				}
			}
			//redirect('store/cart');
			$this->session->set_flashdata('vehicle_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box2").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box2 text-center"><i class="fa fa-check-circle"></i> Vehicle added!</div>');
			
			$data['success'] = true;				
			
			$data['cart_count'] = count($_SESSION["cart_array"]);
			$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Vehicle has been added!</div>';

		}else{
			$this->session->set_flashdata('vehicle_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box2").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box2 text-center"><i class="fa fa-check-circle"></i> Vehicle not added!</div>');
							
			$data['success'] = false;
			$data['notif'] = '<div class="alert alert-danger text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Vehicle has not been added!</div>';
		}
		
			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			//echo json_encode($data);		
	}

	
	public function listing(){
		
		//set cart count
		$data['cart_count'] = 0;
		$email_address = $this->session->userdata('email_address');	
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
		if($this->session->userdata('logged_in')){ 
			//obtain the user details if logged in
			
			$data['users'] = $this->Customers->get_customer($email_address);	
		} 
		if($this->session->userdata('seller_logged_in')){ 
			//obtain the user details if logged in
			
			$data['users'] = $this->Traders->get_trader($email_address);	
		} 
		$messages_unread = $this->Messages->count_unread_messages($email_address);
				
		if($messages_unread == '' || $messages_unread == null){
			$messages_unread = 0;
		}
		$data['messages_unread'] = $messages_unread;
		
			//assign page title name
			$data['pageTitle'] = 'Listing';
			
			//assign page ID
			$data['pageID'] = 'cart';
						
			$this->load->view('pages/header', $data);
				
			$this->load->view('pages/listing_page', $data);
				
			$this->load->view('pages/footer');
		//}
			
	}

		
		
		public function logged_out() {

			//assign page title name
			$data['pageTitle'] = 'Logged Out';
			
			//assign page ID
			$data['pageID'] = 'logged_out';
			
			//load social header
			$this->load->view('pages/header', $data);
															
			//load main body
            $this->load->view('pages/logout_page', $data);
			
			//load social footer
			$this->load->view('pages/social-footer');
															
			
        } 		
	
	
		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
