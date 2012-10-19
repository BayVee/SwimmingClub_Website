<?php get_header(); ?>

<div id="content">

    <?php if(have_posts()) while(have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" class="entry">
        <h1 class="title"><?php the_title(); ?></h1>
        
        <div class="content">
            <?php the_content(); ?>
            <?php wp_link_pages(array('before' => '<div class="page-link">'.__('Pages', 'cpotheme').':', 'after' => '</div>')); ?>
        </div>
    </div>

    <?php endwhile; ?>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>