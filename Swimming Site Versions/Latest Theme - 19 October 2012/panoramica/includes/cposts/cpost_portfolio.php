<?php
// HOMEPAGE FEATURES DECLARACTION
// Adds a custom post type for homepage features
add_action('init', 'cpotheme_cpost_portfolio');
function cpotheme_cpost_portfolio() 
{
	//Set up labels
	$labels = array('name' => __('Portfolio', 'cpotheme'),
	'singular_name' => __('Portfolio', 'cpotheme'),
	'add_new' => __('Add Portfolio Item', 'cpotheme'),
	'add_new_item' => __('Add New Portfolio Item', 'cpotheme'),
	'edit_item' => __('Edit Portfolio Item', 'cpotheme'),
	'new_item' => __('New Portfolio Item', 'cpotheme'),
	'view_item' => __('View Portfolio', 'cpotheme'),
	'search_items' => __('Search Portfolio', 'cpotheme'),
	'not_found' =>  __('No portfolio items found.', 'cpotheme'),
	'not_found_in_trash' => __('No portfolio items found in the trash.', 'cpotheme'), 
	'parent_item_colon' => '');
	
	$fields = array('labels' => $labels,
	'public' => true,
	'publicly_queryable' => true,
	'show_ui' => true, 
	'query_var' => true,
	'rewrite' => array('slug' => 'portfolio'),
	'capability_type' => 'post',
	'hierarchical' => false,
	'menu_icon' => home_url().'/wp-admin/images/generic.png',
	'menu_position' => null,
	'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes')); 
	
	register_post_type('cpo_portfolio', $fields);
} ?>