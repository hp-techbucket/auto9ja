<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paypal extends CI_Controller 
{
     function  __construct(){
        parent::__construct();
        $this->load->library('paypal_lib');
        
     }
 
	/**
	* Function to display paypal success 
	*  
	*/	 
	public function success2(){
		if(isset($_GET['tx']) && ($_GET['tx'])!=null && ($_GET['tx'])!= "") {
			$tx = $_GET['tx'];
			$this->verifyWithPayPal($tx);
		}
		else {
			$this->session->set_flashdata('error', 'No TX ID');
			redirect('paypal/failure');
		}
	}
	
    public function success(){
		 
		if($this->session->userdata('logged_in')){ 
		  				
			$email_address = $this->session->userdata('email_address');
			$users = $this->Customers->get_customer($email_address);
			
			$first_name = '';
			foreach($users as $user){
				$first_name = $user->first_name;
			}	
			
			//get the transaction data
			//$paypalInfo = $this->input->get();
			if(isset($_GET['tx'])){
				
				$new_deposit = '';
				$transactionID = '';
				$receiver_email = '';
				$payer_email = '';
				$payment_status = '';
				$payment_currency = '';
				
				
				$tx = $_GET['tx'];
				$pdt = 'YwFyn2OkH0_gaEXn_6PewXP5bO4tYYpssu4cDtmgPmCohKnpmTZKJMm-4L4';
				
				$payment_data = $this->process_pdt($tx, $pdt);
				
				//$payment_data = $this->process_pdt($tx, $pdt);
				$this->session->set_flashdata('payment_data', $payment_data);
				// parse the data
				//$lines = explode("\n", $payment_data);
				//$keyarray = array();				
				
				if ($payment_data) {
					
					///	for ($i=1; $i<count($lines);$i++){
						//	list($key,$val) = explode("=", $lines[$i]);
						//	$keyarray[urldecode($key)] = urldecode($val);
					//	}
						// check the payment_status is Completed
						// check that txn_id has not been previously processed
						// check that receiver_email is your Primary PayPal email
						// check that payment_amount/payment_currency are correct
						// process payment
						//$myarray = array_shift($payment_data);
		
						$firstname = $payment_data['first_name'];
						$lastname = $payment_data['last_name'];
						$itemname = $payment_data['item_name'];
						//$amount = $payment_data['mc_gross'];
						$new_deposit = $payment_data["payment_gross"];
						$transactionID = $payment_data["txn_id"];
						$receiver_email = $payment_data["receiver_email"];
						$payer_email = $payment_data["payer_email"];
						$payment_status = $payment_data["payment_status"];
						$payment_currency = $payment_data["mc_currency"];
					
					
					
					//$mypaypalemail = $this->session->userdata('business_email');
					$mypaypalemail = 'paypal@auto9ja.com';
							
					if (($payment_status == 'Completed') && ($receiver_email == $mypaypalemail) && ($payment_currency == 'USD') && ($this->Transactions->is_unique_ref($transactionID))) 
					{
						// do your stuff here... if nothing else you must check that $payment_status=='Completed'
						//$new_deposit = $this->input->post('amount');
						//remove non-numbers from post
						$amount = preg_replace("/[^\d-.]+/","", $new_deposit);
								
						//$maskedPaypal = $this->session->userdata('maskedPaypal');
						$maskedPaypal = $this->Paypal_accounts->email_mask($payer_email);
								
						
						$deposit_date = date('Y-m-d H:i:s');

						//array of post value from add credit card form
						$deposit_data = array(
							'payment_type' => 'PayPal',
							'payment_info' => $maskedPaypal,
							'deposit_amount' => $amount,
							'user_email' => $email_address,
							'deposit_date' => $deposit_date,
						);
							

						$trans = array(
							'reference' => $transactionID,
							'amount' => '+ $'.number_format($amount, 2),
							'transaction' => $maskedPaypal,
							'note' => 'Deposit',
							'user_email' => $email_address,
							'date' => $deposit_date,
						);
						
						//	echo json_encode($result);
						if ($this->Deposits->paypal_deposit($amount, $deposit_data, $trans)){
											
							//instant notification div
							$notification = ' <p>Hello '.$first_name.',</p>';
							$notification .= '<p>You have successfully added to $'.$amount.' your account!</p>';
									
							$this->session->set_flashdata('deposit_message', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".ccustom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i>'.$notification.'</div>');
											
							//update activities table
							$description = 'paypal deposit';
					
							$activity = array(			
								'user_email' => $email_address,
								'description' => $description,
								'keyword' => 'Deposit',
								'activity_time' => date('Y-m-d H:i:s'),
							);
							
							$this->Site_activities->insert_activity($activity);
											
							//send email alert
							$to = $email_address;
							$subject = "Auto9ja Transaction alert [PayPal deposit: $ ".$amount."]";
							$message = "You have successfully made a deposit of $ ".$amount." via PayPal.";	
					
							$this->Messages->send_email_alert($to, $subject, $first_name, $message);

							//End email notification

							$this->session->set_flashdata('tx', $tx);
							$this->session->set_flashdata('pdt', $pdt);
							//$r_url = 'paypal/complete/'.$tx.'/'.$pdt;
							redirect('paypal/complete');	
						}else{
						
							$this->session->set_flashdata('error', 'Not saving data');
							//failed for some reason
							redirect('paypal/failure');
						}	
						
					}else{
						
						$this->session->set_flashdata('error', 'Payment data validation error');
						//failed for some reason
						redirect('paypal/failure');
					}
				}else{
					$this->session->set_flashdata('error', 'Payment data function error');
					$this->session->set_flashdata('tx', $_GET['tx']);
					redirect('paypal/failure');
				}		
			}else{
				$this->session->set_flashdata('error', 'TX:'.$_GET['tx']);
				redirect('paypal/failure');
			}		  						
		}else{
			$url = 'login?redirectURL='.urlencode(current_url());
			redirect($url);
		}		
     }

	
	/**
	 * Processes a PDT transaction id.
	 *
	 * @author     Torleif Berger
	 * @link       http://www.geekality.net/?p=1210
	 * @license    http://creativecommons.org/licenses/by/3.0/
	 * @return     The payment data if $tx was valid; otherwise FALSE.
	 */
	public function process_pdt($tx, $pdt)
	{
			// Init cURL
			$request = curl_init();

			// Set request options
			curl_setopt_array($request, array
			(
					CURLOPT_URL => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
					CURLOPT_POST => TRUE,
					CURLOPT_POSTFIELDS => http_build_query(array
					(
							'cmd' => '_notify-synch',
							'tx' => $tx,
							'at' => $pdt,
					)),
					CURLOPT_RETURNTRANSFER => TRUE,
					CURLOPT_HEADER => FALSE,
					//CURLOPT_SSL_VERIFYPEER => TRUE,
					//CURLOPT_CAINFO => 'cacert.pem',
			));

			// Execute request and get response and status code
			$response = curl_exec($request);
			$status   = curl_getinfo($request, CURLINFO_HTTP_CODE);

			// Close connection
			curl_close($request);
			
			$lines = explode("\n", $response);

			$keyarray = array();
			
			// Validate response
			if($status == 200 AND strpos($response, 'SUCCESS') === 0)
			{
				$i=0;
				for ($i=1; $i<count($lines);$i++){

					//list($key,$val) = explode("=", $lines[$i]); array_pad(explode("=", $lines[$i]),2,null);
					list($key,$val) = array_pad(explode("=", $lines[$i]),2,null);

					$keyarray[urldecode($key)] = urldecode($val);

				}
				return $keyarray;
					// Remove SUCCESS part (7 characters long)
					/*$response = substr($response, 7);

					// Urldecode it
					$response = urldecode($response);

					// Turn it into associative array
					preg_match_all('/^([^=\r\n]++)=(.*+)/m', $response, $m, PREG_PATTERN_ORDER);
					$response = array_combine($m[1], $m[2]);

					// Fix character encoding if needed
					if(isset($response['charset']) AND strtoupper($response['charset']) !== 'UTF-8')
					{
							foreach($response as $key => &$value)
							{
									$value = mb_convert_encoding($value, 'UTF-8', $response['charset']);
							}

							$response['charset_original'] = $response['charset'];
							$response['charset'] = 'UTF-8';
					}

					// Sort on keys
					ksort($response);

					// Done!
					return $response;
					*/
			}

			return FALSE;
	}	
	

	
 	/**
	* Function to display paypal  
	* cancel or failure
	*/	    
    public function complete(){
			
		if($this->session->userdata('logged_in')){
				
			$email_address = $this->session->userdata('email_address');
			
			$tx = $this->session->flashdata('tx');
			$pdt = $this->session->flashdata('pdt');
			
			$data['payment_data'] = $this->process_pdt($tx, $pdt);
				
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
					
			$data['users'] = $this->Customers->get_customer($email_address);
				
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

			//assign additional breadcrumb
			$data['breadcrumb'] = '';
				
			$data['page_breadcrumb'] = '<i class="fa fa-check-circle-o"></i> Deposit Success';
				
			//assign page title name
			$data['pageTitle'] = 'Deposit Success';
									
			//assign page ID
			$data['pageID'] = 'deposit_success';
														
			//load header
			$this->load->view('customer_account_pages/header', $data);
													
			//load main body
			$this->load->view('customer_account_pages/deposit_success_page', $data); 				
			//load main footer
			$this->load->view('customer_account_pages/footer');				
												
		}else{
			$url = 'login?redirectURL='.urlencode(current_url());
			redirect($url);
		}	
    }
	
 	/**
	* Function to display paypal  
	* cancel
	*/	    
    public function cancel(){
			
		if($this->session->userdata('logged_in')){
				
			$email_address = $this->session->userdata('email_address');
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
					
			$data['users'] = $this->Customers->get_customer($email_address);
				
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
			
			//assign additional breadcrumb
			$data['breadcrumb'] = '';
				
			$data['page_breadcrumb'] = '<i class="fa fa-ban"></i> Deposit Cancelled';
					
			//assign page title name
			$data['pageTitle'] = 'Deposit Cancelled';
				
			//assign page ID
			$data['pageID'] = 'deposit_cancelled';
									
			//load header
			$this->load->view('customer_account_pages/header', $data);
								
			//load main body
			$this->load->view('customer_account_pages/deposit_cancelled_page');				
				
			//load main footer
			$this->load->view('customer_account_pages/footer');				
												
		}else{
			$url = 'login?redirectURL='.urlencode(current_url());
			redirect($url);
		}	
    }

	
 	/**
	* Function to display paypal  
	* failure
	*/	    
    public function failure(){
			
		if($this->session->userdata('logged_in')){
				
			$email_address = $this->session->userdata('email_address');
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
					
			$data['users'] = $this->Customers->get_customer($email_address);
				
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

			//assign additional breadcrumb
			$data['breadcrumb'] = '';
				
			$data['page_breadcrumb'] = '<i class="fa fa-exclamation-triangle"></i> Deposit Failure';	
				
			//assign page title name
			$data['pageTitle'] = 'Deposit Failure';
				
			//assign page ID
			$data['pageID'] = 'deposit_failure';
									
			//load header
			$this->load->view('customer_account_pages/header', $data);
								
			//load main body
			$this->load->view('customer_account_pages/deposit_failure_page');				
				
			//load main footer
			$this->load->view('customer_account_pages/footer');				
												
		}else{
			$url = 'login?redirectURL='.urlencode(current_url());
			redirect($url);
		}	
    }
    
    public function ipn(){
        //paypal return transaction details array
        $paypalInfo    = $this->input->post();

        //$data['user_id'] = $paypalInfo['custom'];
        $data['product_id']    = $_POST["item_number"];
        $data['txn_id']    = $_POST["txn_id"];
        $data['payment_gross'] = $_POST["payment_gross"];
        $data['currency_code'] = $_POST["mc_currency"];
        $data['payer_email'] = $_POST["payer_email"];
        $data['payment_status']    = $_POST["payment_status"];

        $paypalURL = $this->paypal_lib->paypal_url;        
        $result    = $this->paypal_lib->curlPost($paypalURL,$paypalInfo);
        
        //check whether the payment is verified
        if(eregi("VERIFIED",$result)){
			
			$new_deposit = $_POST["payment_amount"];
			$transactionID = $_POST["txn_id"];
			$receiver_email = $_POST["receiver_email"];
			$payer_email = $_POST["payer_email"];
			$payment_status = $_POST["payment_status"];
			$payment_currency = $_POST["mc_currency"];
			
			$mypaypalemail = $this->session->userdata('business_email');;
					
			if (($payment_status == 'Completed') && ($receiver_email == $mypaypalemail) && ($payment_currency == 'USD') && ($this->Transactions->is_unique_ref($transactionID))) 
			{
				// do your stuff here... if nothing else you must check that // do your stuff here... if nothing else you must check that $payment_status=='Completed'
				//$new_deposit = $this->input->post('amount');
				//remove non-numbers from post
				$amount = preg_replace("/[^\d-.]+/","", $new_deposit);
								
				//$maskedPaypal = $this->session->userdata('maskedPaypal');
				$maskedPaypal = $this->Paypal_accounts->email_mask($payer_email);
								
				$email_address = $this->session->userdata('email_address');
						
				$deposit_date = date('Y-m-d H:i:s');

				//array of post value from add credit card form
				$deposit_data = array(
					'payment_info' => $maskedPaypal,
					'deposit_amount' => $amount,
					'user_email' => $email_address,
					'deposit_date' => $deposit_date,
				);
							

				$trans = array(
					'reference' => $transactionID,
					'amount' => '+ $'.number_format($amount, 2),
					'transaction' => $maskedPaypal,
					'note' => 'Deposit',
					'user_email' => $email_address,
					'date' => $deposit_date,
				);
						
				//	echo json_encode($result);
				if ($this->Deposits->paypal_deposit($amount, $deposit_data, $trans)){
											
					//instant notification div
					$notification = ' <p>Hello '.$first_name.',</p>';
					$notification .= '<p>You have successfully added to $'.$amount.' your account!</p>';
									
					$this->session->set_flashdata('deposit_message', '<script type="text/javascript" language="javascript">setTimeout(function() { $(".ccustom-alert-box").fadeOut("slow"); }, 5000);</script><div class="custom-alert-box text-center"><i class="fa fa-check-circle"></i>'.$notification.'</div>');
											
								
					//send email
					$ci = get_instance();
					$ci->load->library('email');
											
					$config['protocol'] = "smtp";
					$config['validate'] = 'FALSE';
					$config['smtp_host'] = "ssl://cp-in-1.webhostbox.net";
					$config['smtp_port'] = "465";
					$config['smtp_user'] = "getextra@global-sub.com"; 
					$config['smtp_pass'] = "1234567";
					$config['charset'] = "utf-8";
					$config['mailtype'] = "html";
					$config['newline'] = "\r\n";

					$ci->email->initialize($config);

					//setup email function
					$ci->email->from('getextra@global-sub.com', 'Auto9ja');
					$ci->email->to($email_address);
					$this->email->reply_to('getextra@gmail.com', 'Auto9ja');
					$ci->email->subject('PayPal depost');
							
					//$url = 'https://vrqhxykxwa.localtunnel.me/websites/getextra/';
					//img('assets/images/get.png')
					//compose email message
					$message = "<div style='font-size: 1.0em; border: 1px solid #D0D0D0; border-radius: 3px; margin: 5px; padding: 10px; '>";
					$message .= '<div align="center" id="logo"><a href="'.base_url().'" title="Auto9ja"><img src="'.base_url('assets/images/logo/logo2.png').'" ></a></div><br/>';
										
					$message .= '<p>Hello ';
					$message .= $first_name. ',</p>';
					$message .= '<p>You have successfully made a deposit of $ '.$amount.' via PayPal.</p>';
					$message .= '</div>';
											
					$ci->email->message($message);
											
					$ci->email->send();
					//End email notification
								
					
					//message admin
					$ci->email->from('getextra@global-sub.com', 'Auto9ja');
					$ci->email->to('getextra@global-sub.com');
					$this->email->reply_to('getextra@gmail.com', 'Auto9ja');
					$ci->email->subject('Verified IPN');
					$ci->email->message($listener->getTextReport());
									
					$ci->email->send();
				}
			}			
			
            //insert the transaction data into the database
           // $this->product->insertTransaction($data);
        }
    }

	
	
	
}