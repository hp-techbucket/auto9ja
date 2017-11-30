<?php

class Vehicle_type_model extends MY_Model {
    
    const DB_TABLE = 'vehicle_type';
    const DB_TABLE_PK = 'id';
	
	
     /**
     * Vehicle name.
     * @var string 
     */
    public $name;
 
		function get_vehicle_type($type){
			
			$this->db->where('type', $type);
			$q = $this->db->get('vehicles');
			
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


		function get_vehicle_types(){
			
			$q = $this->db->get('vehicle_type');
			if($q->num_rows() > 0){
			  foreach ($q->result() as $row){
				$data[] = $row;
			  }
			  return $data;
			}
		}


		 /**
		 * Function to get all vehicle_types
		 * @var string
		 */		
		public function get_all_vehicle_types($limit, $start){
			
			$this->db->limit($limit, $start);			
			$this->db->order_by('id','ASC');
			$types = $this->db->get('vehicle_type');
				
			if($types->num_rows() > 0){
					
				  // we will store the results in the form of class methods by using $q->result()
				  // if you want to store them as an array you can use $q->result_array()
				foreach ($types->result() as $row)
				{
					$data[] = $row;
				}
				return $data;
				  
			}else{
				return false;
			}
		}

		 /**
		 * Function to count vehicle types
		 * @var string
		 */			
		public function count_vehicle_type($type){
			
			$this->db->where('LOWER(vehicle_type)', strtolower($type));
			$count_types = $this->db->get('vehicles');
				
			if($count_types->num_rows() > 0)	{
					
				$count = $count_types->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}
		


}