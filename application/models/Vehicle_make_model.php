<?php

class Vehicle_make_model extends MY_Model {
    
    const DB_TABLE = 'vehicle_make';
    const DB_TABLE_PK = 'id';

	/**
     * code.
     * @var string 
     */
    public $code;
	
	/**
     * title.
     * @var string 
     */
    public $title;

  
	public function get_vehicle_make_list(){
		
		$this->db->select('*');
		$this->db->from('vehicle_make');
		//$this->db->order_by('id');
		$result = $this->db->get();
		$return = array();
		
		if($result->num_rows() > 0) {
			foreach($result->result_array() as $row) {
				$return[$row['title']] = $row['title'];
			}
		}
		return $return;
	}

  
	public function get_vehicle_makes(){
		
		$this->db->select('*');
		$this->db->from('vehicle_make');
		//$this->db->order_by('id');
		$result = $this->db->get();
		$return = array();
		
		if($result->num_rows() > 0) {
			foreach($result->result_array() as $row) {
				$return[$row['id']] = $row['title'];
			}
		}
		return $return;
	}

  
	public function get_vehicle_model($make_id){
		
		$this->db->from('vehicle_model');
		$this->db->where('make_id', $make_id);
		$result = $this->db->get();
		$return = array();
		
		if($result->num_rows() > 0) {
			foreach($result->result_array() as $row) {
				$return[$row['id']] = $row['title'];
			}
		}
		return $return;
	}


			
	
	
}