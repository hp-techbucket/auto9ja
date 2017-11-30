<?php

class Colours_model extends MY_Model {
    
    const DB_TABLE = 'colours';
    const DB_TABLE_PK = 'id';

	

	var $table = 'colours';
	
    var $column_order = array(null, 'colour_name'); //set column field database for datatable orderable
	
    var $column_search = array('colour_name'); //set column field database for datatable searchable 
	
    var $order = array('id' => 'asc'); // default order 
    
	
	/**
     * colour_name.
     * @var string 
     */
    public $colour_name;


		
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
		* Function to add colour
		* to the database
		* @param $array
		*/		
		public function insert_colour($data){

			$query  = $this->db->insert($this->table, $data);
			$insert_id = $this->db->insert_id();
			
			if ($query ){
				return $insert_id;
			}else {
				return false;
			}
		}
		
  
	public function get_colours(){
		
		$this->db->from($this->table);
		$this->db->order_by('id');
		$result = $this->db->get();
		$return = array();
		
		if($result->num_rows() > 0) {
			foreach($result->result_array() as $row) {
				$return[$row['colour_name']] = $row['colour_name'];
			}
		}
		//for dropdown or select input
		return $return;
	}


		/**
		* Function to update
		* the colour 
		* variable $id
		*/	
		public function update_colour($data, $id){
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
			
			
		
		/* Function to check if the colour is unique 
		*  and insert if it is
		*/	
		public function unique_type($colour = ''){
			
			if($colour != ''){
				$this->db->where('LOWER(colour_name)', strtolower($colour));
			}
			
			$query = $this->db->get($this->table);
			
			if ($query->num_rows() == 0){
				$data = array(
					'colour_name' => strtolower($colour)
				);
				$this->db->insert($this->table, $data);
				return true;
			} else {
				return false;
			}
			
		}




	
	
	
}