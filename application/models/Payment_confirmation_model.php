<?php

class Payment_confirmation_model extends MY_Model {
    
    const DB_TABLE = 'payment_confirmation';
    const DB_TABLE_PK = 'id';

     
    /**
     * Invoice reference number.
     * @var string 
     */
    public $order_reference;
    
    /**
     * payment_details.
     * @var string 
     */
    public $payment_details;

    /**
     * Amount paid.
     * @var int 
     */
    public $amount_paid;

    /**
     * Payment reference.
     * @var string 
     */
    public $payment_reference;

    /**
     * User email.
     * @var string 
     */
    public $payee_email;

    /**
     * Date added.
     * @var string 
     */
    public $confirmation_date;


		
		public function count_payments($email){

			$this->db->where('payee_email', $email);
			$count_payments = $this->db->get('payment_confirmation');
				
			if($count_payments->num_rows() > 0)	{
					
				$count = $count_payments->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}
		
		public function count_all_confirmations(){

			$count_transactions = $this->db->get('payment_confirmation');
				
			if($count_transactions->num_rows() > 0)	{
					
				$count = $count_transactions->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}			


		public function get_all_confirmations($limit, $start){
				
			$this->db->limit($limit, $start);
			$this->db->order_by('date','DESC');
			$confirmations = $this->db->get('payment_confirmation');
				
			if($confirmations->num_rows() > 0){
					
				  // we will store the results in the form of class methods by using $q->result()
				  // if you want to store them as an array you can use $q->result_array()
				foreach ($confirmations->result() as $row)
				{
					$data[] = $row;
				}
				return $data;
				  
			}else{
				return false;
			}
		}		
	

	public function get_payment_confirmation($email){
				
		$this->db->where('payee_email', $email);
		$bank_details = $this->db->get('payment_confirmation');
				
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
	** Function to add payment confirmation
	**
	***/	
	public function add_payment_confirmation($data, $order_reference){
		
		$query = $this->db->insert('payment_confirmation', $data);
		
		if ($query){
			//update the invoice
			$update	= array(
				'payment_status' => 'Paid',
			);	
			$this->db->where('order_reference', $order_reference);
			$this->db->update('orders', $update);
			
			//create new transaction
			$payment_date = $this->session->flashdata('payment_date');
			$payment_amount = $this->input->post('payment_amount');
			//remove non-numbers from post
			$amount = preg_replace("/[^\d-.]+/","", $payment_amount);
			
			//transaction 
			$transaction = $this->input->post('transaction_type');
			
			//store same reference
			$reference = $order_reference;
			
			//obtain users ip address
			//$ip_address = $this->ip();
			
			//data for db
			$transactions = array(
					'reference' => $reference,
					'amount' => '- $'.number_format($amount, 2),
					'transaction' => $transaction,
					'note' => 'Deposit',
					//'ip_address' => $ip_address,
					'user_email' => $email_address,
					'date' => $deposit_date,
			);
			$this->db->insert('transactions', $transactions);
				
			
			return true;
		}else {
			return false;
		}
	}
				
   		
 		/***
		** Function to see if details already exists
		**
		***/	
		public function is_unique($ref,$payment_details,$amount){
			
			$email_address = $this->session->userdata('email_address');
			
			$this->db->where('LOWER(order_reference)', strtolower($ref));
			$this->db->where('LOWER(payment_details)', strtolower($payment_details));
			$this->db->where('amount', $amount);
			$this->db->where('payee_email', $email_address);
			$results = $this->db->get('payment_confirmation');
				
			if($results->num_rows() == 0){
				return true;
			}else {
				return false;
			}
		}
								       
			
		/**
		* Function to search confirmations
		* @var string
		*/			
		public function search($keyword, $limit, $offset){
			
			$email_address = $this->session->userdata('email_address');
			
			$this->db->like('order_reference',$keyword);
			$this->db->or_like('payment_details',$keyword);
			
			$this->db->or_like('amount_paid',$keyword);
			$this->db->or_like('payment_reference',$keyword);
			$this->db->where('payee_email', $email_address);
			$this->db->limit($limit, $offset);
			
			$this->db->order_by('confirmation_date','DESC');
			$query = $this->db->get('payment_confirmation');
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
		public function count_search($keyword){
			
			$email_address = $this->session->userdata('email_address');
			
			$this->db->like('order_reference',$keyword);
			$this->db->or_like('payment_details',$keyword);
			
			$this->db->or_like('amount_paid',$keyword);
			$this->db->or_like('payment_reference',$keyword);
			$this->db->where('payee_email', $email_address);
			
			$count_confirmations = $this->db->get('payment_confirmation');
				
			if($count_confirmations->num_rows() > 0)	{
					
				$count = $count_confirmations->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}	
		
			
		/**
		* Function to search confirmations
		* @var string
		*/			
		public function admin_search($keyword, $limit, $offset){
			
			$this->db->like('order_reference',$keyword);
			$this->db->or_like('payment_details',$keyword);
			
			$this->db->or_like('amount_paid',$keyword);
			$this->db->or_like('payment_reference',$keyword);
			$this->db->or_like('payee_email',$keyword);
			$this->db->limit($limit, $offset);
			
			$this->db->order_by('confirmation_date','DESC');
			$query = $this->db->get('payment_confirmation');
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
		public function count_admin_search($keyword){
			
			$this->db->like('order_reference',$keyword);
			$this->db->or_like('payment_details',$keyword);
			$this->db->or_like('amount_paid',$keyword);
			$this->db->or_like('payment_reference',$keyword);
			$this->db->or_like('payee_email',$keyword);
			
			$count_confirmations = $this->db->get('payment_confirmation');
				
			if($count_confirmations->num_rows() > 0)	{
					
				$count = $count_confirmations->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}	
	
	
	
	
}

