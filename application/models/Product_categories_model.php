<?php

class Product_categories_model extends MY_Model {
    
    const DB_TABLE = 'product_categories';
    const DB_TABLE_PK = 'id';

	
	/**
     * category of the service.
     * @var string 
     */
    public $category_name;

  
	public function get_product_categories(){
		
		$this->db->from('product_categories');
		$this->db->order_by('id');
		$result = $this->db->get();
		$return = array();
		
		if($result->num_rows() > 0) {
			foreach($result->result_array() as $row) {
				$return[$row['category_name']] = $row['category_name'];
			}
		}
		
		return $return;
	}


		 /**
		 * Function to count categories
		 * @var string
		 */			
		public function count_categories(){
			
			$count_categories = $this->db->get('product_categories');
			if($count_categories->num_rows() > 0)	{
					
				$count = $count_categories->num_rows();
				return $count;
			}else {
				return false;
			}				
		}
		
		
		/****
		** Function to get all records from the database
		****/
		public function get_categories(){
			
			$this->db->order_by('id','DESC');
			$q = $this->db->get('product_categories');
			
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
		* Function to update
		* the category
		* variable array $data int $id
		*/	
		public function update_category($data, $id){
			
			$this->db->where('id', $id);
			$query = $this->db->update('product_categories', $data);
			
			if ($query){	
				return true;
			}else {
				return false;
			}			
			
		}

		
		/**
		* Function to search questions
		* @var string
		*/			
		public function search_categories($keyword, $limit, $offset){
			
			$this->db->like('LOWER(category_name)',strtolower($keyword));
			$this->db->limit($limit, $offset);
			$this->db->order_by('id','DESC');
			$query = $this->db->get('product_categories');
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
		public function count_search_categories($keyword){
			
			$this->db->like('LOWER(category_name)',strtolower($keyword));
			$count_categories = $this->db->get('product_categories');
				
			if($count_categories->num_rows() > 0)	{
					
				$count = $count_categories->num_rows();
				return $count;
			}else {
				return false;
			}			
				
		}

		
	
	
}