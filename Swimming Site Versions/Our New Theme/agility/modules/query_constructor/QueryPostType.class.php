<?php 

/**
 * QueryPostType
 *
 * Custom Post Type for saving queries
 *
 */

if(!class_exists('AgilityCustomPostType')) require_once( get_template_directory().'/modules/custom_post_types/CustomPostType.class.php');

class QueryPostType extends AgilityCustomPostType {
	
	public $slug = 'query_definition';
	public $name = 'Query';
	public $name_plural = 'Queries';
			
	public function __construct( $labels = array(), $post_args = array() ){
		
		$this->baseURL = get_template_directory_uri().'/modules/'.basename( dirname( __FILE__ ) ).'/';
		
		$this->labels = $labels;
		
		$post_arg_defaults = array(
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => false, 
			'show_in_menu' => false, 
			'query_var' => false,
			'rewrite' => false,
			'capability_type' => 'post',
			'has_archive' => false,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array(
				'title',
				//'editor',
				//'author',
				//'thumbnail',
				//'excerpt',
				//'comments'
			),
			'can_export' => true
		);
		
		$this->post_args = wp_parse_args($post_args, $post_arg_defaults);
		
		// Call parent constructor
		parent::__construct();
	}

}