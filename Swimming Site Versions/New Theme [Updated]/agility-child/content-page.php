<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Agility
 * @since Agility 1.0
 */


global $post;
$subtitle = get_post_meta( $post->ID , 'post_subtitle' , true );

?>

<!-- Begin content-page.php -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="page-title entry-title <?php if( $subtitle ) echo 'entry-title-with-sub'; ?>"><?php the_title(); ?></h1>
		<?php if( $subtitle ): ?>
		<h5 class="sub-page-title"><?php echo $subtitle; ?></h5>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'agility' ), 'after' => '</div>' ) ); ?>
		<?php edit_post_link( __( 'Edit', 'agility' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
<!-- end content-page.php -->