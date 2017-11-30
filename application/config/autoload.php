<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Packages
| 2. Libraries
| 3. Drivers
| 4. Helper files
| 5. Custom config files
| 6. Language files
| 7. Models
|
*/

/*
| -------------------------------------------------------------------
|  Auto-load Packages
| -------------------------------------------------------------------
| Prototype:
|
|  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
|
*/

$autoload['packages'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in the system/libraries folder
| or in your application/libraries folder.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'email', 'session');
|
| You can also supply an alternative library name to be assigned
| in the controller:
|
|	$autoload['libraries'] = array('user_agent' => 'ua');
*/

$autoload['libraries'] = array('database', 'session', 'table', 'javascript', 'pagination', 'user_agent', 'form_validation', 'facebook', 'merchant', 'ipnlistener', 'paypal_lib');


/*
| -------------------------------------------------------------------
|  Auto-load Drivers
| -------------------------------------------------------------------
| These classes are located in the system/libraries folder or in your
| application/libraries folder within their own subdirectory. They
| offer multiple interchangeable driver options.
|
| Prototype:
|
|	$autoload['drivers'] = array('cache');
*/

$autoload['drivers'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = array('url', 'file');
*/

$autoload['helper'] = array('form', 'url', 'html', 'security', 'date', 'file');


/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = array('config1', 'config2');
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/

$autoload['config'] = array('facebook', 'google', 'pagination', 'email');


/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
|
*/

$autoload['language'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('first_model', 'second_model');
|
| You can also supply an alternative model name to be assigned
| in the controller:
|
|	$autoload['model'] = array('first_model' => 'first');
*/

$autoload['model'] = array(
						'Address_book_model' => 'Address_book',
						'Admin_model' => 'Admin',
						'Bank_payment_methods_model' => 'Bank_payment_methods',
						'Cart_model' => 'Cart',
						'Contact_us_model' => 'Contact_us',
						'Countries_model' => 'Countries',
						'Customers_model' => 'Customers',
						'Customer_reviews_model' => 'Customer_reviews',
						'Deposits_model' => 'Deposits',
						'Email_alerts_model' => 'Email_alerts',
						'Failed_logins_model' => 'Failed_logins',
						'Failed_resets_model' => 'Failed_resets',
						'Invoices_model' => 'Invoices',
						'Keywords_model' => 'Keywords',
						'Keyword_icons_model' => 'Keyword_icons',
						'Logins_model' => 'Logins',
						'Messages_model' => 'Messages',
						'Oauth_logins_model' => 'Oauth_logins',
						'Order_details_model' => 'Order_details',
						'Orders_model' => 'Orders',
						'Page_metadata_model' => 'Page_metadata',
						'Password_resets_model' => 'Password_resets',
						'Payments_model' => 'Payments',
						'Payment_confirmation_model' => 'Payment_confirmation',
						'Paypal_accounts_model' => 'Paypal_accounts',
						'Security_questions_model' => 'Security_questions',
						'Shipping_model' => 'Shipping',
						'Shipping_methods_model' => 'Shipping_methods',
						'Shipping_status_model' => 'Shipping_status',
						'Site_activities_model' => 'Site_activities',
						'Traders_model' => 'Traders',
						'Trader_reviews_model' => 'Trader_reviews',
						'Transactions_model' => 'Transactions',
						'Vehicles_model' => 'Vehicles',
						'Vehicle_makes_model' => 'Vehicle_makes',
						'Vehicle_models_model' => 'Vehicle_models',
						'Vehicle_types_model' => 'Vehicle_types',
						'Watchlist_model' => 'Watchlist',
						
						
						
						
					);

