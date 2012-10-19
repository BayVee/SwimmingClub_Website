<?php
/*
Template Name: Sitemap
*/
?>
<?php get_header(); ?>

<div id="content" class="wide">
    <?php if (have_posts()) while (have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" class="entry">
        <h1 class="title"><?php the_title(); ?></h1>

        <div class="content">
            <?php the_content(); ?>
            <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'cpotheme'), 'after' => '</div>')); ?>
        </div>

    </div>
    <div class="sitemap">
		<?php $sitemap = get_pages('sort_column=menu_order&sort_order=ASC&parent=0&post_status=publish');
        $count = 0; ?>
        <div class="row">
        <?php foreach($sitemap as $current_page){ ?>
            <?php if($current_page->ID != $post->ID): ?>
            <ul>
                <li><a href="<?php echo get_permalink($current_page->ID); ?>"><?php echo $current_page->post_title; ?></a></li>
                <?php $child_pages = get_pages('sort_column=menu_order&sort_order=ASC&child_of='.$current_page->ID.'&parent='.$current_page->ID); ?>
                <?php if(sizeof($child_pages) > 0): ?>
                <ul>
                    <?php foreach($child_pages as $current_child){ ?>
                    <li><a href="<?php echo get_permalink($current_child->ID); ?>"><?php echo $current_child->post_title; ?></a></li>
                    <?php } ?>
                </ul>
                <?php endif; ?>
            </ul>
            <?php endif; 
            $count++;
            if($count % 4 == 0): ?></div><div class="row"><?php endif; ?>
        <?php } ?>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>