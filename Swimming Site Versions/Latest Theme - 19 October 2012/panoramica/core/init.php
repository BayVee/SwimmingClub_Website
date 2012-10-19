<?php
//Theme options setup
add_action('after_setup_theme', 'cpotheme_setup');
function cpotheme_setup(){
	//Set core variables
	cpotheme_update_option('cpo_core_version', '1.2.0');
	cpotheme_update_option('cpo_core_support', 'http://www.cpothemes.com/');

	//Initialize supported theme features
	add_editor_style();
	add_theme_support('post-thumbnails');
	add_theme_support('automatic-feed-links');
	add_post_type_support('page', 'excerpt');
	
	//Remove WordPress version number for security purposes
	remove_action('wp_head', 'wp_generator');
	
	//Load translation text domain and make translation available
	load_theme_textdomain('cpotheme', get_template_directory().'/languages');
	$locale = get_locale();
	$locale_file = get_template_directory()."/languages/$locale.php";
	if(is_readable($locale_file)) require_once($locale_file);
}


//Add Javascript scripts
add_action('wp_print_scripts', 'cpotheme_add_scripts');
function cpotheme_add_scripts( ){
    $scripts_theme_path = get_template_directory_uri().'/scripts/';
	$scripts_path = get_template_directory_uri().'/core/scripts/';
	if(defined('WP_CPODEV')) $scripts_path = get_template_directory_uri().'/../cpoframework/core/scripts/';

	//Common scripts
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-widget');
	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('jquery-effects-core');
	wp_enqueue_script('jquery-effects-fade');
	wp_enqueue_script('thickbox');
    
	//Public page scripts
	if(!is_admin()){
		wp_enqueue_script('script_general', $scripts_theme_path.'general.js');
		wp_enqueue_script('script_jquery_cycle', $scripts_path.'jquery_cycle.js');
	
	//Admin page scripts
	}else{
		wp_enqueue_script('media-upload');        
		wp_enqueue_script('script_colorpicker', $scripts_path.'colorpicker/colorpicker.js');
		wp_enqueue_script('script_colorpicker', $scripts_path.'colorpicker/datepicker.js');
		wp_enqueue_script('script_general_admin', $scripts_path.'admin.js');
	}		
}


//Add public stylesheets
add_action('wp_print_styles', 'cpotheme_add_styles');
function cpotheme_add_styles(){
	$stylesheets_path = get_template_directory_uri().'/core/css/';
	if(defined('WP_CPODEV')) $stylesheets_path = get_template_directory_uri().'/../cpoframework/core/css/';
	
    //Common styles
    wp_enqueue_style('thickbox');     
}


//Add admin stylesheets
add_action('admin_print_styles', 'cpotheme_add_admin_styles');
function cpotheme_add_admin_styles(){
	$stylesheets_path = get_template_directory_uri().'/core/css/';
	if(defined('WP_CPODEV')) $stylesheets_path = get_template_directory_uri().'/../cpoframework/core/css/';
	
    wp_enqueue_style('style_admin', $stylesheets_path.'admin.css');
    wp_enqueue_style('style_colorpicker', $stylesheets_path.'colorpicker/colorpicker.css');
    wp_enqueue_style('thickbox');    
}

//Add all Core components
$core_path = get_template_directory().'/core/';
if(defined('WP_CPODEV'))
	$core_path = get_template_directory().'/../cpoframework/core/';
	
require_once($core_path.'general.php');
require_once($core_path.'filters.php');
require_once($core_path.'meta.php');
//require_once($core_path.'update.php');
require_once($core_path.'custom.php');
require_once($core_path.'forms.php');
require_once($core_path.'seo.php');
require_once($core_path.'settings.php');
require_once($core_path.'widgets/widget_recentposts.php');
require_once($core_path.'widgets/widget_flickr.php');
require_once($core_path.'shortcodes/shortcode_box.php');
require_once($core_path.'shortcodes/shortcode_button.php');
require_once($core_path.'shortcodes/shortcode_column.php');
require_once($core_path.'shortcodes/shortcode_accordion.php');


?>