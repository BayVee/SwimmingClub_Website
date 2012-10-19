<?php get_header(); ?>

<div id="content" class="wide">

    <?php if(have_posts()) while(have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" class="portfolio" <?php post_class(); ?>>
        <h1 class="title"><?php the_title(); ?></h1>
        <?php $args = array(
                'post_type' => 'attachment',
                'numberposts' => null,
                'post_status' => null,
                'post_mime_type' => 'image',
                'post_parent' => $post->ID);
                $attachments = get_posts($args);
				$thumb_count = 0;
                if($attachments): ?>
         <div class="slides">
         	<ul>
				<?php foreach($attachments as $attachment): $thumb_count++; ?>
                <li><?php the_attachment_link($attachment->ID, true); ?></li>
                <?php endforeach; ?>
            </ul>
            <div class="pages"></div>
	    </div>
        <?php endif; ?>
        <div class="content">
            <?php the_content(); ?>
            <?php wp_link_pages(array('before' => '<div id="postpagination">', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
        </div>
        
        <div class="utility"><?php cpotheme_post_tags(); ?></div>

    	<div class="clear"></div>
    </div>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>