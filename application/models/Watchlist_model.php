<?php

class Watchlist_model extends MY_Model {
    
    const DB_TABLE = 'watchlist';
    const DB_TABLE_PK = 'id';
	
	
     /**
     * Vehicle id.
     * @var int 
     */
    public $vehicle_id;
	
	
     /**
     * Vehicle name.
     * @var string 
     */
    public $vehicle_name;
		
     /**
     * customer email.
     * @var string 
     */
    public $customer_email;
 
     /**
     * date added.
     * @var date 
     */
    public $date_added;
 

		public function get_watchlist_summary($email){
				
			$this->db->limit(5, 0);
			$this->db->where('customer_email', $email);
			$this->db->order_by('date_added','DESC');
			$watchlist = $this->db->get('watchlist');
				
			if($watchlist->num_rows() > 0){
					
				  // we will store the results in the form of class methods by using $q->result()
				  // if you want to store them as an array you can use $q->result_array()
				foreach ($watchlist->result() as $row)
				{
					$data[] = $row;
				}
				return $data;
				  
			}else{
				return false;
			}
		}		



		function get_watchlist(){
			
			$q = $this->db->get('watchlist');
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
		public function get_all_watchlist($limit, $start){
			
			$this->db->limit($limit, $start);			
			$this->db->order_by('id','ASC');
			$watchlist = $this->db->get('watchlist');
				
			if($watchlist->num_rows() > 0){
					
				  // we will store the results in the form of class methods by using $q->result()
				  // if you want to store them as an array you can use $q->result_array()
				foreach ($watchlist->result() as $row)
				{
					$data[] = $row;
				}
				return $data;
				  
			}else{
				return false;
			}
		}
 
		function get_customer_watchlist($email, $limit, $start){
			
			$this->db->limit($limit, $start);
			$this->db->where('customer_email', $email);
			$this->db->order_by('date_added','DESC');
			$q = $this->db->get('watchlist');
			
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

		 /**
		 * Function to count vehicle types
		 * @var string
		 */			
		public function count_watchlist($email){
			
			$this->db->where('customer_email', $email);
			$count_watchlist = $this->db->get('watchlist');
				
			if($count_watchlist->num_rows() > 0)	{
					
				$count = $count_watchlist->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}
			
		/**
		* Function to search watchlist
		* @var string
		*/			
		public function get_search($email, $keyword, $limit, $offset){
			
			$this->db->like('vehicle_name',$keyword);
			$this->db->limit($limit, $offset);
			$this->db->where('customer_email', $email);
			$this->db->order_by('date_added','DESC');
			$query = $this->db->get('watchlist');
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
		public function count_search($keyword, $email){
			
			$this->db->like('vehicle_name',$keyword);
			$this->db->where('customer_email', $email);
			
			$count_watchlist = $this->db->get('watchlist');
				
			if($count_watchlist->num_rows() > 0)	{
					
				$count = $count_watchlist->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}	





	


}