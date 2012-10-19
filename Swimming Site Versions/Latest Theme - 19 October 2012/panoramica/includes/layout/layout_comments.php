<?php 
//Generates the comments layout
function cpotheme_layout_comments($comment, $args, $depth){
    $GLOBALS['comment'] = $comment;
    
    //Normal Comments
    switch($comment->comment_type): case '': ?>
    <li id="comment-<?php comment_ID(); ?>">
        <div class="avatar">
            <?php echo get_avatar($comment, 40); ?>
        </div>
        <div class="comment">    
            <?php if($comment->comment_approved == '0'): ?>
                <em class="approval"><?php _e('Your comment is awaiting approval.', 'cpotheme'); ?></em>
            <?php endif; ?>

            <div class="title">
                <span class="author"><?php echo get_comment_author_link(); ?></span>
                <a class="date" href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                    <?php printf(__('%1$s at %2$s', 'cpotheme'), get_comment_date(),  get_comment_time()); ?>
                </a>
            </div>

            <div class="content"><?php comment_text(); ?></div>

            <div class="options">
                <?php edit_comment_link(__('Edit', 'cpotheme')); ?>
                <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
            </div>
        </div>

    <?php break;
    
    //Pingbacks & Trackbacks
    case 'pingback':
    case 'trackback': ?>
        <li class="pingback">
            <!--<?php _e('Pingback:', 'cpotheme'); ?>--><?php comment_author_link(); ?><?php edit_comment_link(__('Edit', 'cpotheme'), ' (', ')'); ?>
    <?php break;
    endswitch;
} ?>