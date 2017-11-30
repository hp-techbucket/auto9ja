<?php

class Site_activities_model extends MY_Model {
    
    const DB_TABLE = 'site_activities';
    const DB_TABLE_PK = 'id';

    
		var $table = 'site_activities';
		
		var $column_order = array(null, 'name','username','description','keyword','activity_time'); //set column field database for datatable orderable
		
		var $column_search = array('name','username','description','keyword','activity_time'); //set column field database for datatable searchable 
		
		var $order = array('id' => 'desc'); // default order 
			
		/**
		 * name.
		 * @var string 
		 */
		public $name;
		

			
		/**
		 * username.
		 * @var string 
		 */
		public $username;
		
		/**
		 * description.
		 * @var string 
		 */
		public $description;
		
		/**
		 * keyword.
		 * @var string 
		 */
		public $keyword;
			
		/**
		 * activity_time.
		 * @var string 
		 */
		public $activity_time;
		
					
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
		* Function to insert
		* site activity
		* variable $data
		*/	
		public function insert_activity($data){

			$query = $this->db->insert('site_activities', $data);
			
			if ($query){	
				return true;
			}else {
				return false;
			}			
			
		}				
		
			
		public function get_activities($limit=15,$offset=0){
			
				$this->db->limit($limit,$offset);
				$this->db->order_by('activity_time','DESC');
				$q = $this->db->get('site_activities');
				
			if($q->num_rows() > 0){
					
			  // we will store the results in the form of class methods by using $q->result()
			  // if you want to store them as an array you can use $q->result_array()
			foreach ($q->result() as $row){
				$data[] = $row;
			}
				return $data;
			}
		} 
		

	  
		public function get_user_activities($email = '', $limit=5, $start=0){
			
			$this->db->limit($limit, $start);
			
			if($email != '' && $email != null){
				$this->db->where('username', $email);
			}
			
			$this->db->order_by('id','DESC');
			$q = $this->db->get('site_activities');
				
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
	  
	  
		public function get_site_activities($limit, $start){
			
			$this->db->limit($limit, $start);
			$this->db->order_by('id','DESC');
			$q = $this->db->get('site_activities');
				
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

			
		
		/***
		** Function to convert timestamp to time ago
		** $this->Site_activities->time_elapsed_string(strtotime($user->last_login))
		***/
		public function time_elapsed_string($ptime)
		{
			$etime = time() - $ptime;

			if ($etime < 1)
			{
				return '0 seconds';
			}

			$a = array( 365 * 24 * 60 * 60  =>  'year',
						 30 * 24 * 60 * 60  =>  'month',
							  24 * 60 * 60  =>  'day',
								   60 * 60  =>  'hour',
										60  =>  'minute',
										 1  =>  'second'
						);
			$a_plural = array( 'year'   => 'years',
							   'month'  => 'months',
							   'day'    => 'days',
							   'hour'   => 'hours',
							   'minute' => 'minutes',
							   'second' => 'seconds'
						);

			foreach ($a as $secs => $str)
			{
				$d = $etime / $secs;
				if ($d >= 1)
				{
					$r = round($d);
					return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
				}
			}
		}

		
		/**
		* Function to delete old records
		*  
		*/		
		public function delete_old_records(){
			
			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			//delete records older than 90 days
			$min_date = strtotime("-90 day", $date);
			
			$this->db->where("activity_time < '$min_date'", NULL, FALSE);
			$this->db->delete($this->table);
		}
			


	
	
	
}