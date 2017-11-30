<?php

class Order_details_model extends MY_Model {
    
    const DB_TABLE = 'order_details';
    const DB_TABLE_PK = 'id';



	var $table = 'order_details';
	
    var $column_order = array(null, 'order_reference','vehicle_id','quantity','price'); //set column field database for datatable orderable
	
    var $column_search = array('order_reference','vehicle_id','quantity','price'); //set column field database for datatable searchable 
	
    var $order = array('id' => 'desc'); // default order 
    
	
	/**
     * Order Reference.
     * @var string 
     */
    public $order_reference;

	
	/**
     * Item Vehicle ID.
     * @var int 
     */
    public $vehicle_id;

	
	/**
     * Item Quantity.
     * @var int 
     */
    public $quantity;

	
	/**
     * Item Price.
     * @var string 
     */
    public $price;


	
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
		* Function to add order detail
		* to the database
		* @param $array
		*/		
		public function insert_order_detail($data){
			
			if(!empty($data)){
				$this->db->insert($this->table, $data);
			}
			
			//$query  = $this->db->insert($this->table, $data);
			$insert_id = $this->db->insert_id();
			
			if ($insert_id){
				return true;
			}else {
				return false;
			}
		}

		
		/****
		** Function to get order details by referenceID from the database
		****/
		public function get_orders_by_reference($ref=null){
			
			//$this->db->limit($limit, $offset);
			if($ref !=null && $ref != ''){
				$this->db->where('order_reference',$ref);
			}
			
			$this->db->order_by('id','ASC');
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
		* the order detail
		* variable $id
		*/	
		public function update_order_detail($data, $id = ''){
			
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
			

		



	
	
	
}