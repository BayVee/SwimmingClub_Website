<?php get_header(); ?>

<div id="content">

    <?php if(have_posts()) while(have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" class="entry" <?php post_class(); ?>>
        <h1 class="title"><?php the_title(); ?></h1>

        <div class="meta"><?php cpotheme_post_byline(); ?></div>

        <div class="content">
            <?php the_content(); ?>
            <?php wp_link_pages(array('before' => '<div id="postpagination">', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
        </div>
        
        <div class="utility"><?php cpotheme_post_tags(); ?></div>

    	<?php if(get_the_author_meta('description')) cpotheme_post_authorbio(); ?>
        
    </div>

    <?php comments_template('', true); ?>

    <?php endwhile; ?>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>