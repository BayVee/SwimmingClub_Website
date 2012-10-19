<?php

// COURSE POST DECLARACTION
// Adds a custom post type for course modules
add_action('init', 'cpotheme_tax_theme');
function cpotheme_tax_theme() 
{
	//Set up labels
	$labels = array('name' => _x('Categories', 'post type general name', 'cpotheme', 'cpotheme'),
	'singular_name' => _x('Category', 'post type singular name', 'cpotheme'),
	'add_new' => _x('New Category', 'slide', 'cpotheme'),
	'add_new_item' => __('Add Category', 'cpotheme'),
	'edit_item' => __('Edit Category', 'cpotheme'),
	'new_item' => __('New Category', 'cpotheme'),
	'view_item' => __('View Category', 'cpotheme'),
	'search_items' => __('Search Categories', 'cpotheme'),
	'not_found' =>  __('No categories were found.', 'cpotheme'),
	'not_found_in_trash' => __('No categories were found in the trash.', 'cpotheme'), 
	'parent_item_colon' => '');
	
	$fields = array('labels' => $labels,
	'public' => true,
	'publicly_queryable' => true,
	'show_ui' => true, 
	'query_var' => true,
	'rewrite' => array('slug' => 'portfolios'),
	'capability_type' => 'post',
	'hierarchical' => false,
	'menu_icon' => get_bloginfo('url').'/wp-admin/images/generic.png',
	'menu_position' => null,
	'supports' => array('title', 'editor', 'thumbnail')); 
	
	register_taxonomy('cpo_tax_portfolio', 'cpo_portfolio', $fields);
} 
?>