<?php

class Users_model extends MY_Model {
    
    const DB_TABLE = 'users';
    const DB_TABLE_PK = 'id';
	
	
     /**
     * User Profile photo.
     * @var string 
     */
    public $profile_photo;
       
    /**
     * User First Name.
     * @var string 
     */
    public $first_name;
    
     /**
     * User Last Name.
     * @var string
     */
    public $last_name;
    
     /**
     * User tagline.
     * @var string
     */
    public $tagline;
    
     /**
     * User Banner photo.
     * @var string
     */
    public $banner_photo;
            
    /**
     * User address.
     * @var string
     */
    public $address;
            
    /**
     * User Location.
     * @var string
     */
    public $location;
             
    /**
     * User city.
     * @var string
     */
    public $city;
	            
    /**
     * User postcode.
     * @var string
     */
    public $postcode;
	            
    /**
     * User state.
     * @var string
     */
    public $state;
	            
    /**
     * User country.
     * @var string
     */
    public $country;
			
	
    /**
     * User Email Address.
     * @var string
     */
    public $email_address;
    
     /**
     * User Mobile.
     * @var string
     */
    public $mobile;

     /**
     * User birthday.
     * @var date
     */
    public $birthday;
    
     /**
     * User Profile description.
     * @var string
     */
    public $profile_description;
     
     /**
     * User username.
     * @var string
     */
    public $username;
   
     /**
     * User password.
     * @var string
     */
    public $password;

     /**
     * User Account balance.
     * @var float
     */
    public $account_balance;
    
     /**
     * User Security question.
     * @var float
     */
    public $security_question;
    
     /**
     * User Security answer.
     * @var float
     */
    public $security_answer;

    /**
     * Date created.
     * @var string 
     */
    public $date_created;

    /**
     * User last login.
     * @var string 
     */
    public $last_login;

		
		/**
		* Function to check that the username and password 
		* exists in the database
		*/	
		public function can_log_in(){
			
			$this->db->where('username', $this->input->post('username'));
			$this->db->where('password', md5($this->input->post('password')));
			
			$query = $this->db->get('users');
			
			if ($query->num_rows() == 1){
				return true;
			} else {
				return false;
			}
			
		}
		
		/**
		* Function to add the user 
		* to the temp table in the database
		* @param $string Activation key
		*/		
		public function add_temp_user($activation_key, $code){
			
			//array of all post variables
			$data = array(

				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				//'address' => $this->input->post('address'),
				'city' => $this->input->post('city'),
				'country' => $this->input->post('country'),
				'username' => $this->input->post('username'),
				'email_address' => $this->input->post('email_address'),
				'mobile' => $this->input->post('mobile'),
				'password' => md5($this->input->post('password')),		
				'date_created' => date('Y-m-d H:i:s'),	
				'activation_key' => $activation_key,
				'activation_code' => $code,
			);
			
			$query = $this->db->insert('temp_users', $data);
			if ($query){
				return true;
			}else {
				return false;
			}
			
		}

				
		/**
		* Function to validate that the activation key
		* exists in the database
		* @param $string Activation key
		*/			
		public function is_valid_key($activation_key){
			
			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			$min_date = strtotime("-1 day", $date);
			
			//check for code 24 hour expiration
			$max_date = date('Y-m-d H:i:s', time());
			$min_date = date('Y-m-d H:i:s', $min_date);			
			
			$this->db->where('activation_key', $activation_key);
			$this->db->where("date_created BETWEEN '$min_date' AND '$max_date'", NULL, FALSE);
			
			$query = $this->db->get('temp_users');
			
			if ($query->num_rows() == 1){
				return true;
			} else {
				//if code expired delete record
				$this->db->where('activation_key', $activation_key);
				$this->db->delete('temp_users');				
				return false;
			}			
		}

				
		/**
		* Function to validate that the activation code
		* exists in the database
		* @param $string Activation code
		*/			
		public function is_valid_code($activation_code){
			
			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			$min_date = strtotime("-1 day", $date);
			
			//check for code 24 hour expiration
			$max_date = date('Y-m-d H:i:s', time());
			$min_date = date('Y-m-d H:i:s', $min_date);			
			
			$this->db->where('activation_code', $activation_code);
			$this->db->where("date_created BETWEEN '$min_date' AND '$max_date'", NULL, FALSE);
			
			$query = $this->db->get('temp_users');
			
			if ($query->num_rows() == 1){
				return true;
			}else {
				//if code expired delete record
				if($this->is_expired_code($activation_code)){
					$this->db->where('activation_code', $activation_code);
					$this->db->delete('temp_users');	
				}		
				return false;
			}			
		}

		public function is_expired_code($code){
			
			$this->db->where('activation_code', $code);
			$temp_users = $this->db->get('temp_users');
			$date_created = '';
			if($temp_users){
				$row = $temp_users->row();
				$date_created = $row->date_created;
			}
			
			$max_date = date('Y-m-d H:i:s', time());
			$max_date = strtotime($max_date);
			$min_date = strtotime($date_created);
			$time_diff = $max_date - $min_date;
			
			if ($time_diff > 86400){
				return true;
			}else {			
				return false;
			}			
		}

				
		/**
		* Function to add the user 
		* to the users table in the database
		* @param $string Activation key
		*/		
		public function add_user($activation_key){
			
			$this->db->where('activation_key', $activation_key);
			$temp_users = $this->db->get('temp_users');
			
			if($temp_users){
				$row = $temp_users->row();
				
				//array of all the row values returned
				$data = array(
					
					'first_name' => $row->first_name,
					'last_name' => $row->last_name,
					'address' => $row->address,
					'city' => $row->city,
					'postcode' => $row->postcode,
					'state' => $row->state,
					'country' => $row->country,
					'email_address' => $row->email_address,
					'mobile' => $row->mobile,
					'username' => $row->username,
					'password' => $row->password,
					'account_balance' => 0,							
					'date_created' => $row->date_created,
					
				);
				
				$did_add_user = $this->db->insert('users', $data);
			}
			if ($did_add_user){
				
				$this->db->where('activation_key', $activation_key);
				$this->db->delete('temp_users');
				return true;
				
			}else {
					return false;
			}
		}

		
		/**
		* Function to add the user 
		* to the users table in the database
		* @param $string Activation key
		*/		
		public function activate_user($code){
			
			$this->db->where('activation_code', $code);
			$temp_users = $this->db->get('temp_users');
			
			if($temp_users){
				$row = $temp_users->row();
				
				//array of all the row values returned
				$data = array(
					
					'first_name' => $row->first_name,
					'last_name' => $row->last_name,
					'address' => $row->address,
					'city' => $row->city,
					'postcode' => $row->postcode,
					'state' => $row->state,
					'country' => $row->country,
					'email_address' => $row->email_address,
					'mobile' => $row->mobile,
					'username' => $row->username,
					'password' => $row->password,
					'account_balance' => 0,							
					'date_created' => $row->date_created,
					
				);
				
				$did_add_user = $this->db->insert('users', $data);
			}
			if ($did_add_user){
				
				$this->db->where('activation_code', $code);
				$this->db->delete('temp_users');
				return true;
				
			}else {
					return false;
			}
		}
				
		/**
		* Function to add the user 
		* to the users table in the database
		* @param $string Activation key
		*/		
		public function insert_user($data){

			$query  = $this->db->insert('users', $data);
			
			if ($query ){
				return true;
			}else {
				return false;
			}
		}
		
		function get_user($username){
			
			$this->db->where('username', $username);
			$q = $this->db->get('users');
			
			if($q->num_rows() > 0){
				
			  // we will store the results in the form of class methods by using $q->result()
			  // if you want to store them as an array you can use $q->result_array()
			  foreach ($q->result() as $row)
			  {
				$data[] = $row;
			  }
			  return $data;
			}
		}


		 /**
		 * Function to get all posts
		 * @var string
		 */		
		public function get_users($limit, $start){
			
			$this->db->limit($limit, $start);			
			$this->db->order_by('date_created','DESC');
			$users = $this->db->get('users');
				
			if($users->num_rows() > 0){
					
				  // we will store the results in the form of class methods by using $q->result()
				  // if you want to store them as an array you can use $q->result_array()
				foreach ($users->result() as $row)
				{
					$data[] = $row;
				}
				return $data;
				  
			}else{
				return false;
			}
		}

		 /**
		 * Function to count users
		 * @var string
		 */			
		public function count_all_users(){
			
			$count_users = $this->db->get('users');
				
			if($count_users->num_rows() > 0)	{
					
				$count = $count_users->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}
		
		
		function get_user_profile($details){
			
			$user = explode('-',$details);
			$fname = $user[0];
			$lname = $user[1];
			$user_id = $user[2];
			
			$this->db->where('first_name', $fname);
			$this->db->where('last_name', $lname);			
			$this->db->where('id', $user_id);
			
			$query = $this->db->get('users');
			
			if($query->num_rows() == 1){
				
			  // we will store the results in the form of class methods by using $q->result()
			  // if you want to store them as an array you can use $q->result_array()
			  foreach ($query->result() as $row)
			  {
				$data[] = $row;
			  }
			  return $data;
			}
		}		


		public function user_update($data, $email){
			
			$this->db->where('email_address', $email);
			
			$query = $this->db->update('users', $data);
			
			if ($query){	
				return true;	
			}else {		
				return false;			
			}			
		}			
		
		public function update_user($data){
			
			$username = $this->session->userdata('username');
			
			$this->db->where('username', $username);
			
			$query = $this->db->update('users', $data);
			
			if ($query){	
				return true;	
			}else {		
				return false;			
			}			
		}		
		


		
		

}