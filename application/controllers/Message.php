<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Controller {
		
		/**
		* Function for controller
		*  index
		*/	
		public function index(){
			
			if($this->session->userdata('logged_in')){
			
				redirect('message/inbox');
			
			}else if($this->session->userdata('trader_logged_in')){
			
				redirect('message/private_inbox');
			
			}else{
				
				redirect('home');	
			}
		}
		
		/**
		* Function to display
		* inbox messages
		*/	
		public function inbox($search = null){
										
			if($this->session->userdata('logged_in')){	
				
				//set cart count
				$data['cart_count'] = 0;
					
				//check if user has started shopping
				//update cart accordingly
				if($this->session->userdata('cart_array')){ 
					
					//obtain the shopping session id
					$session_id = $this->session->userdata('cart_array');
						
					$cart_count = $this->Cart->count_cart($session_id);
					if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
					}
					$data['cart_count'] = $cart_count;
						
				}	
				
				$email_address = $this->session->userdata('email_address');
				
				$data['users'] = $this->Customers->get_customer($email_address);
				
				
				//set payments count
				$count_payments = $this->Orders->count_orders($email_address, 'Paid');
				if($count_payments == '' || $count_payments == null){
					$count_payments = 0;
				}
				
				$data['payments_count'] = $count_payments;
				
				//display pending payments count 
				$pending_payments_count = $this->Orders->count_orders($email_address, 'Pending');
				if($pending_payments_count == '' || $pending_payments_count == null){
					$pending_payments_count = 0;
				}
				$data['pending_payments_count'] = $pending_payments_count;
				 
				//get watchlist count
				$watchlist_count = $this->Watchlist->count_watchlist($email_address);
				
				if($watchlist_count == '' || $watchlist_count == null){
					$watchlist_count = 0;
				}
				$data['watchlist_count'] = $watchlist_count;
				
				//get shipping status count
				$shipping_count = $this->Shipping_status->count_shipping($email_address);
				
				if($shipping_count == '' || $shipping_count == null){
					$shipping_count = 0;
				}
				$data['shipping_count'] = $shipping_count;
				
				//get unread messages
				$data['header_messages_array'] = $this->Messages->get_header_messages();
				
				//set saved searches count
				$data['saved_searches_count'] = 0;
				
				$messages_unread = $this->Messages->count_unread_messages($email_address);
				
				if($messages_unread == '' || $messages_unread == null){
					$messages_unread = 0;
				}
				
				$data['messages_unread'] = $messages_unread;
				
					
				$config = array();
				
				if($this->input->get('search') != ''){
						
						$search = html_escape($this->input->get('search'));
						// get search string
						$search = html_escape($this->input->get('search'));
						$search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;
						$count = $this->Messages->count_search($search, $email_address);
						if($count == '' || $count == null){
							$count = 0;
						}
				
						$data['count'] = $count;
						
						$data['display_option'] = 'Showing Results for "<strong><em>'.$search.'</em></strong>" <a href="'.base_url("message/inbox/$search").'"  >Show All</a>';
						
						$config["base_url"] = base_url("message/inbox/$search");
						$config["total_rows"] = $count;
						$config["per_page"] = 10;
						$config["uri_segment"] = 3;
						$choice = $config["total_rows"] / $config["per_page"];
						$config["num_links"] = round($choice);
					
						$this->pagination->initialize($config);
						
						if($this->uri->segment(3) > 0)
							$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
						else
							$offset = $this->uri->segment(3);					
						
						$data['messages_array'] = $this->Messages->get_search($email_address, $search, $config["per_page"], $offset);	
						
				}else{	
				
					$data['display_option'] = '<strong>Showing All</strong>';
					$config["base_url"] = base_url("message/inbox");
					$config["total_rows"] = $this->Messages->count_received_messages($email_address);
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
			
					$this->pagination->initialize($config);
						
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
								
					//call the model function to get the messages data
					$data['messages_array'] = $this->Messages->get_message($email_address, $config["per_page"], $offset);	
					
					$data['count'] = $this->Messages->count_received_messages($email_address);
				}
				$data['pagination'] = $this->pagination->create_links();
				
				//assign additional breadcrumb
				$data['breadcrumb'] = '<li><a href="javascript:void(0)" onclick="location.href=\''.base_url('message/sent/').'\'" title="Sent Messages" ><i class="fa fa-paper-plane"></i> Sent Messages</a></li>';
				
				$data['page_breadcrumb'] = '<i class="fa fa-inbox"></i> Private Inbox';
				
				//assign page title name
				$data['pageTitle'] = 'Private Inbox';
				
				//assign page ID
				$data['pageID'] = 'inbox';
			
				//load header
				$this->load->view('customer_account_pages/header', $data);
				
				//load main body
				$this->load->view('customer_account_pages/messages_page', $data);
				
				//load main footer
				$this->load->view('customer_account_pages/footer');				
								
			}else{
				$url = 'login?redirectURL='.urlencode(current_url());
				redirect($url);
				//redirect('home/login');
			}	
		}		
		
		/**
		* Function to display
		* sent messages
		*/				
		public function sent(){
			
			if($this->session->userdata('logged_in')){
				
				$email_address = $this->session->userdata('email_address');

				//get users details
				$data['users'] = $this->Customers->get_customer($email_address);
				

				//set payments count
				$count_payments = $this->Orders->count_orders($email_address, 'Paid');
				if($count_payments == '' || $count_payments == null){
					$count_payments = 0;
				}
				
				$data['payments_count'] = $count_payments;
				
				//display pending payments count 
				$pending_payments_count = $this->Orders->count_orders($email_address, 'Pending');
				if($pending_payments_count == '' || $pending_payments_count == null){
					$pending_payments_count = 0;
				}
				$data['pending_payments_count'] = $pending_payments_count;
				
				//set cart count
				$data['cart_count'] = 0;
					 
				//get watchlist count
				$watchlist_count = $this->Watchlist->count_watchlist($email_address);
				
				if($watchlist_count == '' || $watchlist_count == null){
					$watchlist_count = 0;
				}
				$data['watchlist_count'] = $watchlist_count;
				
				//get shipping status count
				$shipping_count = $this->Shipping_status->count_shipping($email_address);
				
				if($shipping_count == '' || $shipping_count == null){
					$shipping_count = 0;
				}
				$data['shipping_count'] = $shipping_count;
				
				//get unread messages
				$data['header_messages_array'] = $this->Messages->get_header_messages();
				
				//set saved searches count
				$data['saved_searches_count'] = 0;
					
				$messages_unread = $this->Messages->count_unread_messages($email_address);
				
				if($messages_unread == '' || $messages_unread == null){
					$messages_unread = 0;
				}
				
				$data['messages_unread'] = $messages_unread;
				

				$data['count_sent'] = $this->Messages->count_sent_messages($email_address);	

				$config = array();
							
				if($this->input->get('search') != ''){
						
						// get search string
						$search = html_escape($this->input->get('search'));
						$search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;
						$count = $this->Messages->count_search_sent($search, $email_address);
						if($count == '' || $count == null){
							$count = 0;
						}
						
						$data['count'] = $count;
						
						
						$data['display_option'] = 'Showing Results for "<strong><em>'.$search.'</em></strong>" <a href="'.base_url('message/inbox').'"  >Show All</a>';
						$config["base_url"] = base_url()."message/sent/$search";	
					
						$config["total_rows"] = $count;
						$config["per_page"] = 10;
						$config["uri_segment"] = 3;
						$choice = $config["total_rows"] / $config["per_page"];
						$config["num_links"] = round($choice);
					
						$this->pagination->initialize($config);
						
						if($this->uri->segment(3) > 0)
							$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
						else
							$offset = $this->uri->segment(3);					
						
						$data['messages_array'] = $this->Messages->get_search_sent($email_address, $search, $config["per_page"], $offset);	
						
				}else{	
				
					$data['display_option'] = '<strong>Showing All</strong>';
					
					$count = $this->Messages->count_sent_messages($email_address);
					if($count == ''){
						$count = 0;
					}
					$config["base_url"] = base_url("message/sent");	
					
					$config["total_rows"] = $count;
					$config["per_page"] = 10;
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = round($choice);
		
					$this->pagination->initialize($config);
						
					if($this->uri->segment(3) > 0)
						$offset = ($this->uri->segment(3) + 0)*$config['per_page'] - $config['per_page'];
					else
						$offset = $this->uri->segment(3);					
							
					$data['sent_messages'] = $this->Messages->get_sent_messages($email_address, $config["per_page"], $offset);	
					
					$data['count_sent_messages'] = $count;
				
				}
				
				$data['pagination'] = $this->pagination->create_links();
				
				//assign additional breadcrumb
				$data['breadcrumb'] = '<li><a href="javascript:void(0)" onclick="location.href=\''.base_url('message/inbox/').'\'" title="Private Inbox" ><i class="fa fa-inbox"></i> Private Inbox</a></li>';
				
				//load header and page title
				//assign page title name
				$data['page_breadcrumb'] = '<i class="fa fa-paper-plane"></i> Sent Messages';
				
				$data['pageTitle'] = 'Sent Messages';
				
				//assign page ID
				$data['pageID'] = 'sent';
							
				$this->load->view('customer_account_pages/header', $data);
							
				//load main body
				$this->load->view('customer_account_pages/sent_messages_page', $data);
				
				//load main footer
				$this->load->view('customer_account_pages/footer');				
					
			}else{
				$url = 'login?redirectURL='.urlencode(current_url());
				redirect($url);	
				//redirect('home/login');
			}	
		}	


		/**
		* Function to handle display
		* message preview and
		* reply message
		*/	
		public function detail(){
			
			$email_address = $this->session->userdata('email_address');

			$detail = $this->db->select('*')->from('messages')->where('id',$this->input->post('id'))->get()->row();
			
			$id = $this->input->post('id');

			if($detail){

				//$this->db->where('message_id',$this->input->post('id'))->update('messages',array('opened'=>'0'));
				
				$this->mark_as_read($id);
				
				$data['id'] = $detail->id;
				$data['name'] = $detail->sender_name;
				$data['email'] = $detail->sender_email;
				$data['subject'] = $detail->message_subject;
				$data['message'] = $detail->message_details;
				$data['date_sent'] = date("F j, Y", strtotime($detail->date_sent)); 
				
				//$data['update_count_message'] = $this->db->where('opened','0')->count_all_results('message');
				$count = $this->Messages->count_unread_messages($detail->receiver_email);
				//$data['count_unread'] = "'".$count."'";
				$data['count_unread'] = $count;
				$data['success'] = true;
				
				//handle reply requests
					$data['receiver_name'] = $detail->sender_name;
					$data['receiver_email'] = $detail->sender_email;
					$data['sender_name'] = $detail->receiver_name;
					$data['sender_email'] = $detail->receiver_email;
					$data['message_subject'] = 'Re: '.$detail->message_subject;
					
					//handle default reply box content
					$Sname = $detail->receiver_name;
					$Rname = $detail->sender_name;
					
					//message content default display
					$message_content = '';
					/*$message_content .= '<br/>';
					$message_content .= '<br/>';
					$message_content .= '-----------------------------------------------------------------------------------------------<br/>';
					$message_content .= 'From: '.$Rname.' <'.$detail->sender_username.'><br/>';
					$message_content .= 'To: '.$Sname.' <'.$detail->receiver_username.'><br/>';
					$message_content .= 'Sent: '.date("F j, Y, g:i a", strtotime($detail->date_sent)) .'<br/>';
					$message_content .= 'Subject: '.$detail->message_subject.'<br/>';
					$message_content .= '<br/><br/>';
					$message_content .= $detail->message_details;
					$message_content .= '<br/><br/>';
					$message_content .= '-----------------------------------------------------------------------------------------------';
					
					
					$breaks = array("<br />","<br>","<br/>");  
					$message_content = str_ireplace($breaks, "\r\n", $message_content); 
					*/				
					
					$data['message_details'] = $message_content;
					$data['replying_to'] = 'Replying to: '.$Rname;
					$data['message_id'] = $detail->id;
					$data['headerTitle'] = $detail->message_subject;			

			} else {
				$data['success'] = false;
			}
			echo json_encode($data);
					
		}
	
		/**
		* Function to handle display
		* message preview and
		* reply message
		*/	
		public function new_message_detail(){
			
			$sender_email = '';
			$sender_username = '';
			if($this->session->userdata('admin_username')){
				$username = $this->session->userdata('admin_username');
				$admin = $this->Admin->get_user($username);
				foreach($admin as $user){
					$sender_name = $user->admin_name;
					$sender_username = $user->admin_username;
				}
			}

			
			$detail = $this->db->select('*')->from('users')->where('username',$this->input->post('email_address'))->get()->row();
			
			$admin_detail = $this->db->select('*')->from('admin_users')->where('admin_username',$this->input->post('username'))->get()->row();
			
			$username = $this->input->post('username');

			if($detail){
				//$this->db->where('message_id',$this->input->post('id'))->update('messages',array('opened'=>'0'));
				//handle reply requests
				$data['id'] = $detail->id;
					$data['receiver_name'] = $detail->first_name .' '.$detail->last_name;
					$data['receiver_username'] = $detail->username;
					$data['sender_name'] = $sender_name;
					$data['sender_username'] = $sender_username;
					
					$data['model'] = 'users';
					$data['success'] = true;
					
					//handle default reply box content
					$data['to'] = '<strong>To: '.$detail->first_name .' '.$detail->last_name.' ('.$detail->username.')</strong>';
					$data['headerTitle'] = 'Send Message to: '.$detail->first_name .' '.$detail->last_name;			

			}
			else if($admin_detail){
					$data['id'] = $admin_detail->id;
					$data['receiver_name'] = $admin_detail->admin_name;
					$data['receiver_username'] = $admin_detail->admin_username;
					$data['sender_name'] = $sender_name;
					$data['sender_username'] = $sender_username;
					
					$data['model'] = 'admin_users';
					$data['success'] = true;
					
					//handle default reply box content
					$data['to'] = '<strong>To: '.$admin_detail->admin_name .' ('.$admin_detail->admin_username.')</strong>';
					$data['headerTitle'] = 'Send Message to: '.$admin_detail->admin_name;			
					
			}else {
				$data['success'] = false;
			}
			echo json_encode($data);
					
		}
			
	
		/**
		* Function to mark messages
		* as read 
		*/	
		public function mark_as_read($message_id){
				
			$data = array(
				'opened' => '1',
			);
			$this->db->where('id', $message_id);
			$query = $this->db->update('messages', $data);
				
		}

		
		/**
		* Function to delete
		* multiple messages
		*/			
		public function multi_delete() {
			
			if(!$this->session->userdata('logged_in')){
				
				//$this->login();
				redirect('login','refresh');
				
			}else{
				
				if($this->input->post('deleteBtn') != ''){
						
						$data = array(
							'recipient_delete' => '1',
						);
						
						$checked_messages = $this->input->post('cb');
						$this->db->where_in('id', $checked_messages);
						$query = $this->db->update('messages', $data);	
						
						$count = count($checked_messages);
						if($count == 1){
							$this->session->set_flashdata('message_deleted', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".commentDiv").fadeOut("slow"); }, 5000);</script><div class="commentDiv text-center"><i class="fa fa-check-circle"></i>'.$count.' message has been deleted!</div>');
						}else{
							$this->session->set_flashdata('message_deleted', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".commentDiv").fadeOut("slow"); }, 5000);</script><div class="commentDiv text-center"><i class="fa fa-check-circle"></i>'.$count.' messages have been deleted!</div>');
						}
												
						redirect('account/messages/','refresh');
				}
				if($this->input->post('deleteSnt') != ''){
						
						$data = array(
							'sender_delete' => '1',
						);
						
						$checked_messages = $this->input->post('cb');
						$this->db->where_in('id', $checked_messages);
						$query = $this->db->update('messages', $data);	
						
						$count = count($checked_messages);
						if($count == 1){
							$this->session->set_flashdata('message_deleted', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".commentDiv").fadeOut("slow"); }, 5000);</script><div class="commentDiv text-center"><i class="fa fa-check-circle"></i>'.$count.' message has been deleted!</div>');
						}else{
							$this->session->set_flashdata('message_deleted', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".commentDiv").fadeOut("slow"); }, 5000);</script><div class="commentDiv text-center"><i class="fa fa-check-circle"></i>'.$count.' messages have been deleted!</div>');
						}
						
						redirect('message/sent_messages/','refresh');
				}				
				if($this->input->post('writeBtn') != ''){
						
						redirect('message/new_message/','refresh');
				}	
				
			}
        }		
		
	

		/**
		* Function to validate replied
		* message
		*/	
		public function send_message_validation(){
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
            
			$this->form_validation->set_rules('message_subject','Subject','required|trim|xss_clean');
			$this->form_validation->set_rules('message_details','Message','required|trim|xss_clean');
		
			$this->form_validation->set_message('required', 'Please enter a %s!');
			
			$email_address = $this->session->userdata('email_address');
			
			$id = $this->input->post('message_id');
			$thisRandNum = md5(uniqid());
			
			if ($this->form_validation->run()){

				if($this->prevent_double_post($email_address)){
					
				//	echo img('assets/images/round_error.png').'You must wait at least 20 seconds before you send another message!';
					//echo "<script language=\"javascript\">alert('You must wait at least 20 seconds before you send another message!')</script>";
					$this->session->set_flashdata('message_error', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-exclamation-circle"></i> You must wait at least 20 seconds before you send another message!</div>');
					
					$data['success'] = false;
					$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> You must wait at least 20 seconds before you send another message!</div>';

					//$url = 'message/reply/'.$id.'/'.$thisRandNum;		
					//$this->messages();
					//redirect($url);
					
				}else{			
						$data = array(
						
							'sender_name' => $this->input->post('sender_name'),
							'sender_email' => $this->input->post('sender_email'),
							'receiver_name' => $this->input->post('receiver_name'),
							'receiver_email' => $this->input->post('receiver_email'),
							'message_subject' => $this->input->post('message_subject'),
							'message_details' => $this->input->post('message_details'),
							'opened' => '0',
							'recipient_delete' => '0',
							'sender_delete' => '0',
							'date_sent' => date('Y-m-d H:i:s'),
						);
					
					if($this->Messages->reply_message($data)){
						
						$this->session->set_flashdata('message_sent', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Message has been sent!</div>');
						
						$receiver_email = $this->input->post('receiver_email');
						
						$data['new_count_message'] = $this->Messages->count_unread_messages($receiver_email);
						$data['success'] = true;
						$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Message sent!</div>';
						
						//notify recipient of new message
						$to = $email_address;
						$subject = 'You have received a new message';
						$message = "You have received a new message. ";
						$message .= 'Please <a href="javascript:void(0)" onclick="location.href=\''.base_url('account/messages/').'\'" title="Login to your Auto9ja account">login</a> in read your new message.';	
					
						$this->Messages->send_email_alert($to, $subject, $first_name, $message);

						//if($this->session->userdata('logged_in')){
						//	redirect('message/inbox');
						//}
						//if($this->session->userdata('hands_logged_in')){
					//		redirect('message/private_inbox');
						//}
						//redirect('message/message_sent');
							
					}else{
						$this->session->set_flashdata('message_error', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box-error").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box-error text-center"><i class="fa fa-exclamation-circle"></i> Message not sent!</div>');
					
						$data['success'] = false;
						$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Message not sent!</div>';

						//$url = 'message/reply/'.$id.'/'.$thisRandNum;		
						//$this->messages();
						//redirect($url);
						//redirect('message/send_message');
					}
				}
			}else {
					$this->session->set_flashdata('message_error', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box-error").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box-error text-center"><i class="fa fa-exclamation-circle"></i>' . validation_errors() . '</div>');
					
					$data['success'] = false;
					$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> ' . validation_errors() . '</div>';

					//$url = 'reply/'.$id.'/'.$thisRandNum;		
					//$this->reply($id,$thisRandNum);
					//redirect($url);
					//redirect('message/send_message');
			}
			echo json_encode($data);
		}

		
		/**
		* Function to prevent user from posting
		* same message within a few seconds
		*/			
		public function prevent_double_post($email){

			$date = date("Y-m-d H:i:s",time());
			$date = strtotime($date);
			$min_date = strtotime("-20 second", $date);
			
			$max_date = date('Y-m-d H:i:s', time());
			$min_date = date('Y-m-d H:i:s', $min_date);
			
			$this->db->select('*');
			$this->db->from('messages');
			$this->db->where('sender_email', $email);
			
			$this->db->where("date_sent BETWEEN '$min_date' AND '$max_date'", NULL, FALSE);

			$query = $this->db->get();
			
			if ($query->num_rows() >= 1){	
				return true;
			}else {
				return false;
			}	
								
		}		
		

		
		/**
		* Function to validate
		* message to support
		*/	
		public function support_message_validation(){
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ', '</div>');
            
			$this->form_validation->set_rules('message_subject','Subject','required|trim|xss_clean|min_length[3]');
			$this->form_validation->set_rules('message_details','Message','required|trim|xss_clean|min_length[6]');
		
			$this->form_validation->set_message('required', 'Please enter a %s!');
			$this->form_validation->set_message('min_length', '%s is too short!');
			
			$email_address = $this->session->userdata('email_address');
			
			if ($this->form_validation->run()){

				if($this->prevent_double_post($email_address)){
					
					//$this->session->set_flashdata('message_error', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-exclamation-circle"></i> You must wait at least 20 seconds before you send another message!</div>');
					
					$data['success'] = false;
					$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> You must wait at least 20 seconds before you send another message!</div>';
					
					//$this->messages();
					//redirect('account/messages');
				}else{	
						$receiver_name = 'Customer Support';
						$receiver_email = 'customer.care';
						
					$data = array(
						
						'sender_name' => $this->input->post('sender_name'),
						'sender_email' => $email_address,
						'receiver_name' => $receiver_name,
						'receiver_email' => $receiver_email,
						'message_subject' => $this->input->post('message_subject'),
						'message_details' => $this->input->post('message_details'),
						'opened' => '0',
						'recipient_delete' => '0',
						'sender_delete' => '0',
						'date_sent' => date('Y-m-d H:i:s'),
					);
					
					if($this->Messages->new_message($data)){
						
						//$this->session->set_flashdata('message_sent', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Message has been sent!</div>');
						
						$data['success'] = true;
						$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Message sent!</div>';
						
						//redirect('account/messages');
						//redirect('message/message_sent');
							
					}else{
							//$this->session->set_flashdata('message_error', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box-error").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box-error text-center"><i class="fa fa-exclamation-circle"></i> Message not sent!</div>');
					
							$data['success'] = false;
							$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Message not sent!</div>';
					}
				}
			}else {
					//$this->session->set_flashdata('message_error', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box-error").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box-error text-center"><i class="fa fa-exclamation-circle"></i>' . validation_errors() . '</div>');
					
					$data['success'] = false;
					$data['notif'] = '<div class="alert text-center"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> ' . validation_errors() . '</div>';
				//$this->new_message();
				//redirect('message/send_message');
			}
			echo json_encode($data);
		}



}