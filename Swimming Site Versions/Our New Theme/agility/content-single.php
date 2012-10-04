<?php
/**
 * Display a single post's content
 *
 * @package Agility
 * @since Agility 1.0
 */

global $post;
?>

<!-- Begin content-single.php -->
<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<div class="entry-meta">
			<div class="cat-links five columns alpha">
				<?php
				/* translators: used between list items, there is a space after the comma */
				$category_list = get_the_category_list( __( ', ', 'agility' ) );
				echo $category_list;
				?>
			</div>
			<div class="six columns omega far-edge">
				<?php agility_posted_on(); ?>
			</div>
		</div>
		<!-- end .entry-meta -->

	</header><!-- .entry-header -->

	<?php agility_feature_single( true ); ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'agility' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<!-- post footer -->
	<footer class="entry-meta">
		<?php /* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', ', ' );
			if( $tag_list ): ?>
		<span class="post-tags tooltip-container"><a href="#" class="icon tag-icon tooltip-anchor"></a><span class="tooltip"><?php
			echo $tag_list;
		?></span></span>
		<?php endif; ?>

		<?php if ( comments_open() ): ?>
		<span class="post-comments tooltip-container"><a href="#comments" class="icon comments-icon tooltip-anchor"></a><span class="tooltip"><?php comments_number(); ?></span></span> 
		<?php endif; ?>

		<span class="post-permalink tooltip-container"><?php echo agility_permalink( '', 'icon permalink-icon tooltip-anchor' ); ?><span class="tooltip"><?php _e( 'Permalink', 'agility' ); ?></span></span>

		<?php edit_post_link( '', '<span class="edit-link tooltip-container">', '<span class="tooltip">Edit</span></span>' ); ?>
	</footer>
	<!-- end post footer.entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->

<!-- End content-single.php -->

