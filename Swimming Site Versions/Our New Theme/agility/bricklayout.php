<?php
/**
 * Template Name: BrickLayer Custom Template
 *
 * Custom templating system.  If you override this file in a child theme,
 * be sure to include the $brickLayout->showLayout() call, or the custom template 
 * system won't work
 *
 * @package Agility
 * @since Agility 1.0
 */

get_header(); 
global $post;
$layout_id = get_post_meta( $post->ID, 'bricklayer_layout' , true );

$brickLayout = new BrickLayout( $layout_id );

?>

		<!-- Begin bricklayout.php :: <?php echo $brickLayout->getTitle(); ?>

		============================================ -->

		<?php $brickLayout->showLayout(); ?>

		<!-- end bricklayout.php -->
		
<?php get_footer(); ?>