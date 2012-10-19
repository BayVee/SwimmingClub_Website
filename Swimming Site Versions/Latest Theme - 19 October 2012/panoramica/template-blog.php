<?php
/*
Template Name: Blog
*/
?>
<?php get_header(); ?>

<div id="content">
    <?php if(have_posts()) while(have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" class="entry">
        <h1 class="title"><?php the_title(); ?></h1>

        <div class="content">
            <?php the_content(); ?>
            <?php wp_link_pages(array('before' => '<div class="page-link">'.__('Pages:', 'cpotheme'), 'after' => '</div>')); ?>
        </div>
    </div>
    <?php endwhile; ?>
    
    <?php if(get_query_var('paged')) $current_page = get_query_var('paged'); else $current_page = 1; ?>
    <?php query_posts("post_type=post&paged=$current_page&posts_per_page=8"); ?>
    <?php if(have_posts()) while(have_posts()) : the_post(); ?>   
	<div class="preview"> 
        <div class="meta">
            <div class="thumbnail"><?php the_post_thumbnail(array(300, 800)); ?></div>
            <div class="date"><?php the_date(); ?></div>                
            <div class="tags"><?php cpotheme_post_tags(); ?></div>
            <div class="comments"><?php if(get_comments_number() == 1) _e('One Comment', 'cpotheme'); else printf(__('%1$s Comments', 'cpotheme'), number_format_i18n(get_comments_number())); ?></div>
        </div>
        <h2 class="title">
            <a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'cpotheme'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>
        <div class="byline"><?php cpotheme_post_shortbyline(); ?></div>
        <div class="content"><?php the_excerpt(); ?></div>
	</div>
	<?php endwhile; ?>
    
    <?php cpotheme_post_pagination(); ?>
    
</div>

<?php get_sidebar('blog'); ?>
<?php get_footer(); ?>