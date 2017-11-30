<?php

class Subscription_list_model extends MY_Model {
    
    const DB_TABLE = 'subscription_list';
    const DB_TABLE_PK = 'id';


	var $table = 'subscription_list';
	
    var $column_order = array(null, 'ip_address','email'); //set column field database for datatable orderable
	
    var $column_search = array('ip_address','email'); //set column field database for datatable searchable 
	
    var $order = array('id' => 'desc'); // default order 
    

    /**
     * ip_address.
     * @var string 
     */
    public $ip_address;

    /**
     * email.
     * @var string 
     */
    public $email;

    /**
     * date_added.
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
		
		
	
		/**
		* Function to insert type
		*  
		*/	
		public function add_email($data){
				
			$this->db->insert($this->table, $data);
			$insert_id = $this->db->insert_id();
			if ($insert_id){
				return true;
			}else {
				return false;
			}
				
		}
	
		/**
		* Function to update subscription list
		* variable $id
		*/	
		public function update_subscription_list($data, $id){

			$this->db->where('id', $id);
			$query = $this->db->update($this->table, $data);
			
			if ($query){	
				return true;
			}else {
				return false;
			}			
			
		}

		/**
		* Function to check that the username and password 
		* exists in the database
		*/	
		public function unique_email($email){
			
			$this->db->where('email', $email);
			
			$query = $this->db->get($this->table);
			
			if ($query->num_rows() == 0){
				return true;
			} else {
				return false;
			}
			
		}			
			
			
			
			
			
			
			
			


	
    
}

