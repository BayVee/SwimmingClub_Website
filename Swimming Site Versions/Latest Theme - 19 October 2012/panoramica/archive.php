<?php get_header(); ?>


<div id="content">
    
    <div class="entry">
        <?php if(is_category()){ ?>
        <a class="subscribe" href="<?php echo get_category_feed_link($cat); ?>" title="<?php _e('Subscribe to this RSS Feed', 'cpotheme'); ?>"><img src="<?php echo get_template_directory_uri().'/images/icon_rss.png'; ?>" alt="RSS"/></a>
        <h1 class="title"><?php echo single_cat_title(); ?></h1>
        <div class="meta">
            <?php $cat_obj = $wp_query->get_queried_object(); ?> 
        </div>

        <?php }elseif(is_day()){ ?>
        <h1 class="title"><?php _e('Archive', 'cpotheme'); ?></h1>
        <div class="meta"><?php the_time(get_option('date_format')); ?></div>

        <?php }elseif(is_month()){ ?>
        <h1 class="title"><?php _e('Archive', 'cpotheme'); ?></h1>
        <div class="meta"><?php the_time(get_option('date_format')); ?></div>

        <?php }elseif(is_year()){ ?>
        <h1 class="title"><?php _e('Archive', 'cpotheme'); ?></h1>
        <div class="meta"><?php the_time(get_option('date_format')); ?></div>

        <?php }elseif(is_author()){ ?>
        <h1 class="title"><?php _e('Archive', 'cpotheme'); ?></h1>
        <div class="meta"><?php _e('Author archive', 'cpotheme'); ?></div>

        <?php }elseif(is_tag()){ ?>
        <h1 class="title"><?php _e('Archive', 'cpotheme'); ?></h1>
        <div class="meta"><?php _e('Tag', 'cpotheme'); ?> <?php echo single_tag_title('', true); ?></div>
        <?php } ?>
    </div>
    
    <?php if(have_posts()): $count = 0; ?>
    <?php while(have_posts()): the_post(); ?>													
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
    
    <div id="navigation">
    	<div class="prev"><?php previous_posts_link(__('Newer', 'cpotheme')); ?></div>
        <div class="next"><?php next_posts_link(__('Older', 'cpotheme')); ?></div>
    </div>
    
    <?php else: ?>

    <div class="entry"><p><?php _e('No posts found.', 'cpotheme') ?></p></div>

    <?php endif; ?> 
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
