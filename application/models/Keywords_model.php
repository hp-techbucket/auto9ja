<?php

class Keywords_model extends MY_Model {
    
    const DB_TABLE = 'keywords';
    const DB_TABLE_PK = 'id';


	var $table = 'keywords';
	
    var $column_order = array(null, 'keyword','icon'); //set column field database for datatable orderable
	
    var $column_search = array('keyword','icon'); //set column field database for datatable searchable 
	
    var $order = array('id' => 'desc'); // default order 
    

    /**
     * keyword.
     * @var string 
     */
    public $keyword;
    

    /**
     * keyword icon.
     * @var string 
     */
    public $icon;

		
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
		* Function to insert keyword
		*  
		*/	
		public function add_keyword($data = array()){
			
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
	
		/**
		* Function to update keyword
		* variable $id
		*/	
		public function update_keyword($data, $id = ''){
			
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

	public function get_all_keywords(){

		$this->db->order_by('id','DESC');
		$tags = $this->db->get($this->table);
				
		if($tags->num_rows() > 0){

			foreach ($tags->result() as $row){
				$data[] = $row;
			}
			return $data;
				  
		}else{
			return false;
		}
	}		


	public function get_keywords($limit = 10, $start = 0){
				
		$this->db->limit($limit, $start);
		$this->db->order_by('id','DESC');
		$tags = $this->db->get($this->table);
				
		if($tags->num_rows() > 0){

			foreach ($tags->result() as $row){
				$data[] = $row;
			}
			return $data;
				  
		}else{
			return false;
		}
	}		

	

			


			
			
			
			
			
			
			
			
			


	
    
}

