<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <?php if(function_exists('cpotheme_description')) cpotheme_description(); ?>
    <?php if(function_exists('cpotheme_description')) cpotheme_keywords(); ?>
    
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
    <link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Imprima' rel='stylesheet' type='text/css'>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
    
    <title><?php cpotheme_title(); ?></title>
    
    <?php if(is_singular() && get_option('thread_comments')) wp_enqueue_script('comment-reply'); ?>
    <?php wp_head(); ?>
</head>

<?php $bg_color = cpotheme_get_option('cpo_bg_color'); if($bg_color == '') $bg_color = '#fff'; ?>
<body <?php body_class(); ?> style="background-color:<?php echo $bg_color; ?>">	    

    <div class="wrapper_top">
    	<div id='topmenu'>
			<?php wp_nav_menu(array('menu_class' => 'nav_top', 'theme_location' => 'top_menu', 'fallback_cb' => null)); ?>
        </div>
    </div>
    
    <div class="wrapper_header">
        <div id='header'>
            <div class="logo">
                <?php if(cpotheme_get_option('cpo_general_texttitle') == 0): ?>
                <?php if(cpotheme_get_option('cpo_general_logo') == ''): ?>
                <a href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri().'/images/logo.png'; ?>" alt="<?php echo bloginfo('name'); ?>"/></a>
                <?php else: ?>
                <a href="<?php echo home_url(); ?>"><img src="<?php echo cpotheme_get_option('cpo_general_logo'); ?>" alt="<?php echo bloginfo('name'); ?>"/></a>
                <?php endif; ?>
                <?php endif; ?>

                <?php if(is_singular() && !is_front_page()): ?>
                    <span class="title<?php if(cpotheme_get_option('cpo_general_texttitle') == 0){ ?> hidden<?php } ?>"><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></span>
                <?php else: ?>
                    <h1 class="title<?php if(cpotheme_get_option('cpo_general_texttitle') == 0){ ?> hidden<?php } ?>"><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
                <?php endif; ?>
                <span class="description"><?php bloginfo('description'); ?></span>
            </div>
            <div id='menu'>
                <?php wp_nav_menu(array('menu_class' => 'nav_main', 'theme_location' => 'main_menu', 'depth' => '2' )); ?>
            </div>
            <div class='clear'></div>
        </div>
	</div>

    
	<?php if(is_home() || is_front_page()){ ?>
    <?php $slider_posts = new WP_Query('post_type=cpo_slide&posts_per_page=-1&order=ASC&orderby=menu_order'); ?>
    <?php if(sizeof($slider_posts) > 0): $slide_count = 0; ?>
    <div id='slider'>
        <div class='prev'></div>
        <div class='next'></div>
        <div class='pages'></div>
        <ul class="slider_container">
            <?php while($slider_posts->have_posts()): $slider_posts->the_post(); ?>
            <?php $slide_count++; ?>
            <?php $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), array(1500, 7000), false, ''); ?>
            <li id="slider_slide_<?php echo $slide_count; ?>" style="background:url(<?php echo $image_url[0]; ?>) no-repeat #555 center;">
                <div class="textbox">
                    <div class="content">
                        <h2><?php the_title(); ?></h2>
                        <?php the_content(); ?>
                    </div>
                </div>
            </li>
            <?php endwhile; ?>
        </ul>
    </div>  
	<?php endif; ?>
    <?php } ?>
    
    <?php if(is_home() || is_front_page()){ ?>
    <div class="wrapper_header">
        <div id='tagline'>
            <h2><?php echo stripslashes(cpotheme_get_option('cpo_home_tagline')); ?></h2> 
        </div>
    </div>
    <?php } ?>
        
    <?php $bg_texture = cpotheme_get_option('cpo_bg_texture');
	$texture_file = '';
	switch($bg_texture){
		case 'dots': $texture_file = get_template_directory_uri().'/images/bg_texture_dots.png'; break;
		case 'diagonal': $texture_file = get_template_directory_uri().'/images/bg_texture_diagonal.png'; break;
		case 'bubbles': $texture_file = get_template_directory_uri().'/images/bg_texture_bubbles.png'; break;
		case 'diamonds': $texture_file = get_template_directory_uri().'/images/bg_texture_diamonds.png'; break;
		case 'stripes': $texture_file = get_template_directory_uri().'/images/bg_texture_stripes.png'; break;
		case 'grid': $texture_file = get_template_directory_uri().'/images/bg_texture_grid.png'; break;
		case 'metal': $texture_file = get_template_directory_uri().'/images/bg_texture_metal.png'; break;
		case 'stone': $texture_file = get_template_directory_uri().'/images/bg_texture_stone.png'; break;
		case 'checkerboard': $texture_file = get_template_directory_uri().'/images/bg_texture_checkerboard.png'; break;
	} ?>
    <div class="wrapper" <?php if($bg_texture != '') echo 'style="background-image:url('.$texture_file.');"'; ?>>
		<?php if(!is_home() && !is_front_page()){ ?>
        <div id='breadcrumb'>
            <?php cpotheme_layout_breadcrumb(); ?>
        </div>
        <?php } ?>
        
        <div id='main'>