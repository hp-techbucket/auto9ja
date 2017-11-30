<?php

class Vehicle_models_model extends MY_Model {
		
		const DB_TABLE = 'vehicle_models';
		const DB_TABLE_PK = 'id';


		var $table = 'vehicle_models';
		
		var $column_order = array(null, 'make_id', 'code', 'title'); //set column field database for datatable orderable
		
		var $column_search = array('make_id', 'code', 'title'); //set column field database for datatable searchable 
		
		var $order = array('id' => 'asc'); // default order 
		

		/**
		 * Vehicle Make ID.
		 * @var string 
		 */
		public $make_id;

		/**
		 * Vehicle Model Code.
		 * @var string 
		 */
		public $code;

		/**
		 * Vehicle Model title.
		 * @var string 
		 */
		public $title;

			
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
		* Function to insert vehicle model
		*  
		*/	
		public function insert_vehicle_model($data){
				
			$query = $this->db->insert($this->table, $data);
			//$insert_id = $this->db->insert_id();
			if ($query){
				return true;
			}else {
				return false;
			}
				
		}
	
		/**
		* Function to update vehicle model
		* variable $id
		*/	
		public function update_vehicle_model($data, $id){

			$this->db->where('id', $id);
			$query = $this->db->update($this->table, $data);
			
			if ($query){	
				return true;
			}else {
				return false;
			}			
			
		}

		public function get_all_models(){

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

		public function get_models_by_make($make_id = ''){

			if($make_id != ''){
				$this->db->where('make_id', $make_id);
			}
			
			$q = $this->db->get($this->table);
			
			if($q->num_rows() > 0){
				
			  // we will store the results in the form of class methods by using $q->result()
			  // if you want to store them as an array you can use $q->result_array()
			  foreach ($q->result() as $row)
			  {
				$data[] = $row;
			  }
			  return $data;
			}
			return false;
		}		


		public function get_models($limit = 10, $start = 0){
					
			$this->db->limit($limit, $start);
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
				
		public function count_models(){
					
			$count_makes = $this->db->get($this->table);
					
			if($count_makes->num_rows() > 0)	{
				$count = $count_makes->num_rows();
				return $count;
			}else {	
				return false;
			}			
					
		}
		
		/* Function to ensure the model is unique 
		* 
		*/	
		public function unique_model($make_id = '', $code = '', $title = ''){
			
			if($make_id != ''){
				$this->db->where('make_id', $make_id);
			}

			if($code != ''){
				$this->db->where('LOWER(code)', strtolower($code));
			}

			if($title != ''){
				$this->db->where('LOWER(title)', strtolower($title));
			}
			
			$query = $this->db->get($this->table);
			
			if ($query->num_rows() == 0){
				return true;
			} else {
				return false;
			}
			
		}

			
		
		/* Function to check if the model is unique 
		*  and insert if it is
		*/	
		public function unique_vehicle_model($title = ''){
			
			if($title != ''){
				$this->db->where('LOWER(title)', strtolower($title));
			}
			
			$query = $this->db->get($this->table);
			
			if ($query->num_rows() == 0){
				
				//get make id 
				$detail = $this->db->select('*')->from('vehicle_makes')->where('LOWER(title)',strtolower($title))->get()->row();
				
				$make_id = '';
				
				if($detail){
					$make_id = $detail->id;
				}
				$data = array(
					'make_id' => $make_id,
					'code' => strtoupper($title),
					'title' => ucwords($title),
				);
				$this->insert_vehicle_model($data);
				return true;
			} else {
				return false;
			}
			
		}

			
			
			
			
			
			
			
			
			


	
    
}

