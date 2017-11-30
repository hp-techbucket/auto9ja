<?php

class Customers_model extends MY_Model {
    
    const DB_TABLE = 'customers';
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
     * User Mobile.
     * @var string
     */
    public $mobile;
			
	
    /**
     * User Email Address.
     * @var string
     */
    public $email_address;
 
     /**
     * User password.
     * @var string
     */
    public $password;

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
     * User Account balance.
     * @var float
     */
    public $account_balance;
		
    /**
     * customer rating.
     * @var int
     */
    public $rating;
	     
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
     * activation status.
     * @var string
     */
    public $activation_status;

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
		* Function to check that the email_address and password 
		* exists in the database
		*/	
		public function can_log_in(){
			
			$this->db->where('email_address', $this->input->post('email_address'));
			$this->db->where('password', md5($this->input->post('password')));
			
			$query = $this->db->get('customers');
			
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
		public function add_temp_customers($activation_key){
			
			//array of all post variables
			$data = array(

				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'email_address' => $this->input->post('email_address'),
				'password' => md5($this->input->post('password')),		
				'date_created' => date('Y-m-d H:i:s'),	
				'activation_key' => $activation_key,
			);
			
			$query = $this->db->insert('temp_customers', $data);
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
		public function is_valid_key($activation_key,$email){
			
			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			$min_date = strtotime("-1 day", $date);
			
			//check for code 24 hour expiration
			$max_date = date('Y-m-d H:i:s', time());
			$min_date = date('Y-m-d H:i:s', $min_date);			
					
			$this->db->where('email_address', $email);
			$this->db->where('activation_key', $activation_key);
			$this->db->where("date_created BETWEEN '$min_date' AND '$max_date'", NULL, FALSE);
			
			$query = $this->db->get('temp_customers');
			
			if ($query->num_rows() == 1){
				return true;
			} else {
				//if code expired delete record
				$this->db->where('email_address', $email);
				$this->db->where('activation_key', $activation_key);
				$this->db->delete('temp_customers');				
				return false;
			}			
		}

				
		/**
		* Function to validate that the activation code
		* exists in the database
		* @param $string Activation code
		*/			
		public function is_valid_code($activation_code,$email){
			
			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			$min_date = strtotime("-1 day", $date);
			
			//check for code 24 hour expiration
			$max_date = date('Y-m-d H:i:s', time());
			$min_date = date('Y-m-d H:i:s', $min_date);			
			
			$this->db->where('email_address', $email);
			$this->db->where('activation_code', $activation_code);
			$this->db->where("date_created BETWEEN '$min_date' AND '$max_date'", NULL, FALSE);
			
			$query = $this->db->get('temp_customers');
			
			if ($query->num_rows() == 1){
				return true;
			}else {
				//if code expired delete record
				if($this->is_expired_code($activation_code,$email)){
					$this->db->where('activation_code', $activation_code);
					$this->db->delete('temp_customers');	
				}		
				return false;
			}			
		}

		public function is_expired_code($code,$email){
			$date_created = '';
			/*$this->db->where('activation_code', $code);
			$this->db->where('email_address', $email);
			$temp_customers = $this->db->get('temp_customers');
			$date_created = '';
			
			if($temp_customers->num_rows() > 0){
				//$row = $temp_customers->row();
				//$date_created = $row->date_created;
				foreach ($customers->result() as $row)
				{
					$data[] = $row;
				}
			}*/
			$result = $this->db->select('date_created')->from('temp_customers')->where('activation_code', $code)->where('email_address', $email)->get()->row();
				
			if(!empty($result)){
				$date_created = $result->date_created;
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
		public function add_customers($activation_key, $email){
			
			$this->db->where('email_address', $email);
			$this->db->where('activation_key', $activation_key);
			$temp_customers = $this->db->get('temp_customers');
			
			if($temp_customers){
				$row = $temp_customers->row();
				
				//array of all the row values returned
				$data = array(
					
					'first_name' => $row->first_name,
					'last_name' => $row->last_name,
					'email_address' => $row->email_address,
					'password' => $row->password,						
					'date_created' => $row->date_created,
					
				);
				
				$did_add_customer = $this->db->insert('customers', $data);
			}
			if ($did_add_customer){
				
				$this->db->where('email_address', $email);
				$this->db->where('activation_key', $activation_key);
				$this->db->delete('temp_customers');
				return true;
				
			}else {
					return false;
			}
		}

		
		/**
		* Function to add the customer 
		* to the customers table in the database
		* @param $string Activation key
		*/		
		public function activate_user($code){
			
			$this->db->where('activation_code', $code);
			$temp_customers = $this->db->get('temp_customers');
			$did_add_customer = false;
			
			if($temp_customers){
				$row = $temp_customers->row();
				
				//array of all the row values returned
				$data = array(
					
					'first_name' => $row->first_name,
					'last_name' => $row->last_name,
					'email_address' => $row->email_address,
					'password' => $row->password,						
					'date_created' => $row->date_created,
					
				);
				
				$did_add_customer = $this->db->insert('customers', $data);
			}
			if ($did_add_customer){
				
				$this->db->where('activation_code', $code);
				$this->db->delete('temp_customers');
				return true;
			}
				return false;
		}
				
		/**
		* Function to add the customer
		* to the customers table in the database
		* @param $string Activation key
		*/		
		public function insert_customer($data){

			$query  = $this->db->insert('customers', $data);
			
			if ($query ){
				return true;
			}else {
				return false;
			}
		}
		
		function get_customer($email){
			
			$this->db->where('email_address', $email);
			$q = $this->db->get('customers');
			
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
		 * Function to get all customers
		 * @var string
		 */		
		public function get_customers($limit, $start){
			
			$this->db->limit($limit, $start);			
			$this->db->order_by('date_created','DESC');
			$customers = $this->db->get('customers');
				
			if($customers->num_rows() > 0){
					
				  // we will store the results in the form of class methods by using $q->result()
				  // if you want to store them as an array you can use $q->result_array()
				foreach ($customers->result() as $row)
				{
					$data[] = $row;
				}
				return $data;
				  
			}else{
				return false;
			}
		}

		 /**
		 * Function to count customers
		 * @var string
		 */			
		public function count_all_customers(){
			
			$count_customers = $this->db->get('customers');
				
			if($count_customers->num_rows() > 0)	{
					
				$count = $count_customers->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}
		
		
		function get_customer_profile($user_id = null, $details = ''){
			
			$user = explode('-',$details);
			$fname = $user[0];
			$lname = $user[1];
			//$user_id = $user[2];
			
			$this->db->where('first_name', $fname);
			$this->db->where('last_name', $lname);			
			$this->db->where('id', $user_id);
			
			$query = $this->db->get('customers');
			
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


		public function customer_update($data, $email){
			
			$this->db->where('email_address', $email);
			
			$query = $this->db->update('customers', $data);
			
			if ($query){	
				return true;	
			}else {		
				return false;			
			}			
		}			
		
		public function update_customer($data){
			
			$email = $this->session->userdata('email_address');
			
			$this->db->where('email_address', $email);
			
			$query = $this->db->update('customers', $data);
			
			if ($query){	
				return true;	
			}else {		
				return false;			
			}			
		}		
		
		/***
		** Function to convert timestamp to time ago
		**
		***/
		public function time_elapsed_string($ptime)
		{
			$etime = time() - $ptime;

			if ($etime < 1)
			{
				return '0 seconds';
			}

			$a = array( 365 * 24 * 60 * 60  =>  'year',
						 30 * 24 * 60 * 60  =>  'month',
							  24 * 60 * 60  =>  'day',
								   60 * 60  =>  'hour',
										60  =>  'minute',
										 1  =>  'second'
						);
			$a_plural = array( 'year'   => 'years',
							   'month'  => 'months',
							   'day'    => 'days',
							   'hour'   => 'hours',
							   'minute' => 'minutes',
							   'second' => 'seconds'
						);

			foreach ($a as $secs => $str)
			{
				$d = $etime / $secs;
				if ($d >= 1)
				{
					$r = round($d);
					return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
				}
			}
		}

		public function date_time_string($datetime, $full = false) {
			
				$today = time();    
				$createdday= strtotime($datetime); 
				$datediff = abs($createdday - $today);  
				$difftext="";  
				$years = floor($datediff / (365*60*60*24));  
				$months = floor(($datediff - $years * 365*60*60*24) / (30*60*60*24));  
				$days = floor(($datediff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));  
				$hours= floor($datediff/3600);  
				$minutes= floor($datediff/60);  
				$seconds= floor($datediff);  
				
				//year checker  
				if($difftext==""){  
					if($years>1)  
						$difftext=$years." years";  
					elseif($years==1)  
						$difftext=$years." year";  
				}  
				
				//month checker  
				if($difftext==""){  
					if($months>1)  
						$difftext=$months." months";  
					elseif($months==1)  
						$difftext=$months." month";  
				}  
				
				//day checker  
				if($difftext==""){  
					if($days>1)  
						$difftext=$days." days";  
					elseif($days==1)  
						$difftext=$days." day";  
				}  
				
				//hour checker  
				if($difftext==""){  
					if($hours>1)  
						$difftext=$hours." hours";  
					elseif($hours==1)  
						$difftext=$hours." hour";  
				}  
				
				//minutes checker  
				if($difftext==""){  
					if($minutes>1)  
						$difftext=$minutes." minutes";  
					elseif($minutes==1)  
						$difftext=$minutes." minute";  
				} 
				
				//seconds checker  
				if($difftext==""){  
					if($seconds>1)  
						$difftext=$seconds." seconds";  
					elseif($seconds==1)  
						$difftext=$seconds." second";  
				}  
				return $difftext;  
		}			
		
		//function to calculate 
		//how much of customer profile
		//is incomplete
		public function profile_completion($email){
			
			$percentage = 0;
			$empty_list = '';
			
			//$sql ="SELECT * FROM customers WHERE email_address=$email";
			//$query = $this->db->query($sql);
			$this->db->where('email_address', $email);
			$query = $this->db->get('customers');

			if ($query->num_rows() > 0){ 
				
				//initialise variable
				$notEmpty = 0;
				
				//numbers of columns to alidate
				$totalField = 15;
				foreach ($query->result() as $row){
					
					$notEmpty +=  ($row->tagline != '') ? 1 : 0;
					
					if($row->tagline == ''){
						$empty_list .= 'Please provide your tagline; ';
					}
					$notEmpty +=  ($row->profile_photo != '') ? 1 : 0;
					if($row->profile_photo == ''){
						$empty_list .= 'Please provide your Profile Photo; ';
					}
					$notEmpty +=  ($row->first_name != '') ? 1 : 0;
					if($row->first_name == ''){
						$empty_list .= 'Please provide your First Name; ';
					}
					$notEmpty +=  ($row->last_name != '') ? 1 : 0;
					if($row->last_name == ''){
						$empty_list .= 'Please provide your Last Name; ';
					}
					$notEmpty +=  ($row->address != '') ? 1 : 0;
					if($row->address == ''){
						$empty_list .= 'Please provide your address; ';
					}
					$notEmpty +=  ($row->city != '') ? 1 : 0;
					if($row->city == ''){
						$empty_list .= 'Please provide your city; ';
					}
					$notEmpty +=  ($row->postcode != '') ? 1 : 0;
					if($row->postcode == ''){
						$empty_list .= 'Please provide your postcode; ';
					}
					$notEmpty +=  ($row->state != '') ? 1 : 0;
					if($row->state == ''){
						$empty_list .= 'Please provide your state; ';
					}
					$notEmpty +=  ($row->country != '') ? 1 : 0;
					if($row->country == ''){
						$empty_list .= 'Please provide your country; ';
					}
					$notEmpty +=  ($row->email_address != '') ? 1 : 0;
					if($row->email_address == ''){
						$empty_list .= 'Please provide your email; ';
					}
					$notEmpty +=  ($row->mobile != '') ? 1 : 0;
					if($row->mobile == ''){
						$empty_list .= 'Please provide your mobile number; ';
					}
					$notEmpty +=  ($row->birthday != '') ? 1 : 0;
					if($row->birthday == ''){
						$empty_list .= 'Please provide your birthday; ';
					}
					$notEmpty +=  ($row->profile_description != '') ? 1 : 0;
					if($row->profile_description == ''){
						$empty_list .= 'Please provide a description; ';
					}
					$notEmpty +=  ($row->security_question != '') ? 1 : 0;
		
					$notEmpty +=  ($row->security_answer != '') ? 1 : 0;
						
				}
				$percentage = $notEmpty/$totalField *100;
			}
			return array(round($percentage).'%', $empty_list);
			//return $notEmpty;
		}

		
		//function to check if user has set 
		//their security question and answer
		//after log in
		public function check_isset_security_info(){
			
				$email = $this->session->userdata('email_address');
				
				$security_question = '';
				
				$user = $this->get_customer($email);
				
				foreach($user as $u){	
					$security_question = $u->security_question;
				}					
				if($security_question == '' || $security_question == null){
					return true;
				}else{
					return false;
				}	
		} 		


		/* Function to check that the memorable answer 
		* exists in the database
		*/	
		public function answer_exists(){
			
			$security_answer = $this->input->post('security_answer');

			$this->db->like('LOWER(security_answer)', strtolower($security_answer));
			
			$query = $this->db->get('customers');
			
			if ($query->num_rows() == 1){
				return true;
			} else {
				return false;
			}
			
		}

		/* Function to check that the email address 
		* exists in the database
		*/	
		public function email_exists(){
			
			$this->db->where('email_address', $this->input->post('email_address'));
			
			$query = $this->db->get('customers');
			
			if ($query->num_rows() == 1){
				return true;
			} else {
				return false;
			}
		}		
				
		/**
		* Function to search questions
		* @var string
		*/			
		public function search_customers($keyword, $limit, $offset){
			
			$this->db->or_like('first_name',$keyword);
			$this->db->or_like('last_name',$keyword);
			$this->db->or_like('address',$keyword);
			$this->db->or_like('city',$keyword);
			$this->db->or_like('state',$keyword);
			$this->db->or_like('postcode',$keyword);
			$this->db->or_like('country',$keyword);
			$this->db->limit($limit, $offset);

			$this->db->order_by('id','DESC');
			$query = $this->db->get('customers');
			if($query->num_rows() > 0){
					
				// we will store the results in the form of class methods by using $q->result()
				// if you want to store them as an array you can use $q->result_array()
				foreach ($query->result() as $row){
					$data[] = $row;
				}
				return $data;
			}
			return false;
		}					

		 /**
		 * Function to count search result
		 * @var string
		 */			
		public function count_search_customers($keyword){
			
			$this->db->or_like('first_name',$keyword);
			$this->db->or_like('last_name',$keyword);
			$this->db->or_like('address',$keyword);
			$this->db->or_like('city',$keyword);
			$this->db->or_like('state',$keyword);
			$this->db->or_like('postcode',$keyword);
			$this->db->or_like('country',$keyword);
			$count_customers = $this->db->get('customers');
				
			if($count_customers->num_rows() > 0)	{
					
				$count = $count_customers->num_rows();
				return $count;
			}else {
				return false;
			}				
		}	
	
		
		

}