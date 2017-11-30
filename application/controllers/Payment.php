<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Payment extends CI_Controller {
 
		/**
		 * Class constructor.
		 * Adding libraries for each call.
		 */
		public function __construct() {
			
			parent::__construct();
			
			$this->merchant->load('paypal_express');

							
		}	
		
		/**
		* Function for controller
		*  index
		*/	
		public function index(){
			
			if($this->session->userdata('logged_in')){
				redirect('account/billing');
			}else{
				redirect('login');	
			}
		}
	

		/**
		* Function for cc deposit 
		* 
		*/			
		public function deposit($id, $rndm){
			
			if($this->session->userdata('logged_in')){
				
				$email_address = $this->session->userdata('email_address');

				$data['users'] = $this->Customers->get_customer($email_address);
				
				$object = new Card_payment_methods_model();
				$object->load($id);
				
				//get unread messages
				$data['header_messages_array'] = $this->Messages->get_header_messages();
				
				$messages_unread = $this->Messages->count_unread_messages($email_address);
				
				if($messages_unread == '' || $messages_unread == null){
					$messages_unread = 0;
				}
				
				$data['messages_unread'] = $messages_unread;
				
				//set saved searches count
				$data['saved_searches_count'] = 0;
				
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
				
				$data['credit_card_details'] = $this->Card_payment_methods->get_card_info($id,$email_address);	
				
				//assign page title name
				$data['pageTitle'] = 'Deposit';
				
				//assign page ID
				$data['pageID'] = 'deposit';
									
				//load header
				$this->load->view('customer_account_pages/header', $data);
								
				//load main body
				$this->load->view('customer_account_pages/deposit_page', array(
					'object' => $object
				));
				
				//load main footer
				$this->load->view('customer_account_pages/footer');				
									
			}else{
				$url = 'login?redirectURL='.urlencode(current_url());
				redirect($url);
			}	
		}		
	


		/**
		* Function for paypal deposit 
		* 
		*/			
		public function paypal_deposit($id, $rndm){
			
			if($this->session->userdata('logged_in')){
				
				$object = new Paypal_methods_model();
				$object->load($id);
				
				$email_address = $this->session->userdata('email_address');

				$data['users'] = $this->Customers->get_customer($email_address);
				
				//get unread messages
				$data['header_messages_array'] = $this->Messages->get_header_messages();
				
				$messages_unread = $this->Messages->count_unread_messages($email_address);
				
				if($messages_unread == '' || $messages_unread == null){
					$messages_unread = 0;
				}
				
				$data['messages_unread'] = $messages_unread;
				
				//set saved searches count
				$data['saved_searches_count'] = 0;
				
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
				
					
				$data['paypal_details'] = $this->Payment_methods->get_paypal_info($id,$username);			
						
				//assign page title name
				$data['pageTitle'] = 'PayPal Deposit';
				
				//assign page ID
				$data['pageID'] = 'paypal_deposit';
									
				//load header
				$this->load->view('customer_account_pages/header', $data);
								
				//load main body
				$this->load->view('customer_account_pages/paypal_deposit_page', array(
					'object' => $object
				));
				
				//load main footer
				$this->load->view('customer_account_pages/footer');				
									
			}else{
				$url = 'login?redirectURL='.urlencode(current_url());
				redirect($url);
			}	
		}		
	
	
		/**
		* Function to process payment 
		* validation
		*/		
		public function paypal_process(){
			
			if($this->session->userdata('logged_in')){
				
				//get user paypal account id
				$id = $this->input->post('id');
				
				//get post amount
				$amount = $this->input->post('amount');
				
				//remove non-numbers from post
				$amount = preg_replace("/[^\d-.]+/","", $amount);
				
				if($amount >= 5){
					
					$data = array(
						//'maskedPaypal' => $this->Paypal_accounts->email_mask($object->PayPal_email),
						'business_email' => 'paypal@auto9ja.com',
					);		
					$this->session->set_userdata($data);
					
					$email_address = $this->session->userdata('email_address');
					
					//Set variables for paypal form
					$paypalURL = 'https://www.sandbox.paypal.com/us/cgi-bin/webscr'; //test PayPal api url
					$paypalID = 'paypal@auto9ja.com'; //business email
					$returnURL = base_url().'paypal/success'; //payment success url
					//$returnURL = 'https://nyfozlznjs.localtunnel.me/websites/auto9ja/paypal/success';
					$cancelURL = base_url().'paypal/cancel'; //payment cancel url
					//$cancelURL = 'https://nyfozlznjs.localtunnel.me/websites/auto9ja/paypal/cancel';
					$notifyURL = base_url().'paypal/ipn'; //ipn url
					//$notifyURL = 'https://nyfozlznjs.localtunnel.me/websites/auto9ja/paypal/ipn';
					$userID = 1;
					$logo = base_url('assets/images/logo/logo2.png');
					
					$this->paypal_lib->add_field('cmd', '_xclick');
					$this->paypal_lib->add_field('business', $paypalID);
					$this->paypal_lib->add_field('return', $returnURL);
					$this->paypal_lib->add_field('cancel_return', $cancelURL);
					$this->paypal_lib->add_field('notify_url', $notifyURL);
					$this->paypal_lib->add_field('item_name', 'Deposit');
					$this->paypal_lib->add_field('custom', $email_address);
					//$this->paypal_lib->add_field('item_number',  $id);
					$this->paypal_lib->add_field('display',  '1');
					$this->paypal_lib->add_field('paymentaction',  'sale');
					$this->paypal_lib->add_field('no_note',  '1');
					$this->paypal_lib->add_field('no_shipping',  '1');
					$this->paypal_lib->add_field('rm',  '2');
					$this->paypal_lib->add_field('lc',  'GB');
					$this->paypal_lib->add_field('cbt',  'Return to Auto9ja');
					$this->paypal_lib->add_field('amount',  $amount);        
					$this->paypal_lib->image($logo);
						
					$this->paypal_lib->paypal_auto_form();
					
				}else{
					$this->session->set_flashdata('paypal_error', '<div class="alert alert-danger text-danger text-center"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <i class="fa fa-exclamation-triangle"></i> Please enter a deposit amount no less than $5!</div>');
					redirect('account/billing');
				}
				//$data['success'] = true;
				//echo json_encode($data);
			
			}else{
				//$url = 'login?redirectURL='.urlencode(current_url());
				//redirect($url);
				redirect('login');
			}				
		}	
		


		/**
		* Function to handle view
		* billing details
		* 
		*/	
		public function billing_details(){
			
			if($this->session->userdata('logged_in') ){
					
				$paypal_detail = $this->db->select('*')->from('paypal_accounts')->where('user_email',$this->input->post('email'))->get()->row();
				
				//bank list dropdown
				$bank_details = '<select name="select-bank-withdrawal" id="bank-withdrawal">';
				
				$this->db->from('bank_accounts');
				$this->db->where('user_email', $this->input->post('email'));
				$this->db->order_by('id');
				$result = $this->db->get();
				if($result->num_rows() > 0) {
					foreach($result->result_array() as $row){
						$masked_account_number = $this->Bank_accounts->mask_number($row['account_number']);
						$card_details .= '<option value="'.$row['id'].'">'.$masked_account_number.'</option>';			
					}
					$data['success'] = true;
				}
				$bank_details .= '</select>';
				$data['bank_details'] = $bank_details;
				
				if($paypal_detail){
						
					$masked_PayPal = $this->Paypal_accounts->email_mask($paypal_detail->PayPal_email);
						$data['id'] = $paypal_detail->id;
						$data['masked_PayPal'] = $masked_PayPal;
						$data['success'] = true;
					
				}
				if($result->num_rows() < 0 || !$paypal_detail){
					$data['success'] = false;
				}
				
				echo json_encode($data);
				
			}else{
				redirect('login/');
			}
			
		}

		/**
		* Function to handle view
		* card details
		* 
		*/	
		public function bank_details(){
			
			if($this->session->userdata('logged_in')){
				
				$detail = $this->db->select('*')->from('bank_accounts')->where('id',$this->input->post('id'))->get()->row();
				
				$id = $this->input->post('id');

				if($detail){

						$data['id'] = $detail->id;
						$data['bank_name'] = $detail->bank_name;			
						$data['bank_location'] = $detail->bank_location;
						$data['account_name'] = $detail->account_name;
						$data['account_number'] = $detail->account_number;
						$masked_account_number = $this->Bank_accounts->mask_number($detail->account_number);
						$data['masked_account_number'] = $masked_account_number;
						
						$data['user_email'] = $detail->user_email;

						$data['date_added'] = date("F j, Y", strtotime($detail->date_added));
						
						$data['model'] = 'bank_accounts';
						$data['success'] = true;
					
				}else {
					$data['success'] = false;
				}
				
				echo json_encode($data);
				
			}else{
				redirect('login/');
			}
			
		}


		/**
		* Function to handle view
		* paypal details
		* 
		*/	
		public function paypal_details(){
			
			if($this->session->userdata('logged_in') ){
					
				$detail = $this->db->select('*')->from('paypal_accounts')->where('id',$this->input->post('id'))->get()->row();
				
				$id = $this->input->post('id');

				if($detail){

						$data['id'] = $detail->id;		
						$data['PayPal_email'] = $detail->PayPal_email;
						$masked_PayPal = $this->Paypal_accounts->email_mask($detail->PayPal_email);
						$data['masked_PayPal'] = $masked_PayPal;
						$data['user_email'] = $detail->user_email;
						$data['date_added'] = date("F j, Y", strtotime($detail->date_added));
						
						$data['model'] = 'paypal_accounts';
						$data['success'] = true;
					
				}else {
					$data['success'] = false;
				}
				
				echo json_encode($data);
				
			}else{
				redirect('login/');
			}
			
		}


		/**
		* Function to add paypal 
		* validation
		*/		
		public function add_paypal(){
			
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> ', '</div>');
            
            $this->form_validation->set_rules('paypal_email','PayPal email','required|trim|xss_clean|valid_email|is_unique[paypal_accounts.PayPal_email]|callback_accounts_limit');
			
			$this->form_validation->set_message('required', '%s cannot be blank!');
			$this->form_validation->set_message('valid_email', 'Please enter a valid email!');
            $this->form_validation->set_message('is_unique', 'This PayPal account has already been registered');
			 
			if ($this->form_validation->run()){
					
					$email_address = $this->session->userdata('email_address');
					
					//array of post value from add paypal form
					$d = array(
						'PayPal_email' => $this->input->post('paypal_email'),
						'user_email' => $email_address,
						'date_added' => date('Y-m-d H:i:s'),
					);				
					
					if ($this->Paypal_accounts->add_paypal($d)){
						
						//update activities table
						$description = 'added paypal';
					
						$activity = array(			
							'user_email' => $email_address,
							'description' => $description,
							'keyword' => 'Paypal',
							'activity_time' => date('Y-m-d H:i:s'),
						);
							
						$this->Site_activities->insert_activity($activity);				
						
						$user = $this->Customers->get_customer($email_address);
					
						$first_name = '';
						
						foreach($user as $u){
							$first_name = $u->first_name;
							
						}
								
						//send email alert
						$to = $email_address;
						$subject = 'Added a new PayPal account';
						$message = 'You have successfully added a new PayPal account.'. "\n";
						$message .= "If you did not make this change. Please contact us immediately. ";
						
						$this->Messages->send_email_alert($to, $subject, $first_name, $message);

						$this->session->set_flashdata('paypal_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> PayPal account has been added!</div>');
						$data['success'] = true;
						$data['notif'] = 'PayPal account has been added!';
						
						//redirects to payment methods page
						//redirect('account/payment_methods', 'refresh');	
						
					}else {
							$data['success'] = false;
							$data['notif'] = 'PayPal account could not be added!';
				
							//redirects to payment methods page
							//redirect('account/payment_methods', 'refresh');
					}
			}else{
				$data['success'] = false;
				//$data['paypal_email'] = $this->input->post('paypal_email'); 
				$data['notif'] = validation_errors();
				
				//$this->payment_methods();
			}
			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			// echo json_encode($data);			
		}


		/**
		* Function to validate answer to 
		* memorable questions
		*/			
		public function accounts_limit(){
			
			$email_address = $this->session->userdata('email_address');
			
			if ($this->Paypal_accounts->account_limit($email_address)){
				
				return TRUE;
			}
			else {
				$this->form_validation->set_message('accounts_limit', 'You can\'t have more than one PayPal account.');
				
				return FALSE;
			}			
		}
				
						
		/**
		* Function to validate update admin 
		* form
		*/			
		public function update_paypal(){
				
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> ', '</div>');
           
			$this->form_validation->set_rules('paypal_email','PayPal','required|trim|xss_clean|valid_email|is_unique[paypal_accounts.PayPal_email]');
			
			$this->form_validation->set_message('required', '%s cannot be blank!');
			$this->form_validation->set_message('valid_email', 'Please enter a valid email!');
				
			if ($this->form_validation->run()){
				
				$email_address = $this->session->userdata('email_address');		

				$update = array(
					
					'PayPal_email' => $this->input->post('paypal_email'),
					'user_email' => $email_address,
					'date_added' => date('Y-m-d H:i:s'),
				);
				
				if ($this->Paypal_accounts->update_paypal($update)){	
					
					//update activities table
					$description = 'updated paypal';
					
					$activity = array(			
						'user_email' => $email_address,
						'description' => $description,
						'keyword' => 'Paypal',
						'activity_time' => date('Y-m-d H:i:s'),
					);
							
					$this->Site_activities->insert_activity($activity);				
						
					$user = $user = $this->Customers->get_customer($email_address);
							
					$first_name = '';
					
					foreach($user as $u){
						$first_name = $u->first_name;
						
					}
								
					//send email alert
					$to = $email_address;
					$subject = 'Updated PayPal account';
					$message = 'You have successfully updated your PayPal account.'. "\n";
					$message .= "If you did not make this change. Please contact us immediately. ";
					
					$this->Messages->send_email_alert($to, $subject, $first_name, $message);
							
					$this->session->set_flashdata('paypal_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".commentDiv").fadeOut("slow"); }, 5000);</script><div class="commentDiv text-center"><i class="fa fa-check-circle"></i> Your PayPal account has been updated!</div>');
					
					$data['success'] = true;
					$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Your PayPal account has been updated!</div>';
					//redirect('account/payment_methods');
				}
				
			}else {
				$data['success'] = false;
				$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-exclamation-triangle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> There are errors on the form!'.validation_errors().'</div>';
				$this->session->set_flashdata('paypal_updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".commentDivError").fadeOut("slow"); }, 5000);</script><div class="commentDivError text-center">'.validation_errors().'</div>');
					
				//redirect('account/payment_methods');
			}
			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			//echo json_encode($data);			
		}			

	
		
		/**
		* Function to add bank account
		* validation
		*/		
		public function add_bank_account(){
			
            $this->load->library('form_validation');
            
			//$this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');
            
			$this->form_validation->set_rules('bank_name','Bank Name','required|trim|xss_clean|min_length[5]');
            $this->form_validation->set_rules('bank_location','Bank Location','required|trim|xss_clean|min_length[5]');
			$this->form_validation->set_rules('account_name','Account Name','required|trim|xss_clean');
			$this->form_validation->set_rules('account_number','Account Number','required|trim|xss_clean|numeric|min_length[6]|is_unique[bank_accounts.account_number]');
			$this->form_validation->set_rules('sort_code','Sort Code','required|trim|xss_clean');
			$this->form_validation->set_rules('swift_bic','SWIFT/BIC','required|trim|xss_clean');
			
			$this->form_validation->set_message('required', '%s cannot be blank!');
			$this->form_validation->set_message('min_length', '%s must be longer!');
            $this->form_validation->set_message('exact_length', 'Please check the length of %s !');
            $this->form_validation->set_message('is_unique', 'Bank account already exists!');
            
			if ($this->form_validation->run()){
					
					$email_address = $this->session->userdata('email_address');
					
					//array of post value from add bank details form
					$bank_data = array(
						'bank_name' => $this->input->post('bank_name'),
						'bank_location' => $this->input->post('bank_location'),
						'account_name' => $this->input->post('account_name'),
						'account_number' => $this->input->post('account_number'),
						'sort_code' => $this->input->post('sort_code'),
						'swift_bic' => $this->input->post('swift_bic'),
						'user_email' => $email_address,
						'date_added' => date('Y-m-d H:i:s'),
					);
						
					if ($this->Bank_accounts->add_bank_details($bank_data)){
						
						//update activities table
						$description = 'added bank account';
						
						$activity = array(			
							'user_email' => $email_address,
							'description' => $description,
							'keyword' => 'Bank',
							'activity_time' => date('Y-m-d H:i:s'),
						);
								
						$this->Site_activities->insert_activity($activity);				
							
						
						$user = $this->Customers->get_customer($email_address);
							
						$first_name = '';
							
						foreach($user as $u){
							$first_name = $u->first_name;
						}
								
						//send email alert
						$to = $email_address;
						$subject = 'Added a new bank account';
						$message = 'You have successfully added a new bank account.'. "\n";
						$message .= "If you did not make this change. Please contact us immediately. ";
						
						$this->Messages->send_email_alert($to, $subject, $first_name, $message);

						$this->session->set_flashdata('bank_account_added', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> The bank account has been added!</div>');
						$data['success'] = true;
						$data['notif'] = '<div class="alert alert-success text-center" role="alert"><i class="fa fa-check-circle"></i> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> The bank account has been added!</div>';
							
						//redirects to payment methods page
						//redirect('account/payment_methods', 'refresh');	
							
					}else {
							$data['success'] = false;
							$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-exclamation-triangle"></i> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> The bank account could not be added!</div>';
					
							//redirects to payment methods page
							//redirect('account/payment_methods', 'refresh');
					}
					
			}else{
				$data['success'] = false;
				$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.validation_errors().'</div>';
				
				//$this->payment_methods();
			}
				// Encode the data into JSON
				$this->output->set_content_type('application/json');
				$data = json_encode($data);

				// Send the data back to the client
				$this->output->set_output($data);			
		}


		
		/**
		* Function to validate update bank 
		* form
		*/			
		public function update_bank(){
				
			$this->load->library('form_validation');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger text-danger text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> ', '</div>');
			
            $this->form_validation->set_rules('bank_name','Bank Name','required|trim|xss_clean|min_length[5]');
            $this->form_validation->set_rules('bank_location','Bank Location','required|trim|xss_clean|min_length[5]');
			$this->form_validation->set_rules('account_name','Account Name','required|trim|xss_clean');
			$this->form_validation->set_rules('account_number','Account Number','required|trim|xss_clean|numeric|min_length[6]|is_unique[bank_accounts.account_number]');
			$this->form_validation->set_rules('sort_code','Sort Code','required|trim|xss_clean');
			$this->form_validation->set_rules('swift_bic','SWIFT/BIC','required|trim|xss_clean');
			
			$this->form_validation->set_message('required', 'Please enter a %s!');
				
			if ($this->form_validation->run()){
				
				$email_address = $this->session->userdata('email_address');		

				//array of post value from add bank details form
				$bank_data = array(
					'bank_name' => $this->input->post('bank_name'),
					'bank_location' => $this->input->post('bank_location'),
					'account_name' => $this->input->post('account_name'),
					'account_number' => $this->input->post('account_number'),
					'sort_code' => $this->input->post('sort_code'),
					'swift_bic' => $this->input->post('swift_bic'),
					'user_email' => $email_address,
					'date_added' => date('Y-m-d H:i:s'),
				);
				
				if ($this->Bank_accounts->update_bank_details($bank_data)){	
						
					//update activities table
					$description = 'updated bank account';
						
					$activity = array(			
						'user_email' => $email_address,
						'description' => $description,
						'keyword' => 'Bank',
						'activity_time' => date('Y-m-d H:i:s'),
					);
					
					$this->Site_activities->insert_activity($activity);

					$user = $this->Customers->get_customer($email_address);
							
					$first_name = '';
					
					foreach($user as $u){
						$first_name = $u->first_name;
					}
								
					//send email alert
					$to = $email_address;
					$subject = 'Updated bank account';
					$message = 'You have successfully updated your bank account.'. "\n";
					$message .= "If you did not make this change. Please contact us immediately. ";
					
					$this->Messages->send_email_alert($to, $subject, $first_name, $message);
				
					$this->session->set_flashdata('updated', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> Your bank account has been updated!</div>');
					
					$data['success'] = true;
					$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Your bank account has been updated!</div>';
				}
				
			}else {
				$data['success'] = false;
				$data['notif'] = '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.validation_errors().'</div>';
			}
			// Encode the data into JSON
			$this->output->set_content_type('application/json');
			$data = json_encode($data);

			// Send the data back to the client
			$this->output->set_output($data);
			//echo json_encode($data);			
		}			

		/**
         * cancel Function
         * @param int $issue_id
         */
        public function remove() {
			
			if($this->session->userdata('logged_in') && ($this->input->post('id') != '' && $this->input->post('model') != '')){
				
				$email_address = $this->session->userdata('email_address');
				
				$id = $this->input->post('id');	
				$model = $this->input->post('model');
				
				//$this->load->model(array('Issue'));
				$new_model = ucfirst($model.'_model');
				//$issue = new Issue();	
				$object = new $new_model();
				//$issue->load($issue_id);
				$object->load($id);
				$object->delete();
				
				//update activities table
				$description = 'removed billing method';
						
				$activity = array(			
					'user_email' => $email_address,
					'description' => $description,
					'keyword' => 'Removed',
					'activity_time' => date('Y-m-d H:i:s'),
				);
					
				$this->Site_activities->insert_activity($activity);
				
				$user = $this->Customers->get_customer($email_address);
							
				$first_name = '';
				
				foreach($user as $u){
					$first_name = $u->first_name;	
				}
								
				//send email alert
				$to = $email_address;
				$subject = 'Removed billing method';
				$message = 'You have successfully removed a billing method.'. "\n";
				$message .= "If you did not make this change. Please contact us immediately. ";	
					
				$this->Messages->send_email_alert($to, $subject, $first_name, $message);

				$this->session->set_flashdata('removed', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".custom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i> The account has been removed!</div>');					
				$data['success'] = true;
				$data['notif'] = '<div class="alert alert-success text-center" role="alert"> <i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> The account has been removed!</div>';

				// Encode the data into JSON
				$this->output->set_content_type('application/json');
				$data = json_encode($data);

				// Send the data back to the client
				$this->output->set_output($data);
				//echo json_encode($data);				
														
			}else{
			
				redirect('login/');
			}	
		}	

	
	
	}