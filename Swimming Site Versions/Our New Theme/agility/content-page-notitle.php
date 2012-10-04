<?php
/**
 * The template used for displaying page content in page.php - with no title
 *
 * @package Agility
 * @since Agility 1.0
 */
?>

<!-- Begin content-page.php -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'agility' ), 'after' => '</div>' ) ); ?>
		<?php edit_post_link( __( 'Edit', 'agility' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
<!-- end content-page.php -->