<?php 

// Registers all widget areas
add_action('widgets_init', 'cpotheme_sidebar_init');
function cpotheme_sidebar_init(){
	
    register_sidebar(array('name' => __('Homepage', 'cpotheme'),
    'id' => 'home-widget-area',
    'description' => __('Sidebar shown only in the homepage.', 'cpotheme'),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="title">',
    'after_title' => '</h3>'));

    register_sidebar(array('name' => __('Primary Widgets', 'cpotheme'),
    'id' => 'primary-widget-area',
    'description' => __('Default sidebar shown in all standard pages.', 'cpotheme'),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="title">',
    'after_title' => '</h3>'));

    register_sidebar(array('name' => __('Blog', 'cpotheme'),
    'id' => 'blog-widget-area',
    'description' => __('Sidebar shown in the blog page template.', 'cpotheme'),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="title">',
    'after_title' => '</h3>'));

    register_sidebar(array('name' => __('Footer Sidebar 1', 'cpotheme'),
    'id' => 'first-footer-widget-area',
    'description' => __('Shown in the footer area.', 'cpotheme'),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="title">',
    'after_title' => '</h3>'));

    register_sidebar(array('name' => __('Footer Sidebar 2', 'cpotheme'),
    'id' => 'second-footer-widget-area',
    'description' => __('Shown in the footer area.', 'cpotheme'),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="title">',
    'after_title' => '</h3>'));

    register_sidebar(array('name' => __('Footer Sidebar 3', 'cpotheme'),
    'id' => 'third-footer-widget-area',
    'description' => __('Shown in the footer area.', 'cpotheme'),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="title">',
    'after_title' => '</h3>'));
	
	register_sidebar(array('name' => __('Footer Sidebar 4', 'cpotheme'),
    'id' => 'fourth-footer-widget-area',
    'description' => __('Shown in the footer area.', 'cpotheme'),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="title">',
    'after_title' => '</h3>'));
}

//Registers all menu areas
add_action('widgets_init', 'cpotheme_menu_init');
function cpotheme_menu_init(){
    //Register navigation menus
    register_nav_menus(array('top_menu' => __('Top Menu', 'cpotheme')));
    register_nav_menus(array('main_menu' => __('Main Menu', 'cpotheme')));
    register_nav_menus(array('footer_menu' => __('Footer Menu', 'cpotheme')));
}
?>