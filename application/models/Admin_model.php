<?php

class Admin_model extends MY_Model {
 	
	const DB_TABLE = 'admin_users';
    const DB_TABLE_PK = 'id';
	
	var $table = 'admin_users';
    var $column_order = array(null, 'avatar','admin_username','admin_name','access_level','date_created','last_login'); //set column field database for datatable orderable
    var $column_search = array('admin_username','admin_name','access_level','date_created','last_login'); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 

	
    /**
     * Admin avatar.
     * @var string
     */
    public $avatar;

	
    /**
     * Admin Username.
     * @var string
     */
    public $admin_username;

	
    /**
     * Admin Password.
     * @var string
     */
    public $admin_password;

	
    /**
     * admin_name.
     * @var string
     */
    public $admin_name;		
	
    /**
     * access level.
     * @var string
     */
    public $access_level;		
		
    /**
     * Date Created.
     * @var string
     */
    public $date_created;	
	
    /**
     * Last Login.
     * @var string
     */
    public $last_login;		

	
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
		* Function to check that the username and password 
		* exists in the database
		*/
		/*	
		public function admin_can_log_in(){
			
			$this->db->where('LOWER(admin_username)', strtolower($this->input->post('username')));
			$this->db->where('admin_password', md5($this->input->post('password')));
			
			$query = $this->db->get($this->table);
			
			if ($query->num_rows() == 1){
				return true;
			} else {
				return false;
			}
			
		}
		*/
		public function admin_can_log_in(){
			
			$username = strtolower($this->input->post('username'));
			$password = $this->input->post('password');
			$db_password = '';
			
			//get users info frm db using username
			$user_array = $this->get_user($username);
			
			//check if email exists
			if($user_array){
				//get stored password
				foreach($user_array as $user){
					$db_password = $user->admin_password;
				}
				// If the password inputs matched the hashed password in the database
				if(password_verify($password, $db_password)) {
					return true;
				}else{
					return false;
				} 
			}else {
				return false;
			}
			
		}
		
		/**
		* Function to check that the admin user 
		* has access
		*/
		public function check_admin_access(){
			
			$username = $this->session->userdata('admin_username');

			$this->db->where('admin_username', $username);
			$this->db->where('access_level >', '2');
			
			$query = $this->db->get($this->table);
			
			if ($query->num_rows() == 1){
				return true;
			} else {
				return false;
			}
			
		}		

		
		/**
		* Function to create admin
		*
		*/	
		public function create_admin($data){
				
			$this->db->insert($this->table, $data);
			$insert_id = $this->db->insert_id();
			if ($insert_id){
				return $insert_id;
			}else {
				return false;
			}
		}
	
		/**
		* Function to update admin
		* variable $id
		*/	
		public function update_admin($data, $id=null){
			
			//$username = $this->session->userdata('admin_username');
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
	
		/**
		* Function to update admin
		* variable $username
		*/	
		public function update_user($data, $username = ''){
			
			//$username = $this->session->userdata('admin_username');
			if($username != '' && $username != null){
				$this->db->where('admin_username', $username);
			}
			
			$query = $this->db->update($this->table, $data);
			
			if ($query){	
				return true;
			}else {
				return false;
			}			
			
		}		
		
		/****
		** Function to get admin user from the database
		****/
		function get_user($username = ''){
			
			if($username != '' && $username != null){
				$this->db->where('admin_username', $username);
			}
			
			$q = $this->db->get($this->table);
			
			if($q->num_rows() > 0){
				
			  // we will store the results in the form of class methods by using $q->result()
			  // if you want to store them as an array you can use $q->result_array()
			  foreach ($q->result() as $row)
			  {
				$data[] = $row;
			  }
			  return $data;
			}

		}		


		
		/****
		** Function to get all admin users 
		** from the database
		****/
		function get_all_usernames(){
			
			$this->db->select('admin_username');
			$this->db->from($this->table);
			$q = $this->db->get();
			
			return $q->result_array();

		}		

		
		

}