<?php

class Cart_model extends MY_Model {
		
		const DB_TABLE = 'cart';
		const DB_TABLE_PK = 'id';

		/**
		 * session_id.
		 * @var int 
		 */
		public $session_id;

		/**
		 * product_id.
		 * @var int 
		 */
		public $product_id;

		/**
		 * quantity.
		 * @var int
		 */
		public $quantity;

		/**
		 * username.
		 * @var string 
		 */
		public $username;

		/**
		 * date_added.
		 * @var date 
		 */
		public $date_added;

	

		 /**
		 * Function to count cart items
		 * @var string
		 */			
		public function count_cart($session_id){
			
			$this->db->where('session_id', $session_id);
			$count_cart = $this->db->get('cart');
			if($count_cart->num_rows() > 0)	{
					
				$count = $count_cart->num_rows();
				return $count;
			}else {
				return false;
			}				
		}
		
		
		/****
		** Function to get all records from the database
		****/
		public function get_cart(){
			
			$this->db->order_by('id','DESC');
			$q = $this->db->get('cart');
			
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
		** Function to get all records from the database
		****/
		public function get_users_cart($session_id){
			
			$this->db->where('session_id', $session_id);
			$this->db->order_by('id','DESC');
			$q = $this->db->get('cart');
			
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
		* to the cart table in the database
		* @param $string Activation key
		*/		
		public function insert_cart($data){

			$query  = $this->db->insert('cart', $data);
			
			if ($query ){
				return true;
			}else {
				return false;
			}
		}
		
		/**
		* Function to update
		* the cart
		* variable array $data int $id
		*/	
		public function update_cart($data, $id){
			
			$this->db->where('id', $id);
			$query = $this->db->update('cart', $data);
			
			if ($query){	
				return true;
			}else {
				return false;
			}			
			
		}
		
		
		// Add an item to the cart
		function validate_add_cart_item(){
			 
			$id = $this->input->post('product_id'); // Assign posted product_id to $id
			$qty = $this->input->post('product_qty'); // Assign posted quantity to $cty
			$price = $this->input->post('product-price');
			
			$this->db->where('id', $id); // Select where id matches the posted id
			$this->db->where('price', $price);
			$query = $this->db->get('products', 1); // Select the products where a match is found and limit the query by 1
			 
			// Check if a row has matched our product id
			if($query->num_rows > 0){
			 
				// We have a match!
				foreach ($query->result() as $row)
				{
					
					$session_id = $this->session->userdata('cart_session_id');
					
					//Create an array with product information
					$data = array(
						'session_id'      => $session_id,
						'product_id'      => $id,
						'quantity'     => $qty,
						'product_price'   => $price,
						'product_name'    => $row->name,
						'username'     => $username,
					);
		 
					// Add the data to the cart using the insert function that is available because we loaded the cart library
					$this->insert_cart($data); 
					 
					return TRUE; // Finally return TRUE
				}
			 
			}else{
				// Nothing found! Return FALSE! 
				return FALSE;
			}
		}


		/* Function to check that the email address 
		* exists in the database
		*/	
		public function is_unique_cart_session($session_id){
			
			$this->db->where('session_id', $session_id);
			$query = $this->db->get('cart');
			
			if ($query->num_rows() == 0){
				return true;
			} else {
				return false;
			}
		}

		/* Function to check that the email address 
		* exists in the database
		*/	
		public function check_pid_exists($pid){
			
			$this->db->where('product_id', $pid);
			
			$query = $this->db->get('cart');
			
			if ($query->num_rows() == 1){
				
				// we will store the results in the form of class methods by using $q->result()
				// if you want to store them as an array you can use $q->result_array()
				foreach ($q->result() as $row){
					$data[] = $row;
				}
				return $data;
				
			} else{
				return false;
			}
		}
						
		
		
	
}