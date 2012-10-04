<?php
/**
 * This header makes both the logo and nav bar full-width, placing the navigation beneath
 * the logo.  Rename this file to "header.php" to use this header.
 *
 * Displays all of the <head> section and site header up to #main-container
 *
 * @package Agility
 * @since Agility 1.1.3
 */

global $agilitySettings;

?><!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>> <!--<![endif]-->

<head>
	
	<!-- Agility - Responsive HTML5/CSS3 WordPress Theme by SevenSpark http://agility.sevenspark.com -->

	<!-- Basic Page Needs
  	================================================== -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	
	<!-- Mobile Specific Metas
  	================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=<?php echo $agilitySettings->op( 'viewport-maximum-scale' ); ?>">

	<title><?php
		/*
		 * Print the <title> tag based on what is being viewed.
		 */
		global $page, $paged;

		//WP SEO by Yoast
		if( function_exists( 'wpseo_get_value' ) ){
			wp_title('');
		}
		//Standard
		else{
			wp_title( '&ndash;', true, 'right' );
	
			// Add the blog name.
			bloginfo( 'name' );
		
			// Add the blog description for the home/front page.
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description && ( is_home() || is_front_page() ) )
				echo " &ndash; $site_description";
		
			// Add a page number if necessary:
			if ( $paged >= 2 || $page >= 2 )
				echo ' | ' . sprintf( __( 'Page %s', 'agility' ), max( $paged, $page ) );
		}
		?></title>
		
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/selectivizr.js"></script>
	<![endif]-->
	
	<!--[if IE 7]>
		<link rel="stylesheet" href="<?php echo get_template_directory_uri().'/stylesheets/ie7.css'; ?>">
	<![endif]-->

	<!-- Favicons
	================================================== -->
	<?php if( $favicon = $agilitySettings->op( 'favicon-image' ) ): ?><link rel="shortcut icon" href="<?php echo $favicon; ?>">
	<?php endif; ?><?php if( $icon_57 = $agilitySettings->op( 'apple-touch-57' ) ): ?><link rel="apple-touch-icon" href="<?php echo $icon_57; ?>">
	<?php endif; ?><?php if( $icon_72 = $agilitySettings->op( 'apple-touch-72' ) ): ?><link rel="apple-touch-icon" sizes="72x72" href="<?php echo $icon_72; ?>">
	<?php endif; ?><?php if( $icon_114 = $agilitySettings->op( 'apple-touch-114' ) ): ?><link rel="apple-touch-icon" sizes="114x114" href="<?php echo $icon_114; ?>">
	<?php endif; ?>


	<!-- Begin wp_head()
	================================================== -->
	<?php wp_head(); ?>
	<!-- end wp_head() -->

</head>

<body <?php body_class( $agilitySettings->op( 'background-preset' ).' '.$agilitySettings->op( 'highlight-color' ).' '. ( $agilitySettings->op( 'box-layout' ) ? 'box-layout' : '' ) ); ?>>
	
	<!-- #page .wrap -->
	<div id="page" class="wrap hfeed site">

		<div class="drop-container">
			<?php if( $agilitySettings->op( 'enable-drop-container' ) ) : ?>
			<aside class="drop-panel">
				<div class="container">
				<?php dynamic_sidebar( 'drop-container' ); ?>
				</div>
			</aside>
			<?php endif; ?>
			<div class="drop-bar-container">
				<div class="container">
					<div class="drop-bar sixteen columns far-edge">
						&nbsp;
						<?php if( $agilitySettings->op( 'enable-social-media' ) ) : ?>
						<?php agility_social_media_icons(); ?>
						<?php endif; ?>
						<?php if( $agilitySettings->op( 'enable-drop-container' ) ) : ?>
						<a href="#" id="drop-panel-expando">+</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		
		<?php do_action( 'before' ); ?>
		
		<!-- Header -->
		<header id="header" class="container site-header site-header-full-width" role="banner">
        	<h1 id="site-title" class="site-title"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php
					switch( $agilitySettings->op( 'logo-type' ) ){
						case 'image';
							?><img src="<?php echo $agilitySettings->op( 'logo-image' ); ?>" class="scale-with-grid" /><?php
							break;
						case 'image_text':
							?><img src="<?php echo $agilitySettings->op( 'logo-image' ); ?>" class="scale-with-grid" /> <?php bloginfo( 'name' );
							break;
						case 'text_image':
							bloginfo( 'name' ); ?> <img src="<?php echo $agilitySettings->op( 'logo-image' ); ?>" class="scale-with-grid" /> <?php 
							break;
						case 'text':
						default:
							bloginfo( 'name' );
							break;
					} 
					?></a></h1>
			
			<div id="header-inner" class="sixteen columns over">
				
				<hgroup id="masthead">
					<?php if( get_bloginfo( 'description' ) ): ?>
					<h2 id="sub-title" class="site-description"><?php bloginfo( 'description' ); ?></h2>
					<?php endif; ?>
				</hgroup>
		
				
				<?php 
					$pre_wrap = '
					<h1 class="assistive-text">'. __( 'Menu', 'agility' ).'</h1>
					<div class="assistive-text skip-link"><a href="#content" title="'.esc_attr( 'Skip to content', 'agility' ) .'">'.__( 'Skip to content', 'agility' ).'</a></div>
					
					<a href="#main-nav-menu" class="mobile-menu-button button">+ Menu</a>';
				
					wp_nav_menu( array( 
						'theme_location' 	=> 'primary',
						'container'			=> 'nav',
						'container_class'	=> 'menu-primary-container site-navigation main-navigation',
						'container_id'		=> 'main-nav',
						'menu_class'		=> 'nav-menu',
						'menu_id'			=> 'main-nav-menu',
						'items_wrap'		=> $pre_wrap.'<ul id="%1$s" class="%2$s">%3$s</ul>',
						'fallback_cb'		=> 'agility_nav_hint'
					) ); 
				?>
			
				
			</div>
		</header>
		<!-- end Header -->
	
		