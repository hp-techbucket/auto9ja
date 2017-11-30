<?php

class Transactions_model extends MY_Model {
    
		const DB_TABLE = 'transactions';
		const DB_TABLE_PK = 'id';

		var $table = 'transactions';
		
		var $column_order = array(null,'order_reference','order_amount','shipping_and_handling_costs','total_amount','email','status','created'); //set column field database for datatable orderable
		
		var $column_search = array('order_reference','order_amount','shipping_and_handling_costs','total_amount','email','status','created'); //set column field database for datatable searchable 
		
		var $order = array('id' => 'desc'); // default order 
				
		
		/**
		 * Reference of the transaction.
		 * @var int 
		 */
		public $order_reference;

		
		/**
		 * Order amount
		 * @var float
		 */
		public $order_amount;

		
		/**
		 * Shipping and Handling Costs.
		 * @var float 
		 */
		public $shipping_and_handling_costs;

		
		/**
		 * Total Amount.
		 * Order + Shipping
		 * @var float
		 */
		public $total_amount;

		/**
		 * users email.
		 * @var string 
		 */
		public $email;

		
		/**
		 * status.
		 * @var string 
		 */
		public $status;
		
		/**
		 * transaction date.
		 * @var string 
		 */
		public $created 	;

		

			
		private function _get_datatables_query()
		{
			 
			$this->db->from($this->table);
	 
			$i = 0;
		 
			foreach ($this->column_search as $item) // loop column 
			{
				if($_POST['search']['value']) // if datatable send POST for search
				{
					 
					if($i===0) // first loop
					{
						$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
						$this->db->like($item, $_POST['search']['value']);
					}
					else
					{
						$this->db->or_like($item, $_POST['search']['value']);
					}
	 
					if(count($this->column_search) - 1 == $i) //last loop
						$this->db->group_end(); //close bracket
				}
				$i++;
			}
			 
			if(isset($_POST['order'])) // here order processing
			{
				$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			} 
			else if(isset($this->order))
			{
				$order = $this->order;
				$this->db->order_by(key($order), $order[key($order)]);
			}
		}
		
		function get_datatables()
		{
			$this->_get_datatables_query();
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
			$query = $this->db->get();
			return $query->result();
		}
	 
		function count_filtered()
		{
			$this->_get_datatables_query();
			$query = $this->db->get();
			return $query->num_rows();
		}
	 
		public function count_all()
		{
			$this->db->from($this->table);
			return $this->db->count_all_results();
		}	
		
			
		private function _get_user_datatables_query()
		{
			$email = $this->session->userdata('email');
			
			$this->db->from($this->table);
			
			
			$i = 0;
		 
			foreach ($this->column_search as $item) // loop column 
			{
				if($_POST['search']['value']) // if datatable send POST for search
				{
					 
					if($i===0) // first loop
					{
						$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
						$this->db->like($item, $_POST['search']['value']);
					}
					else
					{
						$this->db->or_like($item, $_POST['search']['value']);
					}
	 
					if(count($this->column_search) - 1 == $i) //last loop
						$this->db->group_end(); //close bracket
				}
				$i++;
			}
			 
			if(isset($_POST['order'])) // here order processing
			{
				$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			} 
			else if(isset($this->order))
			{
				$order = $this->order;
				$this->db->order_by(key($order), $order[key($order)]);
			}
			$this->db->where('email', $email);	
		}
		
		function get_user_datatables()
		{
			$this->_get_user_datatables_query();
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
			$query = $this->db->get();
			return $query->result();
		}
	 
		function count_user_filtered()
		{
			$this->_get_user_datatables_query();
			$query = $this->db->get();
			return $query->num_rows();
		}
	 
		public function count_user_all()
		{
			$email = $this->session->userdata('email');
			
			$this->db->where('email', $email);	
			$query = $this->db->get($this->table);
			return $query->num_rows();
		}				
				
		
				
		
		/***
		** Function to add transaction
		**
		***/	
		public function insert_transaction($data = array()){
			
			//insert into transactions table
			if(!empty($data)){
				$this->db->insert($this->table, $data);
			}
			
			$insert_id = $this->db->insert_id();

			if ($insert_id){
				
				return true;
			}else {
				return false;
			}
		}
					

		public function get_all_transactions($limit=7, $start=0){
				
			$this->db->limit($limit, $start);
			$this->db->order_by('created','DESC');
			$transactions = $this->db->get($this->table);
				
			if($transactions->num_rows() > 0){
					
				  // we will store the results in the form of class methods by using $q->result()
				  // if you want to store them as an array you can use $q->result_array()
				foreach ($transactions->result() as $row)
				{
					$data[] = $row;
				}
				return $data;
				  
			}else{
				return false;
			}
		}		

		/****
		** Function to get_transaction_by_reference from the database
		****/
		public function get_transaction($ref = ''){
			
			if($ref != '' && $ref != null){
				$this->db->where('order_reference', $ref);
			}

			$transactions = $this->db->get($this->table);
				
			if($transactions->num_rows() > 0){
					
				  // we will store the results in the form of class methods by using $q->result()
				  // if you want to store them as an array you can use $q->result_array()
				foreach ($transactions->result() as $row)
				{
					$data[] = $row;
				}
				return $data;
				  
			}else{
				return false;
			}
		}		

		public function update_transaction($data, $id = ''){
			
			if($id != '' && $id != null){
				$this->db->where('id', $id);
			}
			
			$query = $this->db->update($this->table, $data);
				
			if ($query){
				return true;
			}else {
				return false;
			}			
		}
		
		
		/* Function to count transactions 
		* from the past 24 hours or 1 day
		*/						
		public function count_new_transactions($email = ''){
			
			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			$min_date = strtotime("-1 day", $date);
			
			$max_date = date('Y-m-d H:i:s', time());
			$min_date = date('Y-m-d H:i:s', $min_date);
			
			$this->db->select('*');
			$this->db->from('transactions');
			if($email != '' && $email != null){
				$this->db->where('email', $email);
			}
			//
			
			$this->db->where("created BETWEEN '$min_date' AND '$max_date'", NULL, FALSE);

			$query = $this->db->get();
			
			if($query->num_rows() > 0)	{
					
				$count = $query->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}
		
		public function get_user_transactions($email = '', $limit=7, $start=0){
				
			$this->db->limit($limit, $start);
			
			if($email != '' && $email != null){
				$this->db->where('email', $email);
			}
			$this->db->order_by('created','DESC');
			$transactions = $this->db->get($this->table);
				
			if($transactions->num_rows() > 0){
				foreach ($transactions->result() as $row){
					$data[] = $row;
				}
				return $data;
			}else{
				return false;
			}
		}		

		

		/* Function to check that the reference 
		* doesnt exist in the database
		*/	
		public function is_unique_reference($ref = ''){
			
			if($ref != '' && $ref != null){
				$this->db->where('order_reference', $ref);
			}

			$query = $this->db->get($this->table);
			
			if ($query->num_rows() == 0){
				return true;
			} else {
				return false;
			}
			
		}
	
		/* Function to ensure the data is unique 
		* 
		*/	
		public function is_unique($where = array()){
			
			if(!empty($where)){
				$this->db->where($where);
			}
			
			$query = $this->db->get($this->table);
			
			if ($query->num_rows() == 0){
				return true;
			} else {
				return false;
			}
			
		}		
		
		
		
		

		
		
		
}