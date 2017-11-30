<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

	class Oauth2login extends CI_Controller { 

		public function __construct() { 

			parent::__construct(); 
			
			$fb_config = array(
			'appId' => '1555023334803063',
			'secret' => 'd852b5f40578ae908edfdb98b577848a'
			);
			// Load facebook library and pass associative array which contains appId and secret key
			$this->load->library('facebook', $fb_config);
		
			// Include two files from google-php-client library in controller
			//require_once APPPATH . "libraries/google-api-php-client/vendor/autoload.php";
			include_once APPPATH . "libraries/google-api-php-client/src/Google/Client.php";
			include_once APPPATH . "libraries/google-api-php-client/src/Google/Service/Oauth2.php";
			
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
			$objOAuthService = new Google_Service_Oauth2($client);

		}




		/**
		* Function to handle facebook login
		*
		*/			
        public function facebookcallback() {
			
			$user = $this->facebook->getUser();
        
			if ($user) {
				try {
					$data['user_profile'] = $this->facebook->api('/me');
				} catch (FacebookApiException $e) {
					$user = null;
				}
			}else {
				$this->facebook->destroySession();
			}
			
			if ($user) {
				$data['user_profile'] = $this->facebook->api('/me?fields=id,birthday,first_name,last_name,email');
				$accessToken = $this->facebook->getAccessToken();
				$params = array('next' => base_url('account/logout/'), 'access_token' => $accessToken);
				$data['logout_url'] = $this->facebook->getLogoutUrl($params);
				//$data['logout_url'] = site_url('welcome/logout'); // Logs off application
				
				
				$data['user_id'] = $user;
				//$name = $data['user_profile']['name'];
				//$email = ;
				//$profile_picture = $data['user_profile']['profile_picture'];
				

				
				$email = $data['user_profile']['email'];
				$first_name = $data['user_profile']['first_name'];
				$last_name = $data['user_profile']['last_name'];
				$birthday = $data['user_profile']['birthday'];
				
				$username = '';
				
				if($this->Oauth_logins->first_time_log_in($email)){
					
					$date = new DateTime();
					
					$month = $date->format('m');
					$month = sprintf("%02d", $month);
					$year = $date->format('Y');
					
					
					if($birthday != 0000-00-00 || $birthday != '' || $birthday != null ){
							
							$month = date("m",strtotime($birthday));
							$month = sprintf("%02d", $month);
							$year = date("Y",strtotime($birthday));
							$year = substr($year, -2);
					}
					$username = $first_name[0] .''.$last_name[0].''.$month.''.$year;
					$i = 1;
					
					while($this->Users->check_account_exists($username)){
						$username = $username .''.$i;
						$i++;
					}
				}else{
					$users = $this->Oauth_logins->get_username($email);
					foreach($users as $u){	
						$username = $u->username;
					}
				}
				//$login = $this->db->limit(1)->where(array('username'=>$username))->order_by('id','desc')->get('logins')->result_array();
				$last_login = '';
				$logins = $this->Oauth_logins->get_last_login($username);
				foreach($logins as $login){	
						$last_login = $login->login_time;
				}
				$login_data = array(
					//'last_login' => $login['login_time'],
					'last_login' => $last_login,
				);
				
				//record login in db
				$this->Users->user_update($login_data, $email);
				
				$provider = 'Facebook';
				$this->Oauth_logins->insert_login($username, $email, $provider);
				
				//check if similar user already exists
				if(!$this->Users->check_email_exists($email)){
					
					$insert = array(
						'profile_photo' => 'https://graph.facebook.com/'.$data['user_profile']['id'].'/picture',
						'first_name' => $first_name,
						'last_name' => $last_name,
						'username' => $username,
						'email_address' => $email,
						'location' => '',
						'mobile' => '',
						'account_balance' => 0,							
						'date_created' => date('Y-m-d H:i:s'),	
					);
					//create new user
					$this->Users->insert_user($insert);
				}
				
				//data to set session
				$userdata = array(
					'username' => $username,
					'access_token' => $accessToken,
					'logged_in' => 1,
					'login_time' => date('Y-m-d H:i:s')
				);
		
				//set login session
				$this->session->set_userdata($userdata);
				redirect('account/dashboard');
			}else{
				//$error = 'Error: '. $this->facebook->getError() .'<br/>';
				//$error .= 'Code: '. $this->facebook->getErrorCode() .'<br/>';
				//$error .= 'Reason: '. $this->facebook->getErrorReason() .'<br/>';
				//$error .= 'Description: '. $this->facebook->getErrorDescription() .'<br/>';

				//$this->session->set_flashdata('error', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".commentDivError").fadeOut("slow"); }, 5000);</script><div class="commentDivError text-center"><i class="fa fa-check-circle"></i>'.$error.'</div>');
						
				
				redirect('main/social');
			}			
		
		}

		/**
		* Function to handle google login
		*
		*/			
        public function googlecallback() {

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
			$objOAuthService = new Google_Service_Oauth2($client);			
			
			// Add Access Token to Session
			if (isset($_GET['code'])) {
				//$client->setHttpClient(new GuzzleHttp\Client(['verify'=>false]));
				$client->authenticate($_GET['code']);
				$_SESSION['access_token'] = $client->getAccessToken();
				header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
			}
			
			// Set Access Token to make Request
			if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
				$client->setAccessToken($_SESSION['access_token']);
			}
			
			// Get User Data from Google and store them in $data
			if ($client->getAccessToken()) {
				$userData = $objOAuthService->userinfo->get();
				$data['userData'] = $userData;
				$_SESSION['access_token'] = $client->getAccessToken();
				
				$profile_avatar = $userData->picture;
				$first_name = $userData->given_name;
				$last_name = $userData->family_name;
				$email = $userData->email;
				$username = '';
				
				if($this->Oauth_logins->first_time_log_in($email)){
					
					$date = new DateTime();
					
					$month = $date->format('m');
					$month = sprintf("%02d", $month);
					$year = $date->format('Y');
					$year = substr($year, -2);
					
					$username = $first_name[0] .''.$last_name[0].''.$month.''.$year;
					$i = 1;
					
					while($this->Users->check_account_exists($username)){
						$username = $username .''.$i;
						$i++;
					}
				}else{
					$users = $this->Oauth_logins->get_username($email);
					foreach($users as $u){	
						$username = $u->username;
					}
				}
				
				//data to set session
				$userdata = array(
					'username' => $username,
					'access_token' => $client->getAccessToken(),
					'logged_in' => 1,
					'login_time' => date('Y-m-d H:i:s')
				);
				
				//$login = $this->db->limit(1)->where(array('email_address'=>$email))->order_by('id','desc')->get('oauth_logins')->result_array();
				$last_login = '';
				$logins = $this->Oauth_logins->get_last_login($username);
				foreach($logins as $login){	
						$last_login = $login->login_time;
				}
				$login_data = array(
					//'last_login' => $login['login_time'],
					'last_login' => $last_login,
				);

				//record login in db
				$this->Users->user_update($login_data, $email);
				
				$provider = 'Google';
				
				$this->Oauth_logins->insert_login($username, $email, $provider);
				
				//check if similar user already exists
				if(!$this->Users->check_email_exists($email)){
					
					$insert = array(
						'profile_photo' => $userData->picture,
						'first_name' => $first_name,
						'last_name' => $last_name,
						'username' => $username,
						'email_address' => $email,
						'location' => '',
						'mobile' => '',
						'account_balance' => 0,							
						'date_created' => date('Y-m-d H:i:s'),	
					);
					//create new user
					$this->Users->insert_user($insert);
				}
				//set login session
				$this->session->set_userdata($userdata);
				redirect('account/dashboard');
								
			} 
			else{
				$authUrl = $client->createAuthUrl();
				$data['authUrl'] = $authUrl;
				redirect('main/social');
			}
		}
			
		



	} 

	
	
?>






