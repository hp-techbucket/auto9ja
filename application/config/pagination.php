<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['use_page_numbers'] = TRUE;

/* This Application Must Be Used With BootStrap 3 *  */
//$config['full_tag_open'] = '<div class="pagination"><ul>';
//$config['full_tag_close'] = '</ul></div>';
$config['full_tag_open'] = '<ul class="pagination">';
$config['full_tag_close'] = '</ul>';
$config['first_link'] = false;
$config['last_link'] = false;	
//$config['first_link'] = '&laquo; First';
$config['first_tag_open'] = '<li class="prev page">';
$config['first_tag_close'] = '</li>';	
			
//$config['last_link'] = 'Last &raquo;';
$config['last_tag_open'] = '<li class="next page">';
$config['last_tag_close'] = '</li>';
			
$config['prev_link'] = '&laquo Previous&nbsp;';
$config['prev_tag_open'] = '<li class="prev page">';
$config['prev_tag_close'] = '</li>';	
						
$config['next_link'] = '&nbsp;Next &raquo';
$config['next_tag_open'] = '<li class="next page">';
$config['next_tag_close'] = '</li>';
						
$config['cur_tag_open'] = "<li class='active'><a href='#'>";
$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
						
$config['num_tag_open'] = '<li class="page">';
$config['num_tag_close'] = '</li>';

/* End of file Pagination.php */
/* Location: ./system/application/config/pagination.php */