<?php
/*
  Template Name: Full-width Page
 */
?>
<?php get_header(); ?>

<div id="content" class="wide">
    <?php if (have_posts()) while (have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" class="entry">
        

        <div class="content">
            <?php the_content(); ?>
            <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'cpotheme'), 'after' => '</div>')); ?>
        </div>

    </div>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>