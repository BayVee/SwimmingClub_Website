<?php 

function cpotheme_metadata_backgroundtexture(){
	$cpotheme_data = array(
	'' => get_template_directory_uri().'/images/admin/texture_none.gif',
	'dots' => get_template_directory_uri().'/images/admin/texture_dots.gif',
	'diagonal' => get_template_directory_uri().'/images/admin/texture_diagonal.gif',
	'stripes' => get_template_directory_uri().'/images/admin/texture_stripes.gif',
	'diamonds' => get_template_directory_uri().'/images/admin/texture_diamonds.gif',
	'bubbles' => get_template_directory_uri().'/images/admin/texture_bubbles.gif',
	'grid' => get_template_directory_uri().'/images/admin/texture_grid.gif',
	'checkerboard' => get_template_directory_uri().'/images/admin/texture_checkerboard.gif',
	'metal' => get_template_directory_uri().'/images/admin/texture_metal.gif',
	'stone' => get_template_directory_uri().'/images/admin/texture_stone.gif');
	
	return $cpotheme_data;
} ?>