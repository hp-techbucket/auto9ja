<?php

class Paypal_accounts_model extends MY_Model {
    
    const DB_TABLE = 'paypal_accounts';
    const DB_TABLE_PK = 'id';


     /**
     * PayPal email.
     * @var string
     */
    public $PayPal_email; 

    /**
     * User email.
     * @var string 
     */
    public $user_email;

    /**
     * Date added.
     * @var string 
     */
    public $date_added;

	

		/***
		** Function to add paypal details
		**
		***/	
		public function add_paypal($data){
				
			$query = $this->db->insert('paypal_accounts', $data);
			if ($query){
				return true;
			}else {
				return false;
			}
		}	

		public function get_paypal($email){

			$this->db->where('user_email', $email);
			$payments = $this->db->get('paypal_accounts');
				
			if($payments->num_rows() > 0){
				  // we will store the results in the form of class methods by using $q->result()
				  // if you want to store them as an array you can use $q->result_array()
				foreach ($payments->result() as $row)
				{
					$data[] = $row;
				}
				return $data; 
			}else{
				return false;
			}
		}


		public function get_paypal_info($id,$email){
			
			$this->db->where('id', $id);	
			$this->db->where('user_email', $email);
			$payments = $this->db->get('paypal_accounts');
				
			if($payments->num_rows() > 0){
				foreach ($payments->result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}else{
				return false;
			}
		}	
		
		
		
		/**
		* Function to update paypal in db
		*
		*/	
		public function update_paypal($data){
			
			$email_address = $this->session->userdata('email_address');
			$id = $this->input->post('id');
			
			$this->db->where('id', $id);
			$this->db->where('user_email', $email_address);
			$query = $this->db->update('paypal_accounts', $data);
			
			if ($query){				
				return true;	
			}else {
				return false;
			}			
		}
		             
 		/***
		** Function to see if user already has a Paypal account stored
		**
		***/	
		public function account_limit($email){

			$this->db->where('user_email', $email);
			$results = $this->db->get('paypal_accounts');
				
			if($results->num_rows() == 0){
				return true;
			}else {
				return false;
			}
		}	
					
		/***
		** Function to mask email
		**
		***/		
		public function mask_email($email){
			/*
			Author: Fed
			Simple way of masking emails
			*/
			
			$char_shown = 3;
			
			$mail_parts = explode("@", $email);
			$username = $mail_parts[0];
			$len = strlen( $username );
			
			if( $len <= $char_shown ){
				return implode("@", $mail_parts );	
			}
			
			//Logic: show asterisk in middle, but also show the last character before @
			$mail_parts[0] = substr( $username, 0 , $char_shown )
				. str_repeat("*", $len - $char_shown - 1 )
				. substr( $username, $len - $char_shown + 2 , 1  )
				;
				
			return implode("@", $mail_parts );
		}

		function email_mask($email) 
		{ 
				$mask_char = '*';
				$percent = 50;
				list($user, $domain) = preg_split("/@/", $email); 

				$len = strlen($user); 
				$mask_count = floor( $len * $percent /100 ); 

				$offset = floor( ( $len - $mask_count ) / 2 ); 

				$masked = substr( $user, 0, $offset ) 
						.str_repeat( $mask_char, $mask_count ) 
						.substr( $user, $mask_count+$offset ); 
				
				$domain_len = strlen($domain);
				$mask_domain_count = floor($domain_len * 40 /100);
				$domain_offset = floor( ($domain_len - $mask_domain_count) / 2 ); 
				
				$masked_domain = substr($domain, 0, $domain_offset ) 
						.str_repeat( $mask_char, $mask_domain_count) 
						.substr($domain, $mask_domain_count+$domain_offset );
						
				return( $masked.'@'.$masked_domain); 
		} 
	
				
	
	
	
	
}

