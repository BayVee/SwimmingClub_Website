<?php
// HOMEPAGE FEATURES DECLARACTION
// Adds a custom post type for homepage features
add_action('init', 'cpotheme_cpost_feature');
function cpotheme_cpost_feature() 
{
	//Set up labels
	$labels = array('name' => __('Features', 'cpotheme'),
	'singular_name' => __('Feature', 'cpotheme'),
	'add_new' => __('Add Feature', 'cpotheme'),
	'add_new_item' => __('Add New Feature', 'cpotheme'),
	'edit_item' => __('Edit Feature', 'cpotheme'),
	'new_item' => __('New Feature', 'cpotheme'),
	'view_item' => __('View Features', 'cpotheme'),
	'search_items' => __('Search Features', 'cpotheme'),
	'not_found' =>  __('No features found.', 'cpotheme'),
	'not_found_in_trash' => __('No features found in the trash.', 'cpotheme'), 
	'parent_item_colon' => '');
	
	$fields = array('labels' => $labels,
	'public' => false,
	'publicly_queryable' => false,
	'show_ui' => true, 
	'query_var' => true,
	'rewrite' => true,
	'capability_type' => 'post',
	'hierarchical' => false,
	'menu_icon' => home_url().'/wp-admin/images/generic.png',
	'menu_position' => null,
	'supports' => array('title', 'editor', 'thumbnail', 'page-attributes')); 
	
	register_post_type('cpo_feature', $fields);
} ?>