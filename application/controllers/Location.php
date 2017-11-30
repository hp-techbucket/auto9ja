<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Location extends CI_Controller {
 
		/**
		 * Class constructor.
		 * Adding libraries for each call.
		 */
		public function __construct() {
			parent::__construct();

		}	
		
		/**
		* Function for controller
		*  index
		*/	
		public function index(){
			
			if($this->session->userdata('logged_in')){
				if(isset($_GET['type']) || !empty($_GET['type'])) {
					$type = $_GET['type'];
					if($type=='getCountries') {
						$data = $this->Countries->get_countries();
					} 
					if($type=='getStates') {
						 $countryId = $_GET['countryId'];
						 $data = $this->Countries->get_states($countryId);
					}
					if($type=='getCities') {
						 $stateId = $_GET['stateId'];
						 $data = $this->Countries->get_cities($stateId);
					}
				}
				echo json_encode($data);
				
			}else{
				redirect('home/login');	
			}
		}
	

		/**
		* Function to get states 
		* 
		*/			
		public function get_states(){
			
			if($this->session->userdata('logged_in') || $this->session->userdata('hands_logged_in')){
				
				$email_address = $this->session->userdata('email_address');
								
				
				//$detail = $this->db->select('*')->from('card_payment_methods')->where('id',$this->input->post('id'))->get()->row();
				
				///$id = $this->input->post('id');

				if(!empty($this->input->post('id')) || $this->input->post('id') != ''){
					
					$id = $this->input->post('id');
					
					$options = '';
					$options .= '<select name="card_billing_state" id="states">';
					$options .= '<option value="0" selected="selected">Select State</option>';
						
					$this->db->from('states');
					$this->db->where('country_id', $id);
					$result = $this->db->get();
					if($result->num_rows() > 0) {
						foreach($result->result_array() as $row){
							$options .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';			
						}
					}
					$options .= '</select>';
					
					$data['options'] = $options;
						
					$data['success'] = true;
					
				}else {
					$data['success'] = false;
				}
				
				echo json_encode($data);
								
			}else{
				redirect('home/');
			}	
		}		
	

		/**
		* Function to get cities 
		* 
		*/			
		public function get_cities(){
			
			if($this->session->userdata('logged_in') || $this->session->userdata('hands_logged_in')){
				
				$email_address = $this->session->userdata('email_address');

				if(!empty($this->input->post('id')) || $this->input->post('id') != ''){
					
					$id = $this->input->post('id');
					
					$options = '';
					$options .= '<select name="card_billing_city" id="cities">';
					$options .= '<option value="0" selected="selected">Select City</option>';
						
					$this->db->from('cities');
					$this->db->where('state_id', $id);
					$result = $this->db->get();
					if($result->num_rows() > 0) {
						foreach($result->result_array() as $row){
							$options .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';			
						}
					}
					$options .= '</select>';
					
					$data['options'] = $options;
						
					$data['success'] = true;
					
				}else {
					$data['success'] = false;
				}
				
				echo json_encode($data);
								
			}else{
				redirect('home/');
			}	
		}		
		


	
	
	}