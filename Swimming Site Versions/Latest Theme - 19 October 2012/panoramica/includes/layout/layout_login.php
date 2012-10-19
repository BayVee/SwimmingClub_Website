<?php 
//Custom Login Form
add_action('login_head', 'cpotheme_login_style');
function cpotheme_login_style(){ 
	echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/includes/css/login.css" />'; 
} ?>