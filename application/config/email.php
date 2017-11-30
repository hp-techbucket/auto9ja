<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	$config['protocol'] = "smtp";
	$config['validate'] = 'FALSE';
	$config['smtp_host'] = "ssl://cp-in-1.webhostbox.net";
	$config['smtp_port'] = "465";
	$config['smtp_user'] = "getextra@global-sub.com"; 
	$config['smtp_pass'] = "1234567";
	$config['charset'] = "utf-8";
	$config['mailtype'] = "html";
	$config['newline'] = "\r\n";

/* End of file email.php */
/* Location: ./system/application/config/email.php */