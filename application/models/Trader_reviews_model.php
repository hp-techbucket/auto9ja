<?php

class Trader_reviews_model extends MY_Model {
		
		const DB_TABLE = 'trader_reviews';
		const DB_TABLE_PK = 'id';

		/**
		 * user id.
		 * @var int 
		 */
		public $user_id;

		/**
		 * trader email.
		 * @var string 
		 */
		public $trader_email;

		/**
		 * reviewer username.
		 * @var string 
		 */
		public $reviewer_email;

		/**
		 * comment.
		 * @var string 
		 */
		public $comment;

		/**
		 * rating.
		 * @var decimal 
		 */
		public $rating;

		/**
		 * review date.
		 * @var date 
		 */
		public $review_date;

	

		 /**
		 * Function to count reviews
		 * @var string
		 */			
		public function count_reviews(){
			
			$count_reviews = $this->db->get('trader_reviews');
			if($count_reviews->num_rows() > 0)	{
					
				$count = $count_reviews->num_rows();
				return $count;
			}else {
				return false;
			}				
		}
		
		
		/****
		** Function to get all records from the database
		****/
		public function get_reviews(){
			
			$this->db->order_by('id','DESC');
			$q = $this->db->get('trader_reviews');
			
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
		* to the products table in the database
		* @param $string Activation key
		*/		
		public function insert_review($data, $trader_email = null){

			$query  = $this->db->insert('trader_reviews', $data);
			
			if ($query ){
				
				//get average of customers rating
				$rating = $this->get_average_rating($trader_email);
				
				$data = array(
					'rating' => $rating
				);
				//update the customers rating
				$this->Trader->trader_update($data, $trader_email);
				
				return true;
			}else {
				return false;
			}
		}
		
		/**
		* Function to update
		* the review
		* variable array $data int $id
		*/	
		public function update_review($data, $id = null){
			
			$this->db->where('id', $id);
			$query = $this->db->update('trader_reviews', $data);
			
			if ($query){	
				return true;
			}else {
				return false;
			}			
			
		}

		
		/****
		** Function to get all records from the database
		****/
		public function get_trader_reviews($email = null){
			
			$this->db->where('trader_email', $email);
			$q = $this->db->get('trader_reviews');
			
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

		
		/****
		** Function to get average
		** customer rating from the database
		****/
		public function get_trader_rating($email = null){
			
			$this->db->select_avg('rating');
			//$this->db->select('AVG(rating) as average');
			$this->db->from('trader_reviews');
			$this->db->where('trader_email', $email);
			
			$q = $this->db->get();
			
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
		
		public function get_average_rating($email = null){ 
				
				$this->db->select_avg('rating');
				$this->db->where('trader_email', $email);
				$query = $this->db->get('trader_reviews')->first_row('array');
				return round($query['rating'], 2);
		}
				
		/**
		* Function to search reviews
		* @var string
		*/			
		public function search_reviews($keyword = null, $limit = 6, $offset = 0){
			
			$this->db->like('LOWER(trader_email)',strtolower($keyword));
			$this->db->or_like('LOWER(reviewer_email)',strtolower($keyword));
			$this->db->or_like('LOWER(rating)',strtolower($keyword));
			$this->db->limit($limit, $offset);
			$this->db->order_by('id','DESC');
			$query = $this->db->get('trader_reviews');
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
		public function count_search_reviews($keyword = null){

			$this->db->like('LOWER(trader_email)',strtolower($keyword));
			$this->db->or_like('LOWER(reviewer_email)',strtolower($keyword));
			$this->db->or_like('LOWER(rating)',strtolower($keyword));
			$count_reviews = $this->db->get('trader_reviews');
				
			if($count_reviews->num_rows() > 0)	{
					
				$count = $count_reviews->num_rows();
				return $count;
			}else {
				return false;
			}			
				
		}
						
		
		
	
}