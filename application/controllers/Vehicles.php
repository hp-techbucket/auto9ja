<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Vehicles extends CI_Controller {
 
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
			//set cart count
			$data['cart_count'] = 0;
				
			//check if cart session is set
			if($this->session->userdata('cart_array')){ 
				
				//count cart items
				$cart_count = count($this->session->userdata('cart_array'));
				if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
				}
				$data['cart_count'] = $cart_count;
				
				//get cart items
				$data['cart_array'] = $this->session->userdata('cart_array');
			}
			
			$data['vehicles_array'] = $this->Vehicles->get_all_vehicles();
			$data['search'] = '';
				
			//GET COUNT OF PRODUCTS
			$data['count'] = '';
				
			//assign meta tags
			$page = 'vehicles';
			$keywords = '';
			$description = '';
			$metadata_array = $this->Page_metadata->get_page_metadata($page);
			if($metadata_array){
				foreach($metadata_array as $meta){
					$keywords = $meta->keywords;
					$description = $meta->description;
				}
			}
			if($description == '' || $description == null){
				$description = 'Auto9ja - one stop shop for new and used vehicles';
			}
			//assign meta_description
			$data['meta_description'] = $description;
			
			//assign meta_author
			$data['meta_author'] = 'Auto9ja';
			
			//assign meta_keywords		
			$data['meta_keywords'] = $keywords;

			//assign page title name
			$data['pageTitle'] = 'Vehicles';
				
			//assign page ID
			$data['pageID'] = 'vehicles';
			
			$this->load->view('pages/header', $data);
			
			$this->load->view('pages/vehicle_listings_page', $data);
				
			$this->load->view('pages/footer', $data);
		}
					
						
		/**
		 * Function for Vehicles Display Page.
		 *
		 */
		public function vehicles_display()
		{
			//set cart count
			$data['cart_count'] = 0;
				
			//check if cart session is set
			if($this->session->userdata('cart_array')){ 
				
				//count cart items
				$cart_count = count($this->session->userdata('cart_array'));
				if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
				}
				$data['cart_count'] = $cart_count;
				
				//get cart items
				$data['cart_array'] = $this->session->userdata('cart_array');
			}
			
			$data['vehicles_array'] = $this->Vehicles->get_all_vehicles();
			$data['search'] = '';
				
			//GET COUNT OF PRODUCTS
			$data['count'] = '';
				
			//assign meta tags
			$page = 'vehicles';
			$keywords = '';
			$description = '';
			$metadata_array = $this->Page_metadata->get_page_metadata($page);
			if($metadata_array){
				foreach($metadata_array as $meta){
					$keywords = $meta->keywords;
					$description = $meta->description;
				}
			}
			if($description == '' || $description == null){
				$description = 'Auto9ja - one stop shop for new and used vehicles';
			}
			//assign meta_description
			$data['meta_description'] = $description;
			
			//assign meta_author
			$data['meta_author'] = 'Auto9ja';
			
			//assign meta_keywords		
			$data['meta_keywords'] = $keywords;

			//assign page title name
			$data['pageTitle'] = 'Vehicles';
				
			//assign page ID
			$data['pageID'] = 'vehicles';
			
			$this->load->view('pages/header', $data);
			
			$this->load->view('pages/vehicle_listings_page', $data);
				
			$this->load->view('pages/footer', $data);
		}
			
		
		/***
		* Function to handle vehicles ajax
		* Datatables
		***/
		public function vehicles_datatable()
		{
			$list = $this->Vehicles->get_datatables();
			$data = array();
			$no = $_POST['start'];
			$last_login  = '';
			foreach ($list as $vehicle) {
				$no++;
				$row = array();
				
				$thumbnail = '';
				$filename = FCPATH.'uploads/vehicles/'.$vehicle->id.'/'.$vehicle->vehicle_image;

				$url = 'account/vehicle_details';
				
				if($vehicle->vehicle_image == '' || $vehicle->vehicle_image == null || !file_exists($filename)){
					
					$result = $this->db->select('*, MIN(id) as min_id', false)->from('vehicle_images')->where('vehicle_id', $vehicle->id)->get()->row();
				
					if(!empty($result)){
						
						//THUMBNAIL
						$thumbnail = '<img src="'.base_url().'uploads/vehicles/'.$result->vehicle_id.'/'.$result->image_name.'" class="img-responsive img-rounded" width="80" height="80" />';
						
					}else{
						$thumbnail = '<img src="'.base_url().'assets/images/img/no-default-thumbnail.png" class="img-responsive img-rounded" width="80" height="80" />';
					}
					
				}
				else{
					//THUMBNAIL
					$thumbnail = '<img src="'.base_url().'uploads/vehicles/'.$vehicle->id.'/'.$vehicle->vehicle_image.'" class="img-responsive img-rounded" width="80" height="80" />';
				}	
				
				
				$row[] = $thumbnail;
				
				//$row[] = $vehicle->vehicle_type;
				
				//$row[] = $vehicle->year_of_manufacture;
				
				//$row[] = $vehicle->vehicle_make;
				
				//$row[] = $vehicle->vehicle_model;
				//$row[] = $vehicle->id;
				
				$row[] = $vehicle->vehicle_location_city.', '.$vehicle->vehicle_location_country;
				//$row[] = $vehicle->vehicle_odometer;
				$row[] = '$'.number_format($vehicle->vehicle_price, 0);
				
				//$row[] = $vehicle->id;
				//$row[] = $vehicle->id;
				
				
				//prepare buttons
				$row[] = '<a data-toggle="modal" data-target="#EnquireModal" class="waves-effect waves-light  btn" title="Enquire" >Enquire <i class="material-icons right">send</i></a>';
				
				$data[] = $row;
			}
	 
			$output = array(
				
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->Vehicles->count_all(),
				"recordsFiltered" => $this->Vehicles->count_filtered(),
				"data" => $data,
			);
			//output to json format
			echo json_encode($output);
		}

		
					
		/**
		* Function to view item
		*
		*/	
        public function view_vehicle($title='', $id='', $unique='') {
			
			//$projects = new Projects_model();
			//escaping the post values
			$pid = html_escape($id);
			$title = html_escape($title);
			$title = trim($title);
			
			$id = preg_replace('#[^0-9]#i', '', $pid); // filter everything but numbers
			$id = preg_replace('#[^0-9]#i', '', $pid); // filter everything but numbers

			$detail = $this->db->select('*')->from('vehicles')->where('id',$id)->get()->row();
			
			if($detail){

				$data['id'] = $detail->id;
					
				$data['headerTitle'] = $detail->vehicle_make.' '.$detail->vehicle_model;	
					
				$image = '';
				$thumbnail = '';
				$filename = FCPATH.'uploads/vehicles/'.$detail->id.'/'.$detail->vehicle_image;
					
				if($detail->vehicle_image == '' || $detail->vehicle_image == null || !file_exists($filename)){
					
					$result = $this->db->select('*, MIN(id) as min_id', false)->from('vehicle_images')->where('vehicle_id', $detail->id)->get()->row();
						
					if(!empty($result)){
								
						//MAIN IMAGE
						$image = '<a id="single_image" href="'.base_url().'uploads/vehicles/'.$detail->id.'/'.$detail->vehicle_image.'" title=" View '.$detail->vehicle_make.' '.$detail->vehicle_model.'"><div class="wrapper-img"><img alt="" src="'.base_url().'uploads/vehicles/'.$detail->id.'/'.$result->image_name.'" class="img-responsive main-img" id="main-img" width="" height=""/><div class="img-icon"><i class="fa fa-search-plus fa-lg" aria-hidden="true"></i></div></div></a>';
								
						//THUMBNAIL
						$thumbnail = '<img src="'.base_url().'uploads/vehicles/'.$detail->id.'/'.$result->image_name.'" class="" />';
								
					}else{
						//MAIN IMAGE
						$image = '<a id="single_image" href="'.base_url().'uploads/vehicles/'.$detail->id.'/'.$detail->vehicle_image.'" title=" View '.$detail->vehicle_make.' '.$detail->vehicle_model.'"><div class="wrapper-img"><img alt="" src="'.base_url().'assets/images/img/no-default-thumbnail.png" class="img-responsive main-img" id="main-img" width="" height=""/><div class="img-icon"><i class="fa fa-search-plus fa-lg" aria-hidden="true"></i></div></div></a>';
							
						$thumbnail = '<img src="'.base_url().'assets/images/img/no-default-thumbnail.png" />';
					}
							
				}
				else{
					//MAIN IMAGE
					$image = '<a id="single_image" href="'.base_url().'uploads/vehicles/'.$detail->id.'/'.$detail->vehicle_image.'" title=" View '.$detail->vehicle_make.' '.$detail->vehicle_model.'"><div class="wrapper-img"><img alt="" src="'.base_url().'uploads/vehicles/'.$detail->id.'/'.$detail->vehicle_image.'" class="img-responsive main-img" id="main-img" width="" height=""/><div class="img-icon"><i class="fa fa-search-plus fa-lg" aria-hidden="true"></i></div></div></a>';
							
					//THUMBNAIL
					$thumbnail = '<img src="'.base_url().'uploads/vehicles/'.$detail->id.'/'.$detail->vehicle_image.'" class="" />';
							
				}	
				
				$data['image'] = $image;
					
				$data['thumbnail'] = $thumbnail;
					
					
				$vehicle_images = $this->Vehicles->get_vehicle_images($detail->id);
					
				//count and display the number of images stored
				$images_count = $this->Vehicles->count_vehicle_images($detail->id);
					
				if($images_count == '' || $images_count == null){
					$images_count = 0;
				}
				$data['images_count'] = $images_count;
					
				//start vehicle gallery view
				//$gallery = '<div class="p_gallery">';
				//$gallery = '';
				$image_gallery = '<div class="gallery-wrapper">';
					
					if(!empty($vehicle_images)){
						//item count initialised
						$i = 0;
						$a = 1;
						foreach($vehicle_images as $image){
							
							//vehicle gallery view
							//$gallery .= '<a href="'.base_url().'uploads/vehicles/'.$detail->id.'/'.$image->image_name.'" title="View" >';
							//$gallery .= '<div class="wrapper"><img src="'.base_url().'uploads/vehicles/'.$detail->id.'/'.$image->image_name.'" id="'.$image->image_name.'" class="img-responsive" onclick="changeImage(\''.base_url().'uploads/vehicles/'.$detail->id.'/'.$image->image_name.'\')"/><div class="img-icon"><i class="fa fa-search-plus" aria-hidden="true"></i></div></div>';
							//$gallery .= '</a>';
							
							$image_gallery .= '<div class="img-wrapper">';
							$image_gallery .= '<a href="'.base_url().'uploads/vehicles/'.$detail->id.'/'.$image->image_name.'" title="View" data-fancybox="gallery"><div class="wrapper"><img src="'.base_url().'uploads/vehicles/'.$detail->id.'/'.$image->image_name.'" id="'.$image->image_name.'" class="img-responsive" onclick="changeImage(\''.base_url().'uploads/vehicles/'.$detail->id.'/'.$image->image_name.'\')" /><div class="img-icon"><i class="fa fa-search-plus" aria-hidden="true"></i></div></div></a>';
							$image_gallery .= '</div>';
							
							//$gallery .= '<span href="#" class="gallery-img" title="'.$detail->vehicle_make.' '.$detail->vehicle_model.' '.$a.'" onclick="changeImage(\''.base_url().'uploads/vehicles/'.$detail->id.'/'.$image->image_name.'\')"><img src="'.base_url().'uploads/vehicles/'.$detail->id.'/'.$image->image_name.'" id="'.$image->id.'" class="img-responsive"/></span>';
							
							$a++;	
						}
					}
					
					$image_gallery .= '</div>';
					$data['image_gallery'] = $image_gallery;
					
					
					//end portfolio gallery view
					//$gallery .= '</div>';
					//$data['vehicle_gallery'] = $gallery;
					
					$data['vehicle_title'] = $detail->vehicle_make.' '.$detail->vehicle_model;
					$data['vehicle_type'] = $detail->vehicle_type;
					$data['vehicle_make'] = $detail->vehicle_make;
					$data['vehicle_model'] = $detail->vehicle_model;
					$data['year_of_manufacture'] = $detail->year_of_manufacture;
					$data['vehicle_odometer'] = $detail->vehicle_odometer;
					$data['vehicle_lot_number'] = $detail->vehicle_lot_number;
					$data['vehicle_vin'] = $detail->vehicle_vin;
					$data['vehicle_colour'] = $detail->vehicle_colour;
					
					$background_image = base_url().'assets/images/img/'.strtolower($detail->vehicle_colour).'.png?'.time();
					
					$data['colour'] = '<div class="color-box" title="'.ucwords($detail->vehicle_colour).'" id="'.strtolower($detail->vehicle_colour).'" style="background-color:'.strtolower($detail->vehicle_colour).'; background-image: url('.$background_image.'); background-position: center; background-repeat:no-repeat; background-size:cover;"></div>'; 
					
					$data['vehicle_price'] = $detail->vehicle_price;
					$data['price'] = '$'.number_format($detail->vehicle_price, 2);
					$data['vehicle_location_city'] = $detail->vehicle_location_city;
					$data['vehicle_location_country'] = $detail->vehicle_location_country;
					$data['vehicle_description'] = $detail->vehicle_description;
					$data['sale_status'] = $detail->sale_status;
					$data['trader_email'] = $detail->trader_email;
					
					$user_array = $this->Users->get_user($detail->trader_email);
					
					$fullname = '';
					$company_name = '';
					$user_id = '';
					if($user_array){
						foreach($user_array as $user){
							$user_id = $user->id;
							$fullname = $user->first_name.' '.$user->last_name;
							$company_name = $user->company_name;
						}
					}
					$data['user_id'] = $user_id;
					$data['fullname'] = $fullname;
					$data['company_name'] = $company_name;
					
					$data['discount'] = $detail->discount;
					$data['price_after_discount'] = $detail->price_after_discount;
					
					//count reviews
					$count_reviews = $this->Reviews->count_user_reviews($detail->trader_email);
					if($count_reviews == '' || $count_reviews == null || $count_reviews < 1 ){
						$count_reviews = 0 .'reviews';
					}
					else if($count_reviews == 1){
						$count_reviews = '1 review';
					}else{
						$count_reviews = $count_reviews .' reviews';
					}
					//get product ratings
					$rating = $this->db->select_avg('rating')->from('reviews')->where('seller_email', $detail->trader_email)->get()->result();
					//$res = $this->db->select_avg('rating','overall')->where('product_id', $id)->get('reviews')->result_array();
					
					$data['rating'] = $rating[0]->rating;
					
					$rating_box = '';
					$new_rating = '<div class="starrr stars"></div> <span class="stars-count">0</span> star(s)<input type="hidden" name="rating" class="rating"/>';
					if($rating[0]->rating == '' || $rating[0]->rating == null || $rating[0]->rating < 1){
						$ratings = 0;
						$rating_box = '<div class="starrr stars-existing"  data-rating="'.round($rating[0]->rating).'"></div> <span class="">No reviews yet</span>';
						
					}else{
						$rating_box = '<div class="starrr stars-existing" data-rating="'.round($rating[0]->rating).'"></div> <span class="stars-count-existing">'.round($rating[0]->rating).'</span> star(s) (<span class="review-count">'.$count_reviews.'</span>)';
					}
					$data['rating_box'] = $rating_box;
					$data['new_rating'] = $new_rating;
										
					//assign meta tags
					$page = 'vehicles';
					$keywords = '';
					$description = '';
					$metadata_array = $this->Page_metadata->get_page_metadata($page);
					if($metadata_array){
						foreach($metadata_array as $meta){
							$keywords = $meta->keywords;
							$description = $meta->description;
						}
					}
					if($description == '' || $description == null){
						$description = 'Dejor Autos - one stop shop for new and used vehicles';
					}
					//assign meta_description
					$data['meta_description'] = $description;
					
					//assign meta_author
					$data['meta_author'] = 'Dejor Autos';
					
					//assign meta_keywords		
					$data['meta_keywords'] = $keywords;
					
					//assign page title name
					$data['pageTitle'] = ucwords($detail->vehicle_make.' '.$detail->vehicle_model);
					
					//assign page ID
					$data['pageID'] = 'view-vehicle';
					
					$this->load->view('pages/header', $data);
					
					$this->load->view('pages/view_vehicle_page', $data);
					
					$this->load->view('pages/footer');
					
			}else{
				redirect('vehicles');
			}
		}
	
	
		/**
		* Function for controller
		*  index
		*/	
		public function index2($type = ''){
			//set cart count
			$data['cart_count'] = 0;
				
			//check if cart session is set
			if($this->session->userdata('cart_array')){ 
				
				//count cart items
				$cart_count = count($this->session->userdata('cart_array'));
				if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
				}
				$data['cart_count'] = $cart_count;
				
				//get cart items
				$data['cart_array'] = $this->session->userdata('cart_array');
			}
			
			$config = array();
			
			if($this->input->get('search') != ''){
				
				
				// get search string
				$search = html_escape($this->input->get('search'));
				$search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;
				$count = $this->Vehicles->count_search_vehicles($search);
				if($count == '' || $count == null){
					$count = 0;
				}
				
				$data['count'] = $count;
							
				$data['display_option'] = 'Results for <strong><em>'.$search.'</em></strong>';
				$config["base_url"] = base_url("vehicles/listings/$search");			
				$config["total_rows"] = $count;
				$config["per_page"] = 6;
				$config["uri_segment"] = 4;
				$choice = $config["total_rows"] / $config["per_page"];
				$config["num_links"] = round($choice);
						
				$this->pagination->initialize($config);
							
				if($this->uri->segment(4) > 0)
					$offset = ($this->uri->segment(4) + 0)*$config['per_page'] - $config['per_page'];
				else
					$offset = $this->uri->segment(4);					
							
				$data['vehicles_array'] = $this->Vehicles->search_vehicles($search, $config["per_page"], $offset);	
				$data['pagination'] = $this->pagination->create_links();			
			}
			else if($this->input->post('show_vehicle_type') == 'All'){	
			
				$category = '<select name="show_vehicle_type" id="show_vehicle_type" class="form-control">';
				$category .= '<option value="All" selected="selected">All</option>';
						
				$this->db->from('vehicle_type');
				$this->db->order_by('id');
				$result = $this->db->get();
				if($result->num_rows() > 0) {
					foreach($result->result_array() as $row){
						$category .= '<option value="'.$row['name'].'">'.$row['name'].'</option>';
					}
				}
				$category .= '</select>';
						
				$data['category'] = $category;
						
				$data['vehicles'] = 'vehicles';
						
				$data['display_option'] = '<strong>Showing All</strong>';
							
				$table = 'vehicles';
				
				$count = $this->Vehicles->count_vehicles();
				if($count == '' || $count == null){
					$count = 0;
				}
				
				$config["base_url"] = base_url("vehicles/listings");	
				$config["total_rows"] = $count;
				$config["per_page"] = 6;
				$config["uri_segment"] = 4;
				$choice = $config["total_rows"] / $config["per_page"];
				$config["num_links"] = round($choice);
				$this->pagination->initialize($config);
										
				if($this->uri->segment(4) > 0)
					$offset = ($this->uri->segment(4) + 0)*$config['per_page'] - $config['per_page'];
				else
					$offset = $this->uri->segment(4);					
								
						//call the model function to get the messages data
					   // $data['notification_array'] = $this->bids->get_user_notifications($email_address, $config["per_page"], $data['page']);	
						//call the model function to get the posts data
				$data['vehicles_array'] = $this->Vehicles->get_vehicles($config["per_page"], $offset);	

				$data['count'] = $count;
				$data['pagination'] = $this->pagination->create_links();
			} 
			else if($this->input->post('show_vehicle_type') != ''){
							
				$vehicle_type = html_escape($this->input->post('show_vehicle_type'));
				$count_type = $this->Vehicle_type->count_vehicle_type(strtolower($vehicle_type));
				if($count_type == '' || $count_type == null){
					$count_type = 0;
				}
				$data['count'] = $count_type;
							
				$category = '<select name="show_vehicle_type" id="show_vehicle_type" class="form-control">';
				$category .= '<option value="All">All</option>';
							
				$this->db->from('vehicle_type');
				$this->db->order_by('id');
				$result = $this->db->get();
				if($result->num_rows() > 0) {
					foreach($result->result_array() as $row){
						$default = ($row['name'] == $vehicle_type)?'selected':'';
						$category .= '<option value="'.$row['name'].'" '.$default.'>'.$row['name'].'</option>';
					}
				}
				$category .= '</select>';
							
				$data['category'] = $category;
							
				$data['vehicles'] = $vehicle_type;
				
				$config["base_url"] = base_url("vehicles/listings/$vehicle_type");			
				$data['display_option'] = '<strong>'.$vehicle_type.'</strong>';
				$config["total_rows"] = $count_type;
				$config["per_page"] = 6;
				$config["uri_segment"] = 4;
				$choice = $config["total_rows"] / $config["per_page"];
				$config["num_links"] = round($choice);
						
				$this->pagination->initialize($config);
							
				if($this->uri->segment(4) > 0)
					$offset = ($this->uri->segment(4) + 0)*$config['per_page'] - $config['per_page'];
				else
					$offset = $this->uri->segment(4);					
							
				$data['vehicles_array'] = $this->Vehicles->get_vehicles_by_type($vehicle_type, $config["per_page"], $offset);	
				$data['pagination'] = $this->pagination->create_links();			
			}
			else{	
						
				$category = '<select name="show_vehicle_type" id="show_vehicle_type" class="form-control">';
				$category .= '<option value="All" selected="selected">All</option>';
						
				$this->db->from('vehicle_type');
				$this->db->order_by('id');
				$result = $this->db->get();
				if($result->num_rows() > 0) {
					foreach($result->result_array() as $row){
						$category .= '<option value="'.$row['name'].'">'.$row['name'].'</option>';
					}
				}
				$category .= '</select>';
				$data['category'] = $category;
						
				$data['vehicles'] = 'vehicles';
						
				$data['display_option'] = '<strong>Showing All</strong>';
							
				$count = $this->Vehicles->count_vehicles();
				if($count == '' || $count == null){
					$count = 0;
				}
				
				
				$config["base_url"] = base_url("vehicles/listings/");	
				$config["total_rows"] = $count;
				$config["per_page"] = 6;
				$config["uri_segment"] = 4;
				$choice = $config["total_rows"] / $config["per_page"];
				$config["num_links"] = round($choice);

				$this->pagination->initialize($config);
										
				if($this->uri->segment(4) > 0)
					$offset = ($this->uri->segment(4) + 0)*$config['per_page'] - $config['per_page'];
				else
					$offset = $this->uri->segment(4);					
								
				
				$data['vehicles_array'] = $this->Vehicles->get_vehicles($config["per_page"], $offset);	

				$data['count'] = $count;
				$data['pagination'] = $this->pagination->create_links();
			}	
			
							
			//Get all data from database
			//$data['products_array'] = $this->Products->get_products();

			//assign page title name
			$data['pageTitle'] = 'Vehicles';
			
			//assign page ID
			$data['pageID'] = 'vehicles';
					
			$this->load->view('pages/header', $data);
			
			$this->load->view('pages/vehicle_listings_page', $data);
			
			$this->load->view('pages/footer');
		}
	


		/**
		* Function to get types 
		* 
		*/			
		public function online_sales($type = ''){
			// get string
				$type = html_escape($this->input->get('search'));
				$type = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;
				$count = $this->Vehicles->count_vehicles_by_type($type);
				if($count == '' || $count == null){
					$count = 0;
				}
				
				$data['count'] = $count;
							
				$data['display_option'] = 'Results for <strong><em>'.$type.'</em></strong>';
				$config["base_url"] = base_url("vehicles/online_sales/$type");			
				$config["total_rows"] = $count;
				$config["per_page"] = 6;
				$config["uri_segment"] = 4;
				$choice = $config["total_rows"] / $config["per_page"];
				$config["num_links"] = round($choice);
						
				$this->pagination->initialize($config);
							
				if($this->uri->segment(4) > 0)
					$offset = ($this->uri->segment(4) + 0)*$config['per_page'] - $config['per_page'];
				else
					$offset = $this->uri->segment(4);					
							
				$data['vehicles_array'] = $this->Vehicles->get_vehicles_by_type($type, $config["per_page"], $offset);	
				$data['pagination'] = $this->pagination->create_links();
				
			//assign page title name
			$data['pageTitle'] = 'Vehicles';
			
			//assign page ID
			$data['pageID'] = 'listings';
					
			$this->load->view('pages/header', $data);
			
			$this->load->view('pages/online_sales_page', $data);
			
			$this->load->view('pages/footer');
		}		
		
		
		/**
		* Function for controller
		*  index
		*/	
		public function search(){
			
			if(isset($_GET['vehicle_type']) || !empty($_GET['vehicle_type']) || isset($_GET['keywords']) || !empty($_GET['keywords'])) {
			
				//set cart count
				$data['cart_count'] = 0;
					
				//check if cart session is set
				if($this->session->userdata('cart_array')){ 
					
					//count cart items
					$cart_count = count($this->session->userdata('cart_array'));
					if($cart_count == '' || $cart_count == null){
							$cart_count = 0;
					}
					$data['cart_count'] = $cart_count;
					
					//get cart items
					$data['cart_array'] = $this->session->userdata('cart_array');
				}
			
				$this->input->get(NULL, TRUE); // returns all GET items with XSS filter
				
				if(isset($_GET['vehicle_type']) && !empty($_GET['vehicle_type'])){
						
					$where = array();
					
					//escaping the post values
					$this->input->get(NULL, TRUE); // returns all GET items with XSS filter
					
					
					//escaping the get values
					$vehicle_type = trim($this->input->get('vehicle_type'));
					$vehicle_type = html_escape($vehicle_type);
					//$type = $_GET['vehicle_type'];
					if(!empty($vehicle_type)){
						$where['vehicle_type'] = $vehicle_type;
					}
					
					$vehicle_make = trim($this->input->get('vehicle_make'));
					$vehicle_make = html_escape($vehicle_make);
					if(!empty($vehicle_make)){
						$where['vehicle_make'] = $vehicle_make;
					}

					$vehicle_model = trim($this->input->get('vehicle_model'));
					$vehicle_model = html_escape($vehicle_model);
					if(!empty($vehicle_model)){
						$where['vehicle_model'] = $vehicle_model;
					}

					$from = trim($this->input->get('year_from'));
					$from = html_escape($from);
					$year_from = preg_replace('#[^0-9]#i', '', $from); // filter everything but numbers
					
					if(!empty($year_from)){
						//$where['year_of_manufacture'] >= $year_from;
					}

					$to = trim($this->input->get('year_to'));
					$to = html_escape($to);
					$year_to = preg_replace('#[^0-9]#i', '', $to); // filter everything but numbers
					
					if(!empty($year_to)){
						//$where['year_of_manufacture'] <= $year_to;
					}
					
					$start_date = date('Y', strtotime($year_from));
					$end_date =  date('Y', strtotime($year_to));
					$day = 86400; // Day in seconds  
					$format = 'Y'; // Output format (see PHP date funciton)  
					$sTime = strtotime($start_date); // Start as time  
					$eTime = strtotime($end_date); // End as time  
					$numDays = round(($eTime - $sTime) / $day) + 1;  
					$days = array();  
					for ($d = 0; $d < $numDays; $d++) {  
						$where['year_of_manufacture'] = date($format, ($sTime + ($d * $day)));  
					}	
					
					$d = array(
						'date1' => $year_from,
						'date2' => $year_to
					);	
						
					//$count = $this->Vehicles->count_filter_vehicles($vehicle_type, $vehicle_make,$vehicle_model,$year_from,$year_to);
					
					$count = $this->Vehicles->count_search($where);
					
					if($count == '' || $count == null){
						$count = 0;
					}
					
					$data['count'] = $count;
								
					$data['display_option'] = 'Results for <strong><em>'.$vehicle_type.'</em></strong>';
					$data['vehicles_array'] = $this->Vehicles->search($where);
					
				}else{
						
					$keywords = html_escape($this->input->get('keywords'));
					$keywords = trim($keywords);
					//$keywords = '';
					
					//$data['vehicles_array'] = $this->Vehicles->search_vehicles($keywords);
					//echo '<pre>'; print_r($data['vehicles_array']);die('</pre>');
					
					$count = '';
					if( strpos($keywords, ' ' ) !== false ) {
						$search = explode(' ', $keywords);
						$data['vehicles_array'] = $this->Vehicles->filter($search);
						$count = $this->Vehicles->count_filter($search);
						//echo '<pre>'; print_r($data['vehicles_array']);die('</pre>');
					}else{
						//$keywords = $search;
						$data['vehicles_array'] = $this->Vehicles->filter($keywords);
						$count = $this->Vehicles->count_filter($keywords);				
					}
					
					//*/
					
					$data['search'] = ucwords($keywords);
					
					//GET COUNT OF PRODUCTS
					if($count == '' || $count == null){
						$count = 0;
					}
					$data['count'] = $count;
				}
				
				//assign page title name
				$data['pageTitle'] = 'Vehicles';
				
				//assign page ID
				$data['pageID'] = 'listings';
						
				$this->load->view('pages/header', $data);
				
				$this->load->view('pages/vehicle_listings_page', $data);
				
				$this->load->view('pages/footer');
			
			}else{
				redirect('vehicle-finder');
			}
			
		}
				
		/**
		* Function for controller
		*  index
		*/	
		public function searchOLD(){
			
			if(isset($_GET['vehicle_type']) || !empty($_GET['vehicle_type'])) {
			
				//set cart count
			$data['cart_count'] = 0;
				
			//check if cart session is set
			if($this->session->userdata('cart_array')){ 
				
				//count cart items
				$cart_count = count($this->session->userdata('cart_array'));
				if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
				}
				$data['cart_count'] = $cart_count;
				
				//get cart items
				$data['cart_array'] = $this->session->userdata('cart_array');
			}
			
				$this->input->get(NULL, TRUE); // returns all GET items with XSS filter
				
				$where = array();
				
				//escaping the get values
				$vehicle_type = trim(strtolower($this->input->get('vehicle_type')));
				//$type = $_GET['vehicle_type'];
				if(!empty($vehicle_type)){
					$where['vehicle_type'] = $vehicle_type;
				}
				
				$vehicle_make = trim(strtolower($this->input->get('vehicle_make')));
				if(!empty($vehicle_make) && $vehicle_make != 'all'){
					$where['vehicle_make'] = $vehicle_make;
				}

				$vehicle_model = trim(strtolower($this->input->get('vehicle_model')));
				if(!empty($vehicle_model)){
					$where['vehicle_model'] = $vehicle_model;
				}

				$from = trim($this->input->get('year_from'));
				$year_from = preg_replace('#[^0-9]#i', '', $from); // filter everything but numbers
				
				if(!empty($year_from)){
					//$where['year_of_manufacture'] >= $year_from;
				}

				$to = trim($this->input->get('year_to'));
				$year_to = preg_replace('#[^0-9]#i', '', $to); // filter everything but numbers
				
				if(!empty($year_to)){
					//$where['year_of_manufacture'] <= $year_to;
				}
				
				$start_date = date('Y', strtotime($year_from));
				$end_date =  date('Y', strtotime($year_to));
				$day = 86400; // Day in seconds  
				$format = 'Y'; // Output format (see PHP date funciton)  
				$sTime = strtotime($start_date); // Start as time  
				$eTime = strtotime($end_date); // End as time  
				$numDays = round(($eTime - $sTime) / $day) + 1;  
				$days = array();  
				for ($d = 0; $d < $numDays; $d++) {  
					$where['year_of_manufacture'] = date($format, ($sTime + ($d * $day)));  
				}	
				
				$d = array(
					'date1' => $year_from,
					'date2' => $year_to
				);	
					
				//$count = $this->Vehicles->count_filter_vehicles($vehicle_type, $vehicle_make,$vehicle_model,$year_from,$year_to);
				
				$count = $this->Vehicles->count_filter($where);
				
				if($count == '' || $count == null){
					$count = 0;
				}
				
				$data['count'] = $count;
							
				$data['display_option'] = 'Results for <strong><em>'.$vehicle_type.'</em></strong>';
				$config["base_url"] = base_url("vehicles/search?vehicle_type=$vehicle_type&vehicle_make=$vehicle_make&vehicle_model=$vehicle_model&year_from=$year_from&year_to=$year_to");			
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
							
				$data['vehicles_array'] = $this->Vehicles->filter($where, $config["per_page"], $offset);	
				$data['pagination'] = $this->pagination->create_links();
				
				//assign page title name
				$data['pageTitle'] = 'Vehicles';
				
				//assign page ID
				$data['pageID'] = 'listings';
						
				$this->load->view('pages/header', $data);
				
				$this->load->view('pages/vehicle_listings_page', $data);
				
				$this->load->view('pages/footer');
			
			}
			
		}
		
		/**
		* Function for controller
		*  index
		*/	
		public function searchOld2(){
			
			if(isset($_GET['vehicle_type']) || !empty($_GET['vehicle_type'])) {
				
				$data = array();
				
				$type = $_GET['vehicle_type'];
				if($type=='get_vehicle_makes') {
					$data = $this->Vehicle_make->get_vehicle_makes();
				} 
				if($type=='getModels') {
					$make_id = $_GET['make_id'];
					$data = $this->Vehicle_make->get_vehicle_model($make_id);
				}
				
			}
			echo json_encode($data);
		}
		
		/**
		* Function to get models 
		* 
		*/			
		public function get_models(){
			
			if(!empty($this->input->post('id')) || $this->input->post('id') != ''){
					
				$id = $this->input->post('id');
				$options = '';
				$options .= '<select name="vehicle_model" class="form-control custom-select" id="vehicle_model">';
				$options .= '<option value="0" selected="selected">All Models</option>';
						
				$this->db->from('vehicle_model');
				$this->db->where('make_id', $id);
				$result = $this->db->get();
				if($result->num_rows() > 0) {
					foreach($result->result_array() as $row){
						$title = str_replace("-", "", $row['title']);
						$options .= '<option value="'.$title.'">'.$title.'</option>';			
					}
				}
				$options .= '</select>';
					
				$data['options'] = $options;
				$data['success'] = true;
					
			}else {
				$data['success'] = false;
			}
			echo json_encode($data);
		}		
	

							
		/**
		 * Function for Vehicle Type Display Page.
		 *
		 */
		public function vehicles_by_type($type = '')
		{
			//set cart count
			$data['cart_count'] = 0;
				
			//check if cart session is set
			if($this->session->userdata('cart_array')){ 
				
				//count cart items
				$cart_count = count($this->session->userdata('cart_array'));
				if($cart_count == '' || $cart_count == null){
						$cart_count = 0;
				}
				$data['cart_count'] = $cart_count;
				
				//get cart items
				$data['cart_array'] = $this->session->userdata('cart_array');
			}
			$type = html_escape($type);
			$type = trim($type);
			
			$data['vehicles_array'] = $this->Vehicles->get_vehicles_by_type($type);
			$data['search'] = '';
				
			//GET COUNT OF PRODUCTS
			$data['count'] = '';
				
			//assign meta tags
			$page = 'vehicles';
			$keywords = '';
			$description = '';
			$metadata_array = $this->Page_metadata->get_page_metadata($page);
			if($metadata_array){
				foreach($metadata_array as $meta){
					$keywords = $meta->keywords;
					$description = $meta->description;
				}
			}
			if($description == '' || $description == null){
				$description = 'Auto9ja - one stop shop for new and used vehicles';
			}
			//assign meta_description
			$data['meta_description'] = $description;
			
			//assign meta_author
			$data['meta_author'] = 'Auto9ja';
			
			//assign meta_keywords		
			$data['meta_keywords'] = $keywords;

			//assign page title name
			$data['pageTitle'] = 'Vehicles';
				
			//assign page ID
			$data['pageID'] = 'vehicles';
			
			$this->load->view('pages/header', $data);
			
			$this->load->view('pages/vehicle_listings_page', $data);
				
			$this->load->view('pages/footer', $data);
		}

		
		
		
		
	}