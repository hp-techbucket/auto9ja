<?php

class Vehicles_model extends MY_Model {
		
		const DB_TABLE = 'vehicles';
		const DB_TABLE_PK = 'id';

		/**
		 * vehicle type.
		 * @var string 
		 */
		public $vehicle_type;

		/**
		 * vehicle make.
		 * @var string 
		 */
		public $vehicle_make;

		/**
		 * vehicle model.
		 * @var string 
		 */
		public $vehicle_model;

		/**
		 * year_of_manufacture.
		 * @var string 
		 */
		public $year_of_manufacture;

		/**
		 * vehicle_odometer.
		 * @var string 
		 */
		public $vehicle_odometer;

		/**
		 * vehicle_lot_number.
		 * @var string 
		 */
		public $vehicle_lot_number;

		/**
		 * vehicle_vin.
		 * @var string 
		 */
		public $vehicle_vin;

		/**
		 * vehicle_colour.
		 * @var string 
		 */
		public $vehicle_colour;

		/**
		 * vehicle_price.
		 * @var string 
		 */
		public $vehicle_price;

		/**
		 * vehicle_location.
		 * @var string 
		 */
		public $vehicle_location;

		/**
		 * vehicle_description.
		 * @var string 
		 */
		public $vehicle_description;

		/**
		 * sale_status.
		 * @var string 
		 */
		public $sale_status;

		/**
		 * vehicle image 1.
		 * @var string
		 */
		public $image_1;

		/**
		 * vehicle image 2.
		 * @var string
		 */
		public $image_2;

		/**
		 * vehicle image 3.
		 * @var string
		 */
		public $image_3;

		/**
		 * vehicle image 4.
		 * @var string
		 */
		public $image_4;

		/**
		 * vehicle image 5.
		 * @var string
		 */
		public $image_5;

		/**
		 * vehicle image 6.
		 * @var string
		 */
		public $image_6;

		/**
		 * date_added.
		 * @var date 
		 */
		public $date_added;

	

		 /**
		 * Function to count vehicles
		 * @var string
		 */			
		public function count_vehicles(){
			
			$count_vehicles = $this->db->get('vehicles');
			if($count_vehicles->num_rows() > 0)	{
					
				$count = $count_vehicles->num_rows();
				return $count;
			}else {
				return false;
			}				
		}
		
		
		/****
		** Function to get all records from the database
		****/
		public function get_vehicles(){
			
			$this->db->order_by('id','DESC');
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
			return false;
		}

		/**
		* Function to add the item 
		* to the vehicles table in the database
		* @param $string Activation key
		*/		
		public function insert_vehicle($data){

			$query  = $this->db->insert('vehicles', $data);
			
			if ($query ){
				return true;
			}else {
				return false;
			}
		}
		
		/**
		* Function to update
		* the vehicle
		* variable array $data int $id
		*/	
		public function update_vehicle($data, $id){
			
			$this->db->where('id', $id);
			$query = $this->db->update('vehicles', $data);
			
			if ($query){	
				return true;
			}else {
				return false;
			}			
			
		}

		
		/****
		** Function to get all records from the database
		****/
		public function get_vehicle($id){
			
			$this->db->where('id', $id);
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
			return false;
		}


		/**
		* Function to add the item 
		* to the vehicles table in the database
		* @param $string Activation key
		*/		
		public function insert_more_vehicle_images($data){

			$query  = $this->db->insert('vehicles', $data);
			
			if ($query ){
				return true;
			}else {
				return false;
			}
		}

		
		/**
		* Function to search vehicles
		* @var string
		*/			
		public function search_vehicles($keyword, $limit, $offset){
			
			$this->db->like('LOWER(vehicle_type)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_make)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_model)',strtolower($keyword));
			$this->db->or_like('LOWER(year_of_manufacture)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_odometer)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_lot_number)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_vin)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_colour)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_price)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_location)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_description)',strtolower($keyword));
			$this->db->limit($limit, $offset);
			$this->db->order_by('id','DESC');
			$this->db->where('sale_status', '0');
			$query = $this->db->get('vehicles');
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
		public function count_search_products($keyword){
			
			$this->db->like('LOWER(vehicle_type)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_make)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_model)',strtolower($keyword));
			$this->db->or_like('LOWER(year_of_manufacture)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_odometer)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_lot_number)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_vin)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_colour)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_price)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_location)',strtolower($keyword));
			$this->db->or_like('LOWER(vehicle_description)',strtolower($keyword));
			$this->db->where('sale_status', '0');
			$count_vehicles = $this->db->get('vehicles');
				
			if($count_vehicles->num_rows() > 0)	{
					
				$count = $count_vehicles->num_rows();
				return $count;
			}else {
				return false;
			}			
				
		}
		
		/**
		* Function to search questions
		* @var string
		*/			
		public function get_vehicles_by_type($keyword, $limit, $offset){

			$this->db->where('LOWER(vehicle_type)',strtolower($keyword));
			$this->db->limit($limit, $offset);
			$this->db->order_by('id','DESC');
			$query = $this->db->get('vehicles');
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
		public function count_vehicles_by_type($keyword){
			
			$this->db->or_like('LOWER(vehicle_type)',strtolower($keyword));
			$count_vehicles = $this->db->get('vehicles');
				
			if($count_vehicles->num_rows() > 0)	{
					
				$count = $count_vehicles->num_rows();
				return $count;
			}else {
				return false;
			}			
				
		}						

				
		
	
}