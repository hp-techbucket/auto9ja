<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['about'] = 'home/about';
$route['become-a-seller'] = 'home/become_a_seller';
$route['faq'] = 'home/faq';
$route['contact-us'] = 'home/contact_us';
$route['contact_us/validation'] = 'home/contact_us_validation';
$route['social'] = 'home/social';
$route['login'] = 'home/login';
$route['login/validation'] = 'home/login_validation';
$route['signup'] = 'home/signup';
$route['signup/validation'] = 'home/signup_validation';
$route['register'] = 'home/register';
$route['register/validation'] = 'home/register_validation';
$route['signup-success'] = 'home/signup_success';
//$route['account/activation/(:any)/(:any)'] = 'home/account_activation/$1/$2';
$route['account/activation/(.+)'] = 'home/account_activation/$1';
$route['activation'] = 'home/activation';
$route['activation/validation'] = 'home/activation_validation';
$route['activation/success'] = 'home/activation_success';
$route['activation/error'] = 'home/activation_error';
$route['set-security-information'] = 'account/set_security_information';
$route['security/validation'] = 'account/security_info_validation';
$route['profile/update'] = 'account/update_profile';
$route['password/update'] = 'account/update_password';
$route['security/update'] = 'account/update_security';

$route['password/reset'] = 'account/password_reset';
$route['password/new'] = 'account/new_password_validation';
//$route['message/support'] = 'message/support_message_validation';
$route['logged_out'] = 'home/logged_out';


$route['vehicle-finder'] = 'vehicles/vehicles_display';
$route['vehicles-search'] = 'vehicles/search';

$route['vehicles/(:any)'] = 'vehicles/vehicles_by_type/$1';

$route['vehicles/(:any)/(:num)/(:any)'] = 'vehicles/view_vehicle/$1/$2/$3';


