<?php
/*
  Template Name: Submenu: Siblings
 */
?>
<?php get_header(); ?>

<div id="submenu">
	<?php $parent_post = get_post($post->post_parent); ?>
    <h3><?php echo $parent_post->post_title; ?></h3>
	<ul class="nav_sub">
		<?php wp_list_pages("title_li=&child_of=".$post->post_parent); ?>
	</ul>
</div>
<div id="content" class="submenu">
    <?php if (have_posts()) while (have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" class="entry">
        <h1 class="title"><?php the_title(); ?></h1>

        <div class="content">
            <?php the_content(); ?>
            <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'cpotheme'), 'after' => '</div>')); ?>
        </div>

    </div>
    <?php endwhile; ?>
</div>
<?php get_footer(); ?>