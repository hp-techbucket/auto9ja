<?php

class Messages_model extends MY_Model {
    
    const DB_TABLE = 'messages';
    const DB_TABLE_PK = 'id';


	var $table = 'messages';
	
    var $column_order = array(null, 'sender_name','sender_email','receiver_name','receiver_email','message_subject','message_details','attachment','opened','recipient_archive','recipient_delete','sender_archive','sender_delete','replied','date_sent'); //set column field database for datatable orderable
	
    var $column_search = array('sender_name','sender_email','receiver_name','receiver_email','message_subject','message_details','attachment','opened','recipient_archive','recipient_delete','sender_archive','sender_delete','replied','date_sent'); //set column field database for datatable searchable 
	
    var $order = array('id' => 'desc'); // default order 
 
	
	
    /**
     * Sender name.
     * @var string 
     */
    public $sender_name;
	
    /**
     * Sender email.
     * @var int 
     */
    public $sender_email;
	
    /**
     * Receiver full name.
     * @var string 
     */
    public $receiver_name;	
    	
    /**
     * Receiver email.
     * @var int 
     */
    public $receiver_email;	
    
    /**
     * Message Subject.
     * @var string 
     */
    public $message_subject;
    
    /**
     * Message Details.
     * @var string 
     */
    public $message_details;
	
    /**
     * Message contains attachment or not.
     * @var int 
     */
    public $attachment;	
		
    /**
     * Message read or not.
     * @var int 
     */
    public $opened;	
	
    /**
     * recipient archive or not.
     * @var int 
     */
    public $recipient_archive;	
		
    /**
     * recipient delete or not.
     * @var int 
     */
    public $recipient_delete;	
	
    /**
     * sender archive or not.
     * @var int 
     */
    public $sender_archive;	
		
    /**
     * sender delete or not.
     * @var int 
     */
    public $sender_delete;	
	
    /**
     * Message replied or not.
     * @var int 
     */
    public $replied;	
				
    /**
     * Date sent.
     * @var string 
     */
    public $date_sent;		
	
		

		
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
	
	
	/*
	*	DATATABLE FUNCTION FOR USER INBOX
	*
	*/
	private function _get_inbox_datatables_query()
    {
         $email = $this->session->userdata('email');
		
		if($this->session->userdata('admin_logged_in')){
			$email = $this->session->userdata('admin_username');
		}
			
		
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
		$this->db->where('receiver_email', $email);
		$this->db->where('recipient_archive', '0');
		$this->db->where('recipient_delete', '0');
    }
	
    function get_inbox_datatables()
    {
		$this->_get_inbox_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
    }
			
 
    function count_filtered_inbox(){
        $this->_get_inbox_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 			
			
	public function count_inbox_all(){
		
		$email = $this->session->userdata('email');
		
		if($this->session->userdata('admin_logged_in')){
			$email = $this->session->userdata('admin_username');
		}
		
		$this->db->where('receiver_email', $email);
		$this->db->where('recipient_archive', '0');
		$this->db->where('recipient_delete', '0');
		$query = $this->db->get($this->table);
		return $query->num_rows();			

	} 
	///END DATATABLE FUNCTION FOR USER INBOX
	
	/*
	*	DATATABLE FUNCTION FOR USER SENT MESSAGES
	*
	*/		
	private function _get_sent_datatables_query()
    {
         $email = $this->session->userdata('email');
		
		if($this->session->userdata('admin_logged_in')){
			$email = $this->session->userdata('admin_username');
		}
			
		
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
		$this->db->where('sender_email', $email);
		$this->db->where('sender_archive', '0');
		$this->db->where('sender_delete', '0');
    }	
 	
    function get_sent_datatables(){
		
		$this->_get_sent_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
    }
	
    function count_filtered_sent(){
		
		$this->_get_sent_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 			
	public function count_sent_all(){
				
		$email = $this->session->userdata('email');
		
		if($this->session->userdata('admin_logged_in')){
			$email = $this->session->userdata('admin_username');
		}
			
		$this->db->where('sender_email', $email);
		$this->db->where('sender_archive', '0');
		$this->db->where('sender_delete', '0');
		$query = $this->db->get($this->table);
		return $query->num_rows();	
	}			
	// END DATATABLE FUNCTION FOR USER SENT MESSAGES	

	
	/*
	*	DATATABLE FUNCTION FOR USER ARCHIVE
	*
	*/
	private function _get_archive_datatables_query()
    {
         $email = $this->session->userdata('email');
		
		if($this->session->userdata('admin_logged_in')){
			$email = $this->session->userdata('admin_username');
		}
			
		
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
		$this->db->where('receiver_email', $email);
		$this->db->where('recipient_archive', '1');
		$this->db->where('recipient_delete', '0');
    }
	
    function get_archive_datatables()
    {
		$this->_get_archive_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
    }
			
 
    function count_filtered_archive(){
        $this->_get_archive_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 			
			
	public function count_archive_all(){
		
		$email = $this->session->userdata('email');
		
		if($this->session->userdata('admin_logged_in')){
			$email = $this->session->userdata('admin_username');
		}
		
		$this->db->where('receiver_email', $email);
		$this->db->where('recipient_archive', '1');
		$this->db->where('recipient_delete', '0');
		$query = $this->db->get($this->table);
		return $query->num_rows();			

	} 
	///END DATATABLE FUNCTION FOR USER ARCHIVE
		

		/****
		** Function to get 5 recent messages for the header
		****/
		public function get_header_messages($email = ''){
				
			//$limit = $this->count_unread_messages($email);
				
			$this->db->limit(5, 0);
			
			$where = array(
				'receiver_email' => $email,
				'opened' => '0',
				'recipient_archive' => '0',
				'recipient_delete' => '0',
			);
			
			if(!empty($email)){
				$this->db->where('receiver_email', $email);
			}
			$this->db->where('opened', '0');
			$this->db->where('recipient_archive', '0');
			$this->db->where('recipient_delete', '0');
			$this->db->order_by('date_sent','DESC');
			$q = $this->db->get($this->table);
				
			if($q->num_rows() > 0){
				foreach ($q->result() as $row){
					$data[] = $row;
				}
				  return $data;
			}

		}	

		//function to send a reply
		public function reply_message($data, $id = ''){
				
			$reply_data = array (
				'replied' => '1',
			);

			$this->db->insert($this->table, $data);				
			$insert_id = $this->db->insert_id();
			
			if ($insert_id){
				
				if($id != '' && $id != null){
					$this->db->where('id', $id);
				}
				
				$update = $this->db->update('messages', $reply_data);			
					
				return $insert_id;
			}else {
				return false;
			}					
		}

		//function to send a new message to admin
		public function send_new_message($data = array()){
			
			$this->db->insert($this->table, $data);
			$insert_id = $this->db->insert_id();
			
			if ($insert_id){
				return $insert_id;
			}else {
				return false;
			}					
		}
			
		public function count_unread_messages($email = ''){
			
			if(!empty($email)){
				$this->db->where('receiver_email', $email);
			}

			$this->db->where('opened', '0');
			$this->db->where('recipient_delete', '0');
			$count_messages = $this->db->get($this->table);
				
			if($count_messages->num_rows() > 0)	{
					
				$count = $count_messages->num_rows();
				return $count;
			}else {
					
				return false;
			}			
				
		}
			
		public function count_sent_messages($email = ''){
			
			if(!empty($email)){
				$this->db->where('sender_email', $email);
			}

			$this->db->where('sender_delete', '0');
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
			
			$this->db->where("date_sent < '$min_date'", NULL, FALSE);
			$this->db->delete($this->table);
		}

		
		public function email($to_email = '', $subject = '', $message = '', $attachment = ''){
				
				//validate emails
				if($this->validateEmail($to_email)){
					//Load email library
					$this->load->library('email');
					
					$config = Array(
					  'protocol' => 'smtp',
					  'validate' => FALSE,
					  'smtp_host' => 'ssl://cp-45.webhostbox.net',
					  'smtp_port' => 465,
					  'smtp_user' => 'info@gialovela.com', // change it to yours
					  'smtp_pass' => 'Liverpool2k', // change it to yours
					  'mailtype' => 'html',
					  'charset' => 'utf-8',
					  'wordwrap' => TRUE,
					  'newline' => "\r\n"
					);
					
					 
					$this->email->initialize($config); 
					
					$to_mail = explode(',', $to_email);
					
					$mail_count= count($to_mail);
					
					for($i=0;$i<$mail_count;$i++){
						
						 $email = TRIM($to_mail[$i]);
						 
						$this->email->from('info@dejor.com', 'Dejor Autos');
						$this->email->to($email);
						$this->email->subject($subject); 
						$this->email->message($message); 
						
						if($attachment != ''){
							$this->email->attach($attachment);
						}
					
						 $this->email->send();
						 $this->email->clear();
					}
					
					return true;
					
				}else{
					return false;
				}			
				
		}
		

		
		public function email2($to_email = '', $subject = '', $message = '', $attachment = ''){
							
				//Load email library
				$this->load->library('email');
				
				$config = Array(
				  'protocol' => 'smtp',
				  'validate' => FALSE,
				  'smtp_host' => 'ssl://cp-45.webhostbox.net',
				  'smtp_port' => 465,
				  'smtp_user' => 'info@gialovela.com', // change it to yours
				  'smtp_pass' => 'Liverpool2k', // change it to yours
				  'mailtype' => 'html',
				  'charset' => 'utf-8',
				  'wordwrap' => TRUE,
				  'newline' => "\r\n"
				);
				
				 
				$this->email->initialize($config); 
				
				$this->email->from('info@gialovela.com', 'Gialo Vela'); 
				$this->email->to($to_email);
				$this->email->reply_to('gialovela@gmail.com', 'Gialo Vela');
				$this->email->subject($subject); 
				$this->email->message($message); 
				
				if($attachment != ''){
					$this->email->attach($attachment);
				}
				
				//Send mail 
				if($this->email->send()){
					return true;
				}else{
					return false;
				} 
		}
					
		/**
		 * Function to send_email_alert
		 * @var string
		 */			
		public function send_email_alert($to = '', $subject = '', $fullname = '', $message = ''){
			//send email
			$ci = get_instance();
			$ci->load->library('email');
											
			$config['protocol'] = $ci->config->item('protocol');
			$config['validate'] = $ci->config->item('validate');
			$config['smtp_host'] = $ci->config->item('smtp_host');
			$config['smtp_port'] = $ci->config->item('smtp_port');
			$config['smtp_user'] = $ci->config->item('smtp_user'); 
			$config['smtp_pass'] = $ci->config->item('smtp_pass');
			$config['charset'] = $ci->config->item('charset');
			$config['mailtype'] = $ci->config->item('mailtype');
			$config['newline'] = $ci->config->item('newline');

			$ci->email->initialize($config);
			
			//template
			//compose email message
			$template = "<div style='font-size: 1.0em; border: 1px solid #D0D0D0; border-radius: 3px; margin: 5px; padding: 10px; '>";
			$template .= '<div align="center" id="logo"><a href="'.base_url().'" title="Dejor Autos">'.img('assets/images/logo/logo.png').'</a></div><br/>';
						
			$template .= '<p>Hello '.$fullname. ',</p>';
			$template .= $message;
			$template .= "</div>";
					
			
			//setup email function
			$ci->email->from('info@gialovela.com', 'Dejor Autos');
			$ci->email->to($to);
			$ci->email->reply_to('info@gialovela.com', 'Dejor Autos');
			$ci->email->subject($subject);
			$ci->email->message($template);
			$ci->email->send();				
			
		}															
			
		
		public function validateEmail($address) {
			$validate = array();
			if(strlen($address) < 1){
				return false;
			}
			
			$emailAddresses = explode(",", $address);
			foreach($emailAddresses as $emailAdd){
				$emailAdd = trim($emailAdd);
				if (!preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])*(\.([a-z0-9])([-a-z0-9_-])([a-z0-9])+)*$/i', $emailAdd)){
					// if validation fails, add error to array
					$validate[$emailAdd] = 1;
				}
			}
			
			if(count($validate) == 0){
				// No errors added to the array
				return true;		
			} else {			
				return false;
			}
			
		}	
	
	
}