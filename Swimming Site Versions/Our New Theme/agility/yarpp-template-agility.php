<?php 
/*
Agility Related Posts Template
Author: Chris Mavricos, SevenSpark
*/
?>
<?php if ( have_posts() ):?>
<h6><?php _e( 'Related Posts', 'agility' ); ?></h6>
<?php agility_bloglist( null, 'eleven' ); wp_reset_postdata(); ?>
<?php else: ?>
<h6><?php _e( 'Latest Posts', 'agility' ); ?></h6>
<?php agility_latest_bloglist(); ?>
<?php endif; ?>
