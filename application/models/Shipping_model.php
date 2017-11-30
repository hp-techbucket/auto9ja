<?php

class Shipping_model extends MY_Model {
    
    const DB_TABLE = 'shipping';
    const DB_TABLE_PK = 'id';


	var $table = 'shipping';
	
	var $column_order = array(null,'order_reference','shipping_fee','tax','shipping_method','origin_city','origin_country','destination_zip','destination_city','destination_country','customer_contact_phone','customer_email','status','estimated_delivery_date','created'); //set column field database for datatable orderable
		
	var $column_search = array('order_reference','shipping_fee','tax','shipping_method','origin_city','origin_country','destination_zip','destination_city','destination_country','customer_contact_phone','customer_email','status','estimated_delivery_date','created'); //set column field database for datatable searchable  
	
    var $order = array('id' => 'desc'); // default order 
    
	
	/**
     * Order Reference.
     * @var string 
     */
    public $order_reference;
		
		
	/**
	 * shipping fee.
	 * @var string 
	*/
	public $shipping_fee;
		
		
	/**
	 * shipping tax.
	 * @var string 
	*/
	public $tax;
				
		
	/**
	 * shipping method.
	 * @var string 
	*/
	public $shipping_method;

	
	/**
     * Sender Origin City.
     * @var string 
     */
    public $origin_city;


	/**
     * Sender Origin country.
     * @var string 
     */
    public $origin_country;

	/**
     * Customer Destination City.
     * @var string 
     */
    public $destination_city;


	/**
     * Customer Destination country.
     * @var string 
     */
    public $destination_country;
								
	/**
	 * email address of the customer.
	 * @var string 
	*/
	public $customer_email;
								
	/**
	 * status Of Shipping.
	 * @var Enum 
	*/
	public $status;
								
	/**
	 * Estimated Date of Delivery.
	 * @var Enum 
	*/
	public $estimated_delivery_date;
						

	/**
     * Created Date.
     * @var string 
     */
    public $created;



		
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
		
			
		private function _get_user_datatables_query()
		{
			$email = $this->session->userdata('email');
			
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
			$this->db->where('customer_email', $email);	
		}
		
		function get_user_datatables()
		{
			$this->_get_user_datatables_query();
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
			$query = $this->db->get();
			return $query->result();
		}
	 
		function count_user_filtered()
		{
			$this->_get_user_datatables_query();
			$query = $this->db->get();
			return $query->num_rows();
		}
	 
		public function count_user_all()
		{
			$email = $this->session->userdata('email');
			$this->db->where('customer_email', $email);	
			$query = $this->db->get($this->table);
			return $query->num_rows();
		}	
							
				
	
	/**
		* Function to add shipping 
		* to the database
		* @param $array
		*/		
		public function insert_shipping($data = array()){
			
			if(!empty($data)){
				$this->db->insert($this->table, $data);
			}
			
			$insert_id = $this->db->insert_id();
			
			if ($insert_id){
				return true;
			}else {
				return false;
			}
		}

		
		/****
		** Function to get all records from the database
		****/
		public function get_shippings($limit, $offset){
			
			$this->db->limit($limit, $offset);
			$this->db->order_by('id','DESC');
			$q = $this->db->get($this->table);
			
			if($q->num_rows() > 0){
		
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
		public function get_shipping($ref = ''){
			
			if($ref != null && $ref != ''){
				$this->db->where('order_reference',$ref);
			}
			
			$q = $this->db->get($this->table);
			
			if($q->num_rows() > 0){
		
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
		* the shipping
		* variable $id
		*/	
		public function update_shipping($data, $id){
			
			if($id != '' && $id != null){
				$this->db->where('id', $id);
			}
			
			$query = $this->db->update($this->table, $data);
			
			if ($query){	
				return true;
			}else {
				return false;
			}			
			
		}
			
	
		/* Function to ensure the shipping data is unique 
		* 
		*/	
		public function unique_shipping($where = array()){
			
			if(!empty($where)){
				$this->db->where($where);
			}
			
			$query = $this->db->get($this->table);
			
			if ($query->num_rows() == 0){
				return true;
			} else {
				return false;
			}
			
		}
			
		



	
	
	
}