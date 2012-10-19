<?php

// HOMEPAGE SLIDER DECLARACTION
// Adds a custom post type for homepage slider
add_action('init', 'cpotheme_cpost_slide');
function cpotheme_cpost_slide() 
{
	//Set up labels
	$labels = array('name' => __('Slides', 'cpotheme'),
	'singular_name' => __('Slide', 'cpotheme'),
	'add_new' => __('New Slide', 'cpotheme'),
	'add_new_item' => __('Add New Slide', 'cpotheme'),
	'edit_item' => __('Edit Slide', 'cpotheme'),
	'new_item' => __('New Slide', 'cpotheme'),
	'view_item' => __('View Slide', 'cpotheme'),
	'search_items' => __('Search Slides', 'cpotheme'),
	'not_found' =>  __('No slides were found.', 'cpotheme'),
	'not_found_in_trash' => __('No slides were found in the trash.', 'cpotheme'), 
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
	
	register_post_type('cpo_slide', $fields);
} ?>