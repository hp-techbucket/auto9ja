<?php

class MY_Model extends CI_Model {
    const DB_TABLE = 'abstract';
    const DB_TABLE_PK = 'abstract';

    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Create record.
     */
    private function insert() {
        $this->db->insert($this::DB_TABLE, $this);
        $this->{$this::DB_TABLE_PK} = $this->db->insert_id();
    }
    
    /**
     * Update record.
     */
    private function update() {
        $this->db->update($this::DB_TABLE, $this, $this::DB_TABLE_PK);
    }
    
    /**
     * Populate from an array or standard class.
     * @param mixed $row
     */
    public function populate($row) {
        foreach ($row as $key => $value) {
            $this->$key = $value;
        }
        
    }
    
    /**
     * Load from the database
     * @param int $id
     */
    public function load($id) {
        $query = $this->db->get_where($this::DB_TABLE, array(
            $this::DB_TABLE_PK => $id,));
        $this->populate($query->row());
    }
    
    /**
     * Delete the current record
     */
    public function delete() {
        $this->db->delete($this::DB_TABLE, array(
            $this::DB_TABLE_PK => $this->{$this::DB_TABLE_PK},
        ));
        unset($this->{$this::DB_TABLE_PK});
    }
    
    /**
     * Save the record
     */
    public function save() {
        if (isset($this->{$this::DB_TABLE_PK})){
            $this->update();
        }
        else {
            $this->insert();
        }
    }
    
    /**
     * Get an array of Models with an optional limit, offset.
     * @param int $limit Optional.
     * @param int $offset Optional; if set, requires $limit.
     * @return array Models populated by database, keyed by PK.
     */
    public function get($limit = 0, $offset = 0) {
        if ($limit) {
            $query = $this->db->get($this::DB_TABLE, $limit, $offset);
        }
        else{
            $query = $this->db->get($this::DB_TABLE);
        }
        $ret_val = array();
        $class = get_class($this);
        foreach ($query->result() as $row) {
            $model = new $class;
            $model->populate($row);
            $ret_val[$row->{$this::DB_TABLE_PK}] = $model;
        }
        return $ret_val;
    }
    
	
	//Overide ip_address() with your own function
    public function ip(){
    
			$ipaddress = '';
			
			if ($this->input->server('HTTP_CLIENT_IP')) {
				$ipaddress = $this->input->server('HTTP_CLIENT_IP');
			}
			else if($this->input->server('HTTP_X_FORWARDED_FOR')) {
				$ipaddress = $this->input->server('HTTP_X_FORWARDED_FOR');
			}
			else if($this->input->server('HTTP_X_FORWARDED')){
				$ipaddress = $this->input->server('HTTP_X_FORWARDED');
			}
			else if($this->input->server('HTTP_FORWARDED_FOR')){
				$ipaddress = $this->input->server('HTTP_FORWARDED_FOR');
			}
			else if($this->input->server('HTTP_FORWARDED')){
				$ipaddress = $this->input->server('HTTP_FORWARDED');
			}
			else if($this->input->server('REMOTE_ADDR')) {
				$ipaddress = $this->input->server('REMOTE_ADDR');
			}
			else {
				$ipaddress = 'UNKNOWN';
			}	
			
			return $ipaddress;
	}
	
	
	
	
	
	
}

