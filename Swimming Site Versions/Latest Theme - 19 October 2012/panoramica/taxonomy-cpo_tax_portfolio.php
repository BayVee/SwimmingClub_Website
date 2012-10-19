<?php get_header(); ?>

<div id="submenu">
	<ul class="nav_sub">
		<?php wp_list_categories("taxonomy=cpo_tax_portfolio&title_li="); ?>
	</ul>
</div>
<div id="content" class="submenu">
    <div id="post-<?php the_ID(); ?>" class="entry">
        <h1 class="title"><?php echo single_tag_title('', true); ?></h1>
        <div class="content">
        	<?php echo tag_description(); ?>
        </div>
    </div>
	<?php $feature_count = 0; ?>
    <div id="portfolio">
        <div class="work">
			<?php if (have_posts()) while (have_posts()) : the_post(); ?>
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
</div>
<?php get_footer(); ?>