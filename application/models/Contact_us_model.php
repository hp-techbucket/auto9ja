<?php

class Contact_us_model extends MY_Model {
    
    const DB_TABLE = 'contact_us';
    const DB_TABLE_PK = 'id';


	var $table = 'contact_us';
	
    var $column_order = array(null, 'contact_name','contact_telephone','contact_email','contact_company','contact_message','ip_address','ip_details','opened','contact_us_date'); //set column field database for datatable orderable
	
    var $column_search = array('contact_name','contact_telephone','contact_email','contact_company','contact_message','ip_address','ip_details','opened','contact_us_date'); //set column field database for datatable searchable 
	
    var $order = array('id' => 'desc'); // default order 
      
    
    /**
     * Contact Name.
     * @var string 
     */
    public $contact_name;
 
     /**
     * Contact Telephone.
     * @var string
     */
    public $contact_telephone; 

    /**
     * Contact Email.
     * @var string
     */
    public $contact_email;
    
     /**
     * company.
     * @var string
     */
    public $contact_company;

    /**
     * Contact Us Message.
     * @var string 
     */
    public $contact_message;

    /**
     * Senders IP address.
     * @var string 
     */
    public $ip_address;

    /**
     * Senders IP details.
     * @var string 
     */
    public $ip_details;

    /**
     * message read or not.
     * @var string 
     */
    public $opened;

    /**
     * Date Sent.
     * @var string 
     */
    public $contact_us_date;


		
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
			
		
	
		public function add_contact_us($contact_data){
				
			$query = $this->db->insert($this->table, $contact_data);
			if ($query){
				return true;
			}else {
				return false;
			}
				
		}


		public function get_contact_us($limit, $start){
				
			$this->db->limit($limit, $start);
			$this->db->order_by('contact_us_date','DESC');
			$contacts = $this->db->get($this->table);
				
			if($contacts->num_rows() > 0){
					
				  // we will store the results in the form of class methods by using $q->result()
				  // if you want to store them as an array you can use $q->result_array()
				foreach ($contacts->result() as $row)
				{
					$data[] = $row;
				}
				return $data;
				  
			}else{
				return false;
			}
		}		


		 /**
		 * Function to count all messages
		 * @var string
		 */			
		public function count_all_messages(){

			$count_messages = $this->db->get($this->table);
				
			if($count_messages->num_rows() > 0)	{
				$count = $count_messages->num_rows();
				return $count;
			}else {	
				return false;
			}			
		}

		 /**
		 * Function to count unread messages
		 * @var string
		 */			
		public function count_unread_messages(){

			$this->db->where('opened', '0');
			$count_messages = $this->db->get($this->table);
				
			if($count_messages->num_rows() > 0)	{
				$count = $count_messages->num_rows();
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
			
			$this->db->where("contact_us_date < '$min_date'", NULL, FALSE);
			$this->db->delete($this->table);
		}
			



	
    
}

