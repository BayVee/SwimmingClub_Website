<div id="comments">
    <?php if(post_password_required()): ?>
        <p class="nopassword"><?php _e('This page is protected. Please insert the password to be able to read its contents.', 'cpotheme' ); ?></p>
    </div>
    <?php return; endif; ?>

    <?php if(have_comments()): ?>
        <h3 id="comments_title">
            <?php if(get_comments_number() == 1) _e('One Comment', 'cpotheme'); else printf(__('%1$s Comments', 'cpotheme'), number_format_i18n(get_comments_number())); ?>
        </h3>
        <ol class="commentlist">
            <?php wp_list_comments('type=comment&callback=cpotheme_layout_comments'); ?>
        </ol>

        <h3 id="comments_title">
            <?php _e('Pingbacks', 'cpotheme'); ?>
        </h3>
        <ol class="pinglist">
            <?php wp_list_comments('type=pings&callback=cpotheme_layout_comments'); ?>
        </ol>

	<?php if(get_comment_pages_count() > 1 && get_option('page_comments')): ?>
        <div class="navigation">
            <div class="nav_previous"><?php previous_comments_link(__('Older', 'cpotheme')); ?></div>
            <div class="nav_next"><?php next_comments_link(__('Newer', 'cpotheme')); ?></div>
        </div>
	<?php endif; ?>

    <?php else: ?>
	<?php if(!comments_open()): ?>
	<p class="nocomments"><?php _e('Comments are closed.', 'cpotheme' ); ?></p>
	<?php endif; ?>
    <?php endif; ?>
</div>

<div id="commentform">
    <?php comment_form(); ?>
</div>