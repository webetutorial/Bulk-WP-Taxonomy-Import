<?php
/*
Plugin Name: Import Bulk WooCommerce Taxonomy/Attributes
Plugin URI: http://www.webetutorial.com/shop/
Description: This plugin import bulk Taxonomy in woocomerce product 
Author: webetutorial
Version: 1.0.9
Author URI: http://www.webetutorial.com/
*/

//define some constant for table and folder getting options
$siteurl = get_option('siteurl');
define('IBWA_FOLDER', dirname(plugin_basename(__FILE__)));
define('IBWA_URL', $siteurl.'/wp-content/plugins/' . IBWA_FOLDER);
define('IBWA_FILE_PATH', dirname(__FILE__));
define('IBWA_DIR_NAME', basename(IBWA_FILE_PATH));

add_action('admin_print_styles', 'add_ibwa_stylesheet');

function add_ibwa_stylesheet() 
{	
	$myStyleUrl = IBWA_URL . '/style.css';
	$myStyleFile = IBWA_FILE_PATH . '/style.css';
	if ( file_exists($myStyleFile) ) 
	{
		wp_register_style('myStyleSheets', $myStyleUrl);
		wp_enqueue_style( 'myStyleSheets');
	}	 
}

add_action('admin_menu','ibwa_admin_menu');

function ibwa_admin_menu() { 
	add_menu_page(
		"Import Bulk WooCommerce Attributes",
		"Import Bulk WooCommerce Attributes",
		8,
		__FILE__,
		"ibwa_admin_menu_list"
	); 
 
}

function ibwa_admin_menu_list(){
	include 'ibwa_admin_menu_list.php';
}

add_action('init', 'custom_taxonomy_flush_rewrite');
function custom_taxonomy_flush_rewrite() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

?>
