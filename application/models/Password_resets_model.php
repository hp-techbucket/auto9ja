<?php

class Password_resets_model extends MY_Model {
    
    const DB_TABLE = 'password_resets';
    const DB_TABLE_PK = 'id';


	var $table = 'password_resets';
	
    var $column_order = array(null, 'ip_address','ip_details','email','request_date'); //set column field database for datatable orderable
	
    var $column_search = array( 'ip_address','ip_details','email','request_date'); //set column field database for datatable searchable 
	
    var $order = array('id' => 'desc'); // default order 
	
	/**
     * IP ADDRESS OF THE USER.
     * @var string 
     */
    public $ip_address;
		
	/**
     * IP DETAILS OF THE USER.
     * @var string 
     */
    public $ip_details;
	

		/**
		 * email.
		 * @var string 
		 */
		public $email;

		/**
		 * activation_code.
		 * @var string 
		 */
		public $activation_code;

		/**
		 * request_date.
		 * @var string 
		 */
		public $request_date;

			
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
		* Function to insert password_resets
		*  
		*/	
					
			
		
		public function insert_password_resets($data){
			
			$query = $this->db->insert($this->table, $data);
					
			if ($query){
				return true;
			}else {
				return false;
			}			
		}						

				
		/**
		* Function to validate that the activation code
		* exists in the database
		* @param $string Activation code
		*/			
		public function is_valid_key($key){
			
			$email = $this->session->flashdata('email');
			$username = $this->session->flashdata('username');
			
			
			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			$min_date = strtotime("-1 day", $date);
			
			$max_date = date('Y-m-d H:i:s', time());
			$min_date = date('Y-m-d H:i:s', $min_date);			

			$this->db->where('username', $username);
			$this->db->where('activation_code', $key);
			$this->db->where("request_date BETWEEN '$min_date' AND '$max_date'", NULL, FALSE);
			
			$query = $this->db->get('password_resets');
			
			if ($query->num_rows() == 1){
				return true;
			} else {

				$this->db->where('username', $username);
				$this->db->where('activation_code', $code);
				$this->db->delete('password_resets');				
				return false;
			}			
		}
		
	
		/**
		* Function to update password_reset
		* variable $id
		*/	
		public function update_password_reset($data, $id){

			$this->db->where('id', $id);
			$query = $this->db->update('password_resets', $data);
			
			if ($query){	
				return true;
			}else {
				return false;
			}			
			
		}

		/**
		* Function to check that the username and password 
		* exists in the database
		*/	
		public function unique_password_reset($username){
			
			$this->db->where('username', $username);
			
			$query = $this->db->get('password_resets');
			
			if ($query->num_rows() == 0){
				return true;
			} else {
				return false;
			}
			
		}			
			
    
		
		/**
		* Function to delete old records
		*  
		*/		
		public function delete_old_records(){
			
			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			//delete records older than 90 days
			$min_date = strtotime("-90 day", $date);
			
			$this->db->where("request_date < '$min_date'", NULL, FALSE);
			$this->db->delete($this->table);
		}
						
			
			
			
			
			
			


	
    
}

