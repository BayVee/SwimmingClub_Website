<?php get_header(); ?>
        
<?php $feature_posts = new WP_Query('post_type=cpo_feature&posts_per_page=-1&order=ASC&orderby=menu_order'); ?>
<?php if(sizeof($feature_posts) > 0): $feature_count = 0; ?>
<div id="features">
	<?php while($feature_posts->have_posts()): $feature_posts->the_post(); ?>
	<?php if($feature_count % 4 == 0 && $feature_count != 0) echo '<div class="separator"></div>'; $feature_count++; ?>
    <div class="feature<?php if($feature_count % 2 == 0) echo ' feature_second'; ?><?php if($feature_count % 4 == 0) echo ' feature_right'; ?>">
    	<h2><?php the_title(); ?></h2>
		<?php the_post_thumbnail(array(300, 400)); ?>
		<div class="content"><?php the_content(); ?></div>
	</div>
	<?php endwhile; ?>
</div>
<?php endif; ?>

<?php $feature_posts = new WP_Query('post_type=cpo_portfolio&posts_per_page=6&order=ASC&orderby=menu_order'); ?>
<?php if(sizeof($feature_posts) > 0): $feature_count = 0; ?>
<div id="showcase">
	<div class="description">
        <?php echo stripslashes(cpotheme_get_option('cpo_home_portfolio')); ?>
    </div>
    <div class="work">
		<?php while($feature_posts->have_posts()): $feature_posts->the_post(); ?>
        <?php if($feature_count % 3 == 0 && $feature_count != 0) echo '<div class="separator"></div>'; $feature_count++; ?>
        <a href="<?php the_permalink(); ?>" class="item<?php if($feature_count % 3 == 0) echo ' item_right'; ?>">
            <div class="thumbnail">
				<?php the_post_thumbnail(array(300, 500)); ?>
                <div class="content">
                    <?php the_excerpt(); ?>
                </div>
            </div>
            <div class="title">
            	<h3><?php the_title(); ?></h3>
            </div>
        </a>
        
        <?php endwhile; ?>
    </div>
    <div class='clear'></div>
</div>
<?php endif; ?>


<div id="content">
	<?php $home_posts = cpotheme_get_option('cpo_home_limit');
	if($home_posts == '' || !is_numeric($home_posts)) $home_posts = 5;
	query_posts('posts_per_page='.$home_posts); $post_count = 0; ?>
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
</div>
 
<?php get_sidebar('home'); ?>
<?php get_footer(); ?>
