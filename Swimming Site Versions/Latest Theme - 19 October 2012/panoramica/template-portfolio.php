<?php
/*
  Template Name: Portfolio Page
 */
?>
<?php get_header(); ?>

<div id="submenu">
	<ul class="nav_sub">
		<?php wp_list_categories("taxonomy=cpo_tax_portfolio&title_li="); ?>
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
    <?php $feature_posts = new WP_Query('post_type=cpo_portfolio&posts_per_page=6&order=ASC&orderby=menu_order'); ?>
	<?php if(sizeof($feature_posts) > 0): $feature_count = 0; ?>
    <div id="portfolio">
        <div class="work">
            <?php while($feature_posts->have_posts()): $feature_posts->the_post(); ?>
            <?php if($feature_count % 3 == 0 && $feature_count != 0) echo '<div class="separator"></div>'; $feature_count++; ?>
            <a href="<?php the_permalink(); ?>" class="item<?php if($feature_count % 3 == 0) echo ' item_right'; ?>">
                <div class="thumbnail">
                    <?php the_post_thumbnail(array(250, 500)); ?>
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
</div>
<?php get_footer(); ?>