<?php

class Invoices_model extends MY_Model {
    
		const DB_TABLE = 'invoices';
		const DB_TABLE_PK = 'id';

		
		/**
		 * invoice reference number.
		 * @var string 
		 */
		public $invoice_ref;
		
		/**
		 * vehicle id of the item.
		 * @var string 
		 */
		public $vehicle_id;

		
		/**
		 * description of the item
		 * paid or to pay for.
		 * @var int 
		 */
		public $description;

				
		/**
		 * email address of the buyer.
		 * @var string 
		 */
		public $buyers_email;

		
		/**
		 * email address of the trader.
		 * @var string 
		 */
		public $traders_email;

		
		/**
		 * amount paid or to pay.
		 * @var decimal 
		 */
		public $amount;

		
		/**
		 * payment status.
		 * @var string 
		 */
		public $payment_status;

		
		/**
		 * invoice date.
		 * @var string 
		 */
		public $invoice_date;

		
		public function count_payments($email){
				
			$this->db->where('buyers_email', $email);
			$this->db->where('payment_status', '1');
			$count_payments = $this->db->get('invoices');
				
			if($count_payments->num_rows() > 0)	{
					
				$count = $count_payments->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}			


		public function get_payments($email, $limit, $start){
				
			$this->db->limit($limit, $start);
			$this->db->where('buyers_email', $email);
			$this->db->where('payment_status', '1');
			$this->db->order_by('invoice_date','DESC');
			
			$payments = $this->db->get('invoices');
				
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

		public function get_p_payments($email){
				
			$this->db->limit(5, 0);
			$this->db->where('buyers_email', $email);
			$this->db->where('payment_status', '0');
			$this->db->order_by('invoice_date','DESC');
			$pending_payments = $this->db->get('invoices');
				
			if($pending_payments->num_rows() > 0){
					
				  // we will store the results in the form of class methods by using $q->result()
				  // if you want to store them as an array you can use $q->result_array()
				foreach ($pending_payments->result() as $row)
				{
					$data[] = $row;
				}
				return $data;
				  
			}else{
				return false;
			}
		}		

		
		public function count_pending_payments($email){
				
			$this->db->where('buyers_email', $email);
			$this->db->where('payment_status', '0');
			$count_payments = $this->db->get('invoices');
				
			if($count_payments->num_rows() > 0)	{
					
				$count = $count_payments->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}			


		public function get_pending_payments($email, $limit, $start){
				
			$this->db->limit($limit, $start);
			$this->db->where('buyers_email', $email);
			$this->db->where('payment_status', '0');
			$this->db->order_by('invoice_date','DESC');
			
			$payments = $this->db->get('invoices');
				
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



		
		public function trader_count_payments($email){
				
			$this->db->where('traders_email', $email);
			//$this->db->where('payment_status', '1');
			$count_payments = $this->db->get('invoices');
				
			if($count_payments->num_rows() > 0)	{
					
				$count = $count_payments->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}			


		public function trader_get_payments($email, $limit, $start){
				
			$this->db->limit($limit, $start);
			$this->db->where('traders_email', $email);
			//$this->db->where('payment_status', '1');
			$this->db->order_by('invoice_date','DESC');
			
			$payments = $this->db->get('invoices');
				
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
		
		
		public function get_payment_methods($email){
				
			$this->db->where('user_email', $email);
			$payments = $this->db->get('payment_methods');
				
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


		public function get_paypal($email){
			
			$empty = '';
			
			$this->db->where('user_email', $email);
			$this->db->where('PayPal_email !=', $empty);
			$payments = $this->db->get('payment_methods');
				
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


		public function get_cc_info($id,$email){
			
			$this->db->where('payment_method_id', $id);	
			$this->db->where('type', 'Credit Card');
			$this->db->where('user_email', $email);
			$payments = $this->db->get('payment_methods');
				
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
	
			
		/**
		* Function to search invoices
		* @var string
		*/			
		public function get_pending_search($email, $keyword, $limit, $offset){
			
			$this->db->like('description',$keyword);
			$this->db->or_like('amount',$keyword);
			$this->db->or_like('payment_status',$keyword);
			$this->db->limit($limit, $offset);
			$this->db->where('buyers_email', $email);
			$this->db->where('payment_status', 'Unpaid');
			
			$this->db->order_by('invoice_date','DESC');
			$query = $this->db->get('invoices');
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
		public function count_pending_search($keyword, $email){
			
			$this->db->like('description',$keyword);
			$this->db->or_like('amount',$keyword);
			$this->db->or_like('payment_status',$keyword);
			$this->db->where('buyers_email', $email);
			$this->db->where('payment_status', 'Unpaid');
			
			$count_invoices = $this->db->get('invoices');
				
			if($count_invoices->num_rows() > 0)	{
					
				$count = $count_invoices->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}


		
		/**
		* Function to search invoices
		* @var string
		*/			
		public function get_search($email, $keyword, $limit, $offset){
			
			$this->db->like('description',$keyword);
			$this->db->or_like('amount',$keyword);
			$this->db->or_like('payment_status',$keyword);
			$this->db->limit($limit, $offset);
			$this->db->where('buyers_email', $email);
			$this->db->where('payment_status', 'Paid');
			$this->db->order_by('invoice_date','DESC');
			$query = $this->db->get('invoices');
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
		public function count_search($keyword, $email){
			
			$this->db->like('description',$keyword);
			$this->db->or_like('amount',$keyword);
			$this->db->or_like('payment_status',$keyword);
			$this->db->where('buyers_email', $email);
			$this->db->where('payment_status', 'Paid');
			
			$count_invoices = $this->db->get('invoices');
				
			if($count_invoices->num_rows() > 0)	{
					
				$count = $count_invoices->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}

		
		/**
		* Function to search invoices
		* @var string
		*/			
		public function admin_search($keyword, $limit, $offset){
			
			$this->db->like('description',$keyword);
			$this->db->or_like('amount',$keyword);
			$this->db->or_like('payment_status',$keyword);
			$this->db->limit($limit, $offset);
			$this->db->order_by('invoice_date','DESC');
			$query = $this->db->get('invoices');
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
		public function count_admin_search($keyword, $email){
			
			$this->db->like('description',$keyword);
			$this->db->or_like('amount',$keyword);
			$this->db->or_like('payment_status',$keyword);
			
			$count_invoices = $this->db->get('invoices');
				
			if($count_invoices->num_rows() > 0)	{
					
				$count = $count_invoices->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}
						
		/**
		* Function to search invoices
		* @var string
		*/			
		public function trader_invoice_search($email, $keyword, $limit, $offset){
			
			$this->db->like('description',$keyword);
			$this->db->or_like('amount',$keyword);
			$this->db->or_like('payment_status',$keyword);
			$this->db->limit($limit, $offset);
			$this->db->where('traders_email', $email);
			$this->db->order_by('invoice_date','DESC');
			$query = $this->db->get('invoices');
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
		public function count_trader_invoice_search($keyword, $email){
			
			$this->db->like('description',$keyword);
			$this->db->or_like('amount',$keyword);
			$this->db->or_like('payment_status',$keyword);
			$this->db->where('traders_email', $email);
			
			$count_invoices = $this->db->get('invoices');
				
			if($count_invoices->num_rows() > 0)	{
					
				$count = $count_invoices->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}
											
		

		
		
}