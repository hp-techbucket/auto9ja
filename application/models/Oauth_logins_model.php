<?php

class Oauth_logins_model extends MY_Model {
    
    const DB_TABLE = 'oauth_logins';
    const DB_TABLE_PK = 'id';


	
	/**
     * OAuth Provider name.
     * @var string 
     */
    public $oauth_provider;
		
	/**
     * IP address of the user.
     * @var string 
     */
    public $ip_address;
	
	/**
     * username of the user.
     * @var string 
     */
    public $username;
	
	/**
     * Email Address of the user.
     * @var string 
     */
    public $email_address;


	 /**
     * Time of User Login.
     * @var string 
     */
    public $login_time;
	

	public function insert_login($username, $email, $provider){
		
			//obtain users ip address
			$ipaddress = $this->ip();			
			
			//array of all login posts
			$data = array(
				'oauth_provider' => $provider,
				'ip_address' => $ipaddress,
				'username' => $username,
				'email_address' => $email,
				'login_time' => date('Y-m-d H:i:s')
			);	
			
			$query = $this->db->insert('oauth_logins', $data);
			if ($query){
				return true;
			}else {
				return false;
			}		
	}

	
		
		/****
		** Function to get a user from the database
		****/
		function get_last_login($username){
			
			$this->db->limit(1);
			$this->db->select('login_time');
			$this->db->from('oauth_logins');
			$this->db->where('username', $username);
			$this->db->order_by('id','DESC');
			$q = $this->db->get();
			
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
	
	
	public function update_login_time($email, $login_time){
	
		//array of session variables
		$data = array(	
			'last_login' => $login_time,
		);	
		
		$this->db->where('email_address', $email);
		$query = $this->db->update('users', $data);
			
		if ($query){
			return true;
		}else{
			return false;
		}		
	}
	
	/*
	public function last_login_time($email){
		
			$this->db->limit(1);
			$this->db->where('email_address', $email);
			$this->db->order_by('login_time','DESC');
			
			$last_login = $this->db->get('fblogins');
			
			if($last_login->num_rows() > 0){
				
				$login_time = $last_login->num_rows();
				return $login_time;
			}else {
				return false;
			}			
		
	} */

	/**
	* Function to check if the user 
	* is logging in for the first time
	*  
	*/			
	public function first_time_log_in($email){
			
		$this->db->where('email_address', $email);
			
		$query = $this->db->get('oauth_logins');
			
		if ($query->num_rows() == 0){
			return true;
		} else {
			return false;
		}
	}		
		
	/****
	** Function to get a user from the database
	****/
	function get_username($email){
			
		$this->db->select('username');
		$this->db->from('oauth_logins');
		$this->db->where('email_address', $email);
		//$query = $this->db->get();
			
		$q = $this->db->get();
			
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
	
	
	
	
	
	
	
	
	
}