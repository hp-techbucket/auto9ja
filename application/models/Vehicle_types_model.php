<?php

class Vehicle_types_model extends MY_Model {
		
		const DB_TABLE = 'vehicle_types';
		const DB_TABLE_PK = 'id';


		var $table = 'vehicle_types';
		
		var $column_order = array(null, 'name'); //set column field database for datatable orderable
		
		var $column_search = array('name'); //set column field database for datatable searchable 
		
		var $order = array('id' => 'desc'); // default order 
		

		/**
		 * Vehicle Type Name.
		 * @var string 
		 */
		public $name;

			
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
		* Function to insert vehicle type
		*  
		*/	
		public function insert_vehicle_type($data){
			
			$query = $this->db->insert($this->table, $data);
			//$insert_id = $this->db->insert_id();
			if ($query){
				return true;
			}else {
				return false;
			}
				
		}
	
		/**
		* Function to update vehicle type
		* variable $id
		*/	
		public function update_vehicle_type($data, $id){

			$this->db->where('id', $id);
			$query = $this->db->update($this->table, $data);
			
			if ($query){	
				return true;
			}else {
				return false;
			}			
			
		}

		public function get_all_types(){

			$this->db->order_by('id','DESC');
			$makes = $this->db->get($this->table);
					
			if($makes->num_rows() > 0){

				foreach ($makes->result() as $row){
					$data[] = $row;
				}
				return $data;
					  
			}else{
				return false;
			}
		}		


		public function get_vehicle_types($limit = 10, $start = 0){
					
			$this->db->limit($limit, $start);
			$this->db->order_by('id','DESC');
			$types = $this->db->get($this->table);
					
			if($types->num_rows() > 0){

				foreach ($types->result() as $row){
					$data[] = $row;
				}
				return $data;
					  
			}else{
				return false;
			}
		}		
				
		public function count_vehicle_types(){
					
			$count_types = $this->db->get($this->table);
					
			if($count_types->num_rows() > 0)	{
				$count = $count_types->num_rows();
				return $count;
			}else {	
				return false;
			}			
					
		}
		
		/* Function to ensure the name is unique 
		* 
		*/	
		public function unique_name($name = ''){
			
			if($name != ''){
				$this->db->where('LOWER(name)', strtolower($name));
			}

			$query = $this->db->get($this->table);
			
			if ($query->num_rows() == 0){
				return true;
			} else {
				return false;
			}
			
		}
			

			
		
		/* Function to check if the type is unique 
		*  and insert if it is
		*/	
		public function unique_type($type = ''){
			
			if($type != ''){
				$this->db->where('LOWER(type)', strtolower($type));
			}
			
			$query = $this->db->get($this->table);
			
			if ($query->num_rows() == 0){
				$data = array(
					'name' => ucwords($title),
				);
				$this->insert_vehicle_type($data);
				return true;
			} else {
				return false;
			}
			
		}

			
			
			
			
			
			
			
			
			


	
    
}

