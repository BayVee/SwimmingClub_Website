<?php
/*
  Template Name: Submenu: Children
 */
?>
<?php get_header(); ?>

<div id="submenu">
	<ul class="nav_sub">
		<?php wp_list_pages("title_li=&child_of=".$post->ID); ?>
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