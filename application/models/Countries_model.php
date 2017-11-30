<?php

class Countries_model extends MY_Model {
    
    const DB_TABLE = 'countries';
    const DB_TABLE_PK = 'id';

	/**
     * sortname.
     * @var string 
     */
    public $sortname;
	
	/**
     * country name.
     * @var string 
     */
    public $name;

  
	public function get_country_list(){
		
		$this->db->select('*');
		$this->db->from('countries');
		//$this->db->order_by('id');
		$result = $this->db->get();
		$return = array();
		
		if($result->num_rows() > 0) {
			foreach($result->result_array() as $row) {
				$return[$row['name']] = $row['name'];
			}
		}
		return $return;
	}

  
	public function get_countries(){
		
		$this->db->select('*');
		$this->db->from('countries');
		//$this->db->order_by('id');
		$result = $this->db->get();
		$return = array();
		
		if($result->num_rows() > 0) {
			foreach($result->result_array() as $row) {
				$return[$row['id']] = $row['name'];
			}
		}
		return $return;
	}

  
	public function get_states($countryId){
		
		$this->db->from('states');
		$this->db->where('country_id', $countryId);
		$result = $this->db->get();
		$return = array();
		
		if($result->num_rows() > 0) {
			foreach($result->result_array() as $row) {
				$return[$row['id']] = $row['name'];
			}
		}
		return $return;
	}

  
	public function get_cities($stateId){
		
		$this->db->from('cities');
		$this->db->where('state_id', $stateId);
		$result = $this->db->get();
		$return = array();
		
		if($result->num_rows() > 0) {
			foreach($result->result_array() as $row) {
				$return[$row['id']] = $row['name'];
			}
		}
		return $return;
	}
		
			
	
	
}