<?php get_header(); ?>

<div id="content">

    <div class="entry">
    	<h1 class="title"><?php _e('Search Results', 'cpotheme') ?></h1>
        <div id="search_form">
            <form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
                <input type="text" value="<?php echo the_search_query(); ?>" name="s" id="s" /><input type="submit" id="searchsubmit" value="Buscar" />
            </form>
        </div>
    </div>
    <?php if(have_posts()): while(have_posts()): the_post(); ?>    
    <?php if(get_post_type(get_the_ID()) == 'post'): ?>
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
    <?php else: ?>
    <div class="preview"> 
        <h2 class="title">
            <a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'cpotheme'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>
        <div class="pagecontent"><?php the_excerpt(); ?></div>
	</div>
    <?php endif; ?>
    <?php endwhile; ?>
    
    <?php cpotheme_post_pagination(); ?>
    
	<?php else: ?>
    
    <div class="entry">
        <div class="content"><?php _e('No results have been found.', 'cpotheme'); ?></div>
    </div>
    
    <?php endif; ?>  

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
