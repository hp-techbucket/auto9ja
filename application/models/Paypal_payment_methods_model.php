<?php

class Paypal_payment_methods_model extends MY_Model {
    
    const DB_TABLE = 'paypal_payment_methods';
    const DB_TABLE_PK = 'id';

    
    /**
     * Type.
     * @var string 
     */
    public $type;
 
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
				
			$query = $this->db->insert('paypal_payment_methods', $data);
			if ($query){
				return true;
			}else {
				return false;
			}
		}	

		public function get_paypal($email){

			$this->db->where('user_email', $email);
			$payments = $this->db->get('paypal_payment_methods');
				
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
			$this->db->where('type', 'PayPal');
			$this->db->where('user_email', $email);
			$payments = $this->db->get('paypal_payment_methods');
				
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
			$query = $this->db->update('paypal_payment_methods', $data);
			
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
		public function is_unique($email){

			$this->db->where('user_email', $email);
			$results = $this->db->get('paypal_payment_methods');
				
			if($results->num_rows() == 0){
				return true;
			}else {
				return false;
			}
		}	
	
	
	
	
	
}

