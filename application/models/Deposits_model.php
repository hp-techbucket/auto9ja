<?php

class Deposits_model extends MY_Model {
    
    const DB_TABLE = 'deposits';
    const DB_TABLE_PK = 'id';
	
	
     /**
     * payment_type.
     * @var string 
     */
    public $payment_type;
	
	
     /**
     * payment_info.
     * @var string 
     */
    public $payment_info;
		
     /**
     * deposit_amount.
     * @var string 
     */
    public $deposit_amount;
		
     /**
     * user_email.
     * @var string 
     */
    public $user_email;
  
     /**
     * deposit_date.
     * @var date 
     */
    public $deposit_date;
 

		 /**
		 * Function to get all deposits
		 * @var string
		 */		
		public function get_all_deposits($limit, $start){
			
			$this->db->limit($limit, $start);			
			$this->db->order_by('deposit_date','ASC');
			$deposits = $this->db->get('deposits');
				
			if($deposits->num_rows() > 0){
					
				  // we will store the results in the form of class methods by using $q->result()
				  // if you want to store them as an array you can use $q->result_array()
				foreach ($deposits->result() as $row)
				{
					$data[] = $row;
				}
				return $data;
				  
			}else{
				return false;
			}
		}
 
		function get_customer_deposits($email, $limit, $start){
			
			$this->db->limit($limit, $start);
			$this->db->where('user_email', $email);
			$this->db->order_by('deposit_date','DESC');
			$q = $this->db->get('deposits');
			
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
		 * Function to count deposits
		 * @var string
		 */			
		public function count_deposits($email){
			
			$this->db->where('user_email', $email);
			$count_deposits = $this->db->get('deposits');
				
			if($count_deposits->num_rows() > 0)	{
					
				$count = $count_deposits->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}
		
		/***
		** Function to add payment method
		**
		***/	
		public function add_deposit($data, $email_address){
			
			//$email_address = $this->session->userdata('email_address');
			
			//insert into deposit table
			$query = $this->db->insert('deposits', $data);
			
			if ($query){
				//create new transaction
				$deposit_date = $this->session->flashdata('deposit_date');
				
				$deposit_amount = $this->input->post('deposit_amount');
				//remove non-numbers from post
				$amount = preg_replace("/[^\d-.]+/","", $deposit_amount);
	
				$number = $this->input->post('voucher_number');
				$transaction = 'XXXX-XXXX-XXXX-'.substr($number,-4);
				
				
				//generate random reference number
				$reference = mt_rand(100000000, 999999999);
				
				//obtain users ip address
				$ip_address = $this->ip();
				
				//data for db
				$data = array(
					'reference' => $reference,
					'amount' => '+ $'.number_format($amount, 2),
					'transaction' => $transaction,
					'note' => 'Deposit',
					
					'user_email' => $email_address,
					'date' => $deposit_date,
				);
				$this->db->insert('transactions', $data);
				
				//update users balance
				$user = $this->Customers->get_customer($email_address);
						
				$account_balance = '';
						
				foreach($user as $u){
					$account_balance = $u->account_balance;
				}
				//$account_balance = $this->input->post('account_balance');
				$new_balance = $amount + $account_balance;
				
				$d = array(
					'account_balance' => $new_balance,
				);
				$this->db->where('email_address', $email_address);
				$this->db->update('customers', $d);
				
				return true;
			}else {
				return false;
			}
		}
				
		
		/***
		** Function to add payment method
		**
		***/	
		public function paypal_deposit($amount, $deposits, $transactions){
			
			$email_address = $this->session->userdata('email_address');
			
			//insert into deposit table
			$query = $this->db->insert('deposits', $deposits);
			
		//	$insert_id = $this->db->insert_id();

			if ($query){

				//insert transaction in db
				$this->db->insert('transactions', $transactions);
				
				//get users details
				$user = $this->Customers->get_customer($email_address);
				
				//update users balance
				//$account_balance = $this->input->post('account_balance');
				$account_balance = '';
				
				foreach($user as $u){
					$account_balance = $u->account_balance;
				}

				$new_balance = $amount + $account_balance;
				
				$update = array(
					'account_balance' => $new_balance,
				);
				$this->db->where('email_address', $email_address);
				$this->db->update('customers', $update);
				
				return true;
			}else {
				return false;
			}
		}

		/****
		** Function to get deposit by id
		****/
		public function get_deposit_by_id($id){

			$this->db->limit(1, 0);
			$this->db->where('id', $id);
			$q = $this->db->get('deposits');
			
			if($q->num_rows() > 0){
			  foreach ($q->result() as $row)
			  {
				$data[] = $row;
			  }
			  return $data;
			}

		}	
					
		/**
		* Function to search watchlist
		* @var string
		*/			
		public function get_search($keyword, $limit, $offset){
			
			$this->db->like('payment_type',$keyword);
			$this->db->or_like('payment_info',$keyword);
			$this->db->or_like('deposit_amount',$keyword);
			$this->db->or_like('user_email',$keyword);
			$this->db->limit($limit, $offset);
			$this->db->order_by('deposit_date','DESC');
			$query = $this->db->get('deposits');
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
			
			$this->db->like('payment_type',$keyword);
			$this->db->or_like('payment_info',$keyword);
			$this->db->or_like('deposit_amount',$keyword);
			$this->db->or_like('user_email',$keyword);
			$count_deposits = $this->db->get('deposits');
				
			if($count_deposits->num_rows() > 0)	{
					
				$count = $count_deposits->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}	





	


}