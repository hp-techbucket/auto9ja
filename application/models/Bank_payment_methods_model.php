<?php

class Bank_payment_methods_model extends MY_Model {
    
    const DB_TABLE = 'bank_payment_methods';
    const DB_TABLE_PK = 'id';

    
    /**
     * Bank Name.
     * @var string 
     */
    public $bank_name;
 
     /**
     * Bank address.
     * @var string
     */
    public $bank_location; 

    /**
     * Account name.
     * @var string
     */
    public $account_name;
    
     /**
     * Account Number.
     * @var int
     */
    public $account_number;

    /**
     * Sort Code.
     * @var int 
     */
    public $sort_code;

    /**
     * Bank's SWIFT/BIC code.
     * @var string 
     */
    public $swift_bic;

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

	

	public function get_bank_details($email){
				
		$this->db->where('user_email', $email);
		$bank_details = $this->db->get('bank_payment_methods');
				
		if($bank_details->num_rows() > 0){
				  // we will store the results in the form of class methods by using $q->result()
				  // if you want to store them as an array you can use $q->result_array()
			foreach ($bank_details->result() as $row){
					$data[] = $row;
			}
			return $data;  
		}else{
			return false;
		}
	}			


	/***
	** Function to add bank details
	**
	***/	
	public function add_bank_details($data){
			
		$query = $this->db->insert('bank_payment_methods', $data);
		if ($query){
			return true;
		}else {
			return false;
		}
	}
				
		
	/**
	* Function to update bank details in db
	*
	*/	
	public function update_bank_details($data){
			
		$email_address = $this->session->userdata('email_address');
		$id = $this->input->post('id');
			
		$this->db->where('id', $id);
		$this->db->where('user_email', $email_address);
		$query = $this->db->update('bank_payment_methods', $data);
			
		if ($query){				
			return true;	
		}else {
			return false;
		}			
	}
		     		
 		/***
		** Function to see if bank details already exists
		**
		***/	
		public function isUnique_bank_details($bank_name,$account_name,$account_number){
			
			$email_address = $this->session->userdata('email_address');
			
			$this->db->where('LOWER(bank_name)', strtolower($bank_name));
			$this->db->where('LOWER(account_name)', strtolower($account_name));
			$this->db->where('account_number', $account_number);
			$this->db->where('user_email', $email_address);
			$results = $this->db->get('bank_payment_methods');
				
			if($results->num_rows() == 0){
				return true;
			}else {
				return false;
			}
		}
								       
	
	
	
	
	
	
}

