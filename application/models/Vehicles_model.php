<?php

class Vehicles_model extends MY_Model {
		
		const DB_TABLE = 'vehicles';
		const DB_TABLE_PK = 'id';

		
		var $table = 'vehicles';
		
		var $column_order = array(null, 'vehicle_image','vehicle_type','vehicle_make','vehicle_model','year_of_manufacture','vehicle_odometer','vehicle_lot_number','vehicle_vin','vehicle_colour','vehicle_price','vehicle_location_city','vehicle_location_country','vehicle_description','sale_status','trader_email','discount','price_after_discount','last_updated','date_added'); //set column field database for datatable orderable
		
		var $column_search = array('vehicle_image','vehicle_type','vehicle_make','vehicle_model','year_of_manufacture','vehicle_odometer','vehicle_lot_number','vehicle_vin','vehicle_colour','vehicle_price','vehicle_location_city','vehicle_location_country','vehicle_description','sale_status','trader_email','discount','price_after_discount','last_updated','date_added'); //set column field database for datatable searchable 
		
		var $input_search = array('vehicle_type','vehicle_make','vehicle_model','year_of_manufacture','vehicle_colour','vehicle_description'); //set column field database for datatable searchable 
		
		
		
		var $order = array('id' => 'desc'); // default order 
		
		
		/**
		 * Vehicle Image.
		 * @var string 
		 */
		public $vehicle_image;
		
		
		/**
		 * Vehicle Type.
		 * @var string 
		 */
		public $vehicle_type;

		/**
		 * Vehicle Make.
		 * @var string 
		 */
		public $vehicle_make;
		
		/**
		 * Vehicle Model.
		 * @var string 
		 */
		public $vehicle_model;

		/**
		 * Year of Manufacture.
		 * @var string 
		 */
		public $year_of_manufacture;

		/**
		 * Vehicle Odometer.
		 * @var string 
		 */
		public $vehicle_odometer;

		/**
		 * Vehicle Lot Number.
		 * @var string 
		 */
		public $vehicle_lot_number;

		/**
		 * Vehicle Vin.
		 * @var string 
		 */
		public $vehicle_vin;

		/**
		 * Vehicle Colour.
		 * @var string 
		 */
		public $vehicle_colour;

		/**
		 * Vehicle Price.
		 * @var string 
		 */
		public $vehicle_price;

		/**
		 * Vehicle Location City.
		 * @var string 
		 */
		public $vehicle_location_city;

		/**
		 * Vehicle Location Country.
		 * @var string 
		 */
		public $vehicle_location_country;

		/**
		 * Vehicle Description.
		 * @var string 
		 */
		public $vehicle_description;

		/**
		 * Sale Status.
		 * Item on sale or not
		 * @var string 
		 */
		public $sale_status;

		/**
		 * Trader Email.
		 * @var string 
		 */
		public $trader_email;

		/**
		 * Sale discount percentage.
		 * @var string 
		 */
		public $discount;

		/**
		 * Sale Price after Discount.
		 * @var string 
		 */
		public $price_after_discount;

		/**
		 * Last Time Record was updated .
		 * @var Datetime 
		 */
		public $last_updated;
		
		/**
		 * Date Added.
		 * @var string 
		 */
		public $date_added;


			
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
			
		
		
		/*
		*	DATATABLE FUNCTION FOR USER VEHICLES
		*
		*/
		private function _get_user_datatables_query()
		{
			//$email = $this->session->userdata('email');
			
			if($this->session->userdata('logged_in')){
				$email = $this->session->userdata('email');
			}
				
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
			$this->db->where('trader_email', $email);
			
		}
		
		function get_user_datatables()
		{
			$this->_get_user_datatables_query();
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
			$query = $this->db->get();
			return $query->result();
		}
				
	 
		function count_user_filtered(){
			$this->_get_user_datatables_query();
			$query = $this->db->get();
			return $query->num_rows();
		}
				
				
		public function count_user_all(){
			
			//$email = $this->session->userdata('email');
			
			if($this->session->userdata('logged_in')){
				$email = $this->session->userdata('email');
			}
			
			$this->db->where('trader_email', $email);
			$query = $this->db->get($this->table);
			return $query->num_rows();			

		} 
		///END DATATABLE FUNCTION FOR USER VEHICLES
				
		
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
		 * Function to count vehicles
		 * @var string
		 */			
		public function count_vehicles(){
			
			$this->db->where('sale_status', '0');
			$count_vehicles = $this->db->get('vehicles');
			if($count_vehicles->num_rows() > 0)	{
					
				$count = $count_vehicles->num_rows();
				return $count;
			}else {
				return false;
			}				
		}
		
		
		/****
		** Function to get records from the database
		****/
		public function get_vehicles($limit = 4, $limit = 4, $offset = 0){

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
		 * Function to count all vehicles
		 * @var string
		 */			
		public function count_all_vehicles(){
			
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
		public function get_all_vehicles(){
					
			$this->db->order_by('date_added','DESC');
			$vehicles = $this->db->get($this->table);
					
			if($vehicles->num_rows() > 0){
				foreach ($vehicles->result() as $row){
					$data[] = $row;
				}
				return $data;
					  
			}else{
				return false;
			}
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
		public function search_vehicles($data, $limit, $offset){
			
			$keywords = explode( ' ', $data);
			
			foreach ($keywords as $keyword){
				
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
			
			}
			
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
		public function count_search_vehicles($data){
			
			$keywords = explode( ' ', $data);
			
			foreach ($keywords as $keyword){
				
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
				
			}	
			
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
		public function get_vehicles_by_type($type = ''){
			
			if($type != '' && $type != null){
				$this->db->where('LOWER(vehicle_type)',strtolower($type));
			}
			
			//$this->db->limit($limit, $offset);
			$this->db->where('sale_status', '0');
			$this->db->order_by('id','DESC');
			$query = $this->db->get($this->table);
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
		public function count_vehicles_by_type($type = ''){
			
			if($type != '' && $type != null){
				$this->db->where('LOWER(vehicle_type)',strtolower($type));
			}
			$this->db->where('sale_status', '0');
			$count_vehicles = $this->db->get($this->table);
				
			if($count_vehicles->num_rows() > 0)	{
					
				$count = $count_vehicles->num_rows();
				return $count;
			}else {
				return false;
			}			
				
		}						

							

		 /**
		 * Function to count search result
		 * @var string
		 */			
		public function count_filter_vehicles($type=null, $make=null,$model=null,$from=null,$to=null,$colour=null,$price_from=null,$price_to=null,$location=null){
			
			$this->db->where('vehicle_type', $type);
			$this->db->where('vehicle_make', $make);
			$this->db->where('vehicle_model', $model);
			$this->db->where("year_of_manufacture BETWEEN '$from' AND '$to'", NULL, FALSE);
			//$this->db->where('year_of_manufacture >=', $from);
			//$this->db->where('year_of_manufacture <=', $to);
			$this->db->where('vehicle_colour', $colour);
			$this->db->where("vehicle_price BETWEEN '$price_from' AND '$price_to'", NULL, FALSE);
			//$this->db->where('vehicle_price >=', $price_from);
			//$this->db->where('vehicle_price <=', $price_to);
			$this->db->where('vehicle_location', $location);
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
		 * Function to search result
		 * @var string
		 */			
		public function filter_vehicles($type=null, $make=null,$model=null,$from=null,$to=null,$colour=null,$price_from=null,$price_to=null,$location=null, $limit=10, $offset=0){
			
			$this->db->where('vehicle_type', $type);
			$this->db->where('vehicle_make', $make);
			$this->db->where('vehicle_model', $model);
			$this->db->where("year_of_manufacture BETWEEN '$from' AND '$to'", NULL, FALSE);
			//$this->db->where('year_of_manufacture >=', $from);
			//$this->db->where('year_of_manufacture <=', $to);
			$this->db->where('vehicle_colour', $colour);
			$this->db->where("vehicle_price BETWEEN '$price_from' AND '$price_to'", NULL, FALSE);
			//$this->db->where('vehicle_price >=', $price_from);
			//$this->db->where('vehicle_price <=', $price_to);
			$this->db->where('vehicle_location', $location);
			$this->db->where('sale_status', '0');
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
		public function count_filter($data){
			
			if(is_array($data) && count($data) > 0){
				
				$where = array();
				//$keywords = explode(' ', $data);
				
				foreach ($data as $keyword){
					
					$keyword = trim($keyword);
					
					$this->db->group_start();
					$this->db->like('LOWER(vehicle_type)',strtolower($keyword));
					$this->db->or_like('LOWER(vehicle_make)',strtolower($keyword));
					$this->db->or_like('LOWER(vehicle_model)',strtolower($keyword));
					$this->db->or_like('LOWER(year_of_manufacture)',strtolower($keyword));
					$this->db->or_like('LOWER(vehicle_colour)',strtolower($keyword));
					$this->db->or_like('LOWER(vehicle_description)',strtolower($keyword));
					$this->db->group_end();
					
				}
				
				
			}else{
				$keyword = trim($data);
				
				$this->db->like('LOWER(vehicle_type)',strtolower($keyword));
				$this->db->or_like('LOWER(vehicle_make)',strtolower($keyword));
				$this->db->or_like('LOWER(vehicle_model)',strtolower($keyword));
				$this->db->or_like('LOWER(year_of_manufacture)',strtolower($keyword));
				$this->db->or_like('LOWER(vehicle_colour)',strtolower($keyword));
				$this->db->or_like('LOWER(vehicle_description)',strtolower($keyword));
			}
				
				$this->db->order_by('id','DESC');
				$this->db->where('sale_status', '0');
				$query = $this->db->get($this->table);
				if($query->num_rows() > 0){
					return $query->num_rows();;
				}
				return false;		
				
		}
		
		 /**
		 * Function to search result
		 * @var string
		 */			
		public function filter($data){
			
			if(is_array($data) && count($data) > 0){
				
				$where = array();
				//$keywords = explode(' ', $data);
				
				foreach ($data as $keyword){
					
					$keyword = trim($keyword);
					
					$this->db->group_start();
					$this->db->like('LOWER(vehicle_type)',strtolower($keyword));
					$this->db->or_like('LOWER(vehicle_make)',strtolower($keyword));
					$this->db->or_like('LOWER(vehicle_model)',strtolower($keyword));
					$this->db->or_like('LOWER(year_of_manufacture)',strtolower($keyword));
					$this->db->or_like('LOWER(vehicle_colour)',strtolower($keyword));
					$this->db->or_like('LOWER(vehicle_description)',strtolower($keyword));
					$this->db->group_end();
					
				}
				
				
			}else{
				$keyword = trim($data);
				
				$this->db->like('LOWER(vehicle_type)',strtolower($keyword));
				$this->db->or_like('LOWER(vehicle_make)',strtolower($keyword));
				$this->db->or_like('LOWER(vehicle_model)',strtolower($keyword));
				$this->db->or_like('LOWER(year_of_manufacture)',strtolower($keyword));
				$this->db->or_like('LOWER(vehicle_colour)',strtolower($keyword));
				$this->db->or_like('LOWER(vehicle_description)',strtolower($keyword));
			}
				
			$this->db->order_by('id','DESC');
			$this->db->where('sale_status', '0');
			$this->db->order_by('id','DESC');
			$query = $this->db->get($this->table);
			if($query->num_rows() > 0){
					
				return $query->result();
				// we will store the results in the form of class methods by using $q->result()
				// if you want to store them as an array you can use $q->result_array()
				/*foreach ($query->result() as $row){
					$data[] = $row;
				}
				return $data;
				*/
			}
			return false;
		}			
				

		 /**
		 * Function to count search result
		 * @var string
		 */			
		public function count_search($where){
			
			$this->db->where($where);
			$this->db->where('sale_status', '0');
			$count_vehicles = $this->db->get($this->table);
				
			if($count_vehicles->num_rows() > 0)	{
					
				$count = $count_vehicles->num_rows();
				return $count;
			}else {
				return false;
			}			
				
		}
		
		 /**
		 * Function to search result
		 * @var string
		 */			
		public function search($where){
			
			$this->db->where($where);
			$this->db->where('sale_status', '0');
			$this->db->order_by('id','DESC');
			$query = $this->db->get($this->table);
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
		public function count_filter32($where, $condition = null){
			
			//$condition = "year_of_manufacture BETWEEN " . "'" . $data['year_from'] . "'" . " AND " . "'" . $data['year_to'] . "'";
			//$this->db->where($condition);
			$this->db->where($where);
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
		 * Function to search result
		 * @var string
		 */			
		public function filter32($where, $condition = null, $limit=10, $offset=0){
			
			//$condition = "year_of_manufacture BETWEEN " . "'" . $data['year_from'] . "'" . " AND " . "'" . $data['year_to'] . "'";
			//$this->db->where($condition);
			
			$this->db->where($where);
			$this->db->where('sale_status', '0');
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
				
		
	
}