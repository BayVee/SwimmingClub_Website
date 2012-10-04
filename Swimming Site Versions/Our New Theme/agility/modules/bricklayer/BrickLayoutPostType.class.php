<?php
/**
 * BrickLayoutPostType
 *
 * WordPress Custom Post type modeling BrickLayouts used for storing data in the WordPress database
 *
 */


class BrickLayoutPostType extends AgilityCustomPostType{

	public $slug = 'brick_layout';
	public $name = 'BrickLayout';
	public $name_plural = 'BrickLayouts';


	function __construct( $labels = array(), $post_args = array() ){
		
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
			)
		);
		
		$this->post_args = wp_parse_args($post_args, $post_arg_defaults);
		
		// Call parent constructor
		parent::__construct();
		
	}

}

$GLOBALS['cpt_brick_layout'] = new BrickLayoutPostType();