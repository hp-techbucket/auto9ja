<?php

class Email_alerts_model extends MY_Model {
		
		const DB_TABLE = 'email_alerts';
		const DB_TABLE_PK = 'id';


		var $table = 'email_alerts';
		
		var $column_order = array(null,'status','email','last_updated'); //set column field database for datatable orderable
		
		var $column_search = array('status','email','last_updated'); //set column field database for datatable searchable 
		
		var $order = array('id' => 'desc'); // default order 
		

		/**
		 * Alert Status.
		 * @var string 
		 */
		public $status;

		/**
		 * User Email.
		 * @var string 
		 */
		public $email;

		/**
		 * Last Updated.
		 * @var datetime 
		 */
		public $last_updated;

			
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
		
		
	
		/**
		* Function to insert alert
		*  
		*/	
		public function insert_alert($data){
			
			$query = $this->db->insert($this->table, $data);
			//$insert_id = $this->db->insert_id();
			if ($query){
				return true;
			}else {
				return false;
			}
				
		}
	
		/**
		* Function to update alert
		* variable $id
		*/	
		public function update_alert($data, $email = ''){

			if($email != '' && $email != null){
				$this->db->where('LOWER(email)', strtolower($email));
			}
			
			$query = $this->db->update($this->table, $data);
			
			if ($query){	
				return true;
			}else {
				return false;
			}			
			
		}
		
		
		/****
		** Function to get users alert status from the database
		****/
		public function get_alert($email = ''){
			
			if($email != '' && $email != null){
				$this->db->where('LOWER(email)', strtolower($email));
			}

			$q = $this->db->get($this->table);
			
			if($q->num_rows() == 1){
		
			  foreach ($q->result() as $row)
			  {
				$data[] = $row;
			  }
			  return $data;
			}
			return false;
		}
		
		
		/* Function to ensure the email is unique 
		* 
		*/	
		public function unique_email($email = ''){
			
			if($email != '' && $email != null){
				$this->db->where('LOWER(email)', strtolower($email));
			}

			$query = $this->db->get($this->table);
			
			if ($query->num_rows() == 0){
				return true;
			} else {
				return false;
			}
			
		}
			
		
		/* Function to ensure the email is unique 
		* 
		*/	
		public function alert_on($email = ''){
			
			if($email != '' && $email != null){
				$this->db->where('LOWER(email)', strtolower($email));
			}
			$this->db->where('status', '1');
			$query = $this->db->get($this->table);
			
			if ($query->num_rows() == 1){
				return true;
			} else {
				return false;
			}
			
		}
			

			
			
			
			
			
			
			
			
			


	
    
}

