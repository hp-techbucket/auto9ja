<?php

class Page_metadata_model extends MY_Model {
    
    const DB_TABLE = 'page_metadata';
    const DB_TABLE_PK = 'id';



	var $table = 'page_metadata';
	
    var $column_order = array(null, 'page','keywords','description'); //set column field database for datatable orderable
	
    var $column_search = array('page','keywords','description'); //set column field database for datatable searchable 
	
    var $order = array('id' => 'asc'); // default order 
    
	
	/**
     * page.
     * @var string 
     */
    public $page;

	
	/**
     * keywords.
     * @var string 
     */
    public $keywords;

	
	/**
     * description.
     * @var string 
     */
    public $description;


		
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
		* Function to add page metadata 
		* to the database
		* @param $array
		*/		
		public function insert_page_metadata($data = array()){
			
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
		public function get_page_metadata($page){
			
			$this->db->where('page', $page);
			$q = $this->db->get($this->table);
			
			if($q->num_rows() > 0){
			  foreach ($q->result() as $row){
				$data[] = $row;
			  }
			  return $data;
			}
			return false;
		}
		
		
		/**
		* Function to update
		* the page metadata
		* variable $id
		*/	
		public function update_page_metadata($data, $id){
			
			$this->db->where('id', $id);
			$query = $this->db->update($this->table, $data);
			
			if ($query){	
				return true;
			}else {
				return false;
			}			
			
		}
			




	
	
	
}