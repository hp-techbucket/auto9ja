<?php

class Failed_logins_model extends MY_Model {
    
    const DB_TABLE = 'failed_logins';
    const DB_TABLE_PK = 'id';


	var $table = 'failed_logins';
	
    var $column_order = array(null, 'ip_address','ip_details','username','password','attempt_time'); //set column field database for datatable orderable
	
    var $column_search = array('ip_address','ip_details','username','password','attempt_time'); //set column field database for datatable searchable 
	
    var $order = array('id' => 'desc'); // default order 
    
	
	/**
     * IP ADDRESS OF THE USER.
     * @var string 
     */
    public $ip_address;
	
	/**
     * HOSTNAME OF THE IP ADDRESS.
     * @var string 
     */
    public $hostname;
	
	/**
     * CITY OF THE IP ADDRESS.
     * @var string 
     */
    public $city;
	
	/**
     * REGION OF THE IP ADDRESS.
     * @var string 
     */
    public $region;
		
	/**
     * COUNTRY OF THE IP ADDRESS.
     * @var string 
     */
    public $country;
			
	/**
     * LOC OF THE IP ADDRESS.
     * @var string 
     */
    public $loc;
			
	/**
     * ORG OF THE IP ADDRESS.
     * @var string 
     */
    public $org;
		
	/**
     * USERNAME.
     * @var string 
     */
    public $username;

     /**
     * PASSWORD OF USER.
     * @var string
     */
    public $password; 

	 /**
     * TIME OF USER LOGIN.
     * @var string 
     */
    public $attempt_time;
	
		
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
    * failed login
    */
	public function insert_failed_login($data){
		
			$query = $this->db->insert($this->table, $data);
			
			if ($query){
				return true;
			}else {
				return false;
			}		
		
	}
	

		
		/****
		** Function to get logins from the database
		****/
		function get_failed_logins($limit = 10, $offset = 0){
			
			$this->db->limit($limit, $offset);
			$q = $this->db->get($this->table);
			
			if($q->num_rows() > 0){

			  foreach ($q->result() as $row){
				$data[] = $row;
			  }
			  return $data;
			}
		}		
			
			
	public function count_failed_logins(){
				
		$count_failed_logins = $this->db->get($this->table);
				
		if($count_failed_logins->num_rows() > 0)	{
			$count = $count_failed_logins->num_rows();
			return $count;
		}else {	
			return false;
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
			
			$this->db->where("attempt_time < '$min_date'", NULL, FALSE);
			$this->db->delete($this->table);
		}
						
			
			
			
			
			
	
	
	
}