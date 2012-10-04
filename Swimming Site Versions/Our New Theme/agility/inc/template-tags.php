<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Agility
 * @since Agility 1.0
 */


if ( ! function_exists( 'agility_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since Agility 1.0
 */
function agility_content_nav( $nav_id , $force = false , $post_type = 'posts' ) {
	global $wp_query, $agilitySettings;

	$nav_class = 'site-navigation paging-navigation';
	if ( is_single() )
		$nav_class = 'site-navigation post-navigation';

	?>
	<nav role="navigation" id="<?php echo $nav_id; ?>" class="<?php echo $nav_class; ?>">
		<h1 class="assistive-text"><?php _e( 'Post navigation', 'agility' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'agility' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'agility' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if( $nav_id == 'nav-below' && $agilitySettings->op( 'blog-pagination' ) ): ?>

			<?php agility_pagination( '' , 2 ); ?>

		<?php else: ?>

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( sprintf( __( '<span class="meta-nav">&larr;</span> Older %s', 'agility' ) , $post_type ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( sprintf( __( 'Newer %s <span class="meta-nav">&rarr;</span>', 'agility' ) , $post_type ) ); ?></div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // agility_content_nav


/*
 * From Kriesi - thanks! http://www.kriesi.at/archives/how-to-build-a-wordpress-post-pagination-without-plugin
 */
function agility_pagination($pages = '', $range = 2){  
     $showitems = ($range * 2)+1;  

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         echo "<div class='pagination'>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         echo "</div>\n";
     }
}


/*
 * Utility functions to swap out a temporary query
 */
function agility_swap_query( $special_query ){
	global $wp_query;
	$GLOBALS['temporary_swap_query'] = $wp_query;
	$wp_query = $special_query;
}
function agility_unswap_query(){
	global $wp_query;
	$wp_query = $GLOBALS['temporary_swap_query'];
}


if ( ! function_exists( 'agility_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Agility 1.0
 */
function agility_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'agility' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'agility' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="cf comment-info">
				<?php if( $depth > 1 ): ?>
				<span class="comment-reply-indicator">&rarr;</span>
				<?php endif; ?>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 30 ); ?>
					<?php printf( sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author .vcard -->
				
				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'agility' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>
					<?php edit_comment_link( __( '(Edit)', 'agility' ), ' ' );
					?>
				</div><!-- .comment-meta .commentmetadata -->
			</footer>

			<?php if ( $comment->comment_approved == '0' ) : ?>

				<em class="comment-moderated alert alert-warning"><?php _e( 'Your comment is awaiting moderation.', 'agility' ); ?></em>
				
			<?php endif; ?>


			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] , 'reply_text' => __( 'Reply &rarr;' , 'agility') ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for agility_comment()

if ( ! function_exists( 'agility_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Agility 1.0
 */
function agility_posted_on() {
	
	printf( __( '<span class="byline">by <span class="author vcard">'.
				'<a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>'.
				' on <a href="%1$s" title="%2$s" rel="bookmark">'.
				'<time class="entry-date" datetime="%3$s">%4$s</time></a>',
				'agility' ),
		
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'agility' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
}
endif;

if ( ! function_exists( 'agility_categories' ) ):
/**
 * Prints Categories
 */
function agility_categories( $echo = true ){

					$cats = "&nbsp";

					/* translators: used between list items, there is a space after the comma */
					$categories_list = get_the_category_list( __( ', ', 'agility' ) );
					if ( $categories_list && agility_categorized_blog() ) :
					?>
					<?php $cats = $categories_list; ?>
					<?php endif; // End if categories 

					if( $echo ) echo $cats;
					else return $cats;
}
endif;


if ( ! function_exists( 'agility_custom_categories' ) ):
/**
 * Prints terms from a specific taxonomy in Category Format
 *
 */
function agility_custom_categories($post , $taxonomy , $echo=true, $separator=', ' ){
	global $post, $wp_rewrite;
	$terms = get_the_terms( $post->ID, $taxonomy );
	if( empty( $terms ) ) return '';

	$thelist = '';
	$i = 0;
	$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'data-rel="category tag"' : 'data-rel="category"';
	foreach( $terms as $term ){
		if ( 0 < $i ) $thelist .= $separator;
		$thelist .= '<a href="' . get_term_link( $term ) . '" title="' . esc_attr( sprintf( __( "View all entries in %s" , 'agility' ), $term->name ) ) . '" ' . $rel . '>' . $term->name.'</a>';
		++$i;
	}

	if( $echo ) echo $thelist;
	return $thelist;
}
endif;



/**
 * Returns true if a blog post has more than 1 category
 *
 * @since Agility 1.0
 */
function agility_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so agility_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so agility_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in agility_categorized_blog
 *
 * @since Agility 1.0
 */
function agility_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'agility_category_transient_flusher' );
add_action( 'save_post', 'agility_category_transient_flusher' );




/**
 * Display the featured media for a single post
 */
function agility_feature_single( $expander = false, $showCaption = false ){

	global $post;

	switch( get_post_meta( $post->ID , 'feature_type', true ) ){

		case 'none':
			break;
			
		case 'video':
		case 'video-embed':
			$video_url = get_post_meta( $post->ID, 'featured_video' , true );
			if( $video_url ){
				agility_featured_video_embedded( $video_url );
			}
			else{
				?>
				<div class="hint clearfix">Please enter a video URL in the "Featured Video" field, or change the Feature Type</div>
				<?php
			}
			break;

		case 'video-self':
			agility_featured_videojs();
			break;

		case 'slider':
			agility_slider( $post->ID , 'portfolio_item', agility_image_size( $post->ID , 640 ) );
			echo '&nbsp;';
			break;

		case 'image':
		default:
			if( has_post_thumbnail() ){
				agility_featured_image( agility_image_size( $post->ID, 640 ), 'full-width', $expander, '', $showCaption );
			}
			break;
	}
}

/**
 * Display a featured image with a lightbox
 */
function agility_featured_image( $size = 'full', $class = '' , $expander = false, $gallery = '', $showCaption = false, $textOverImage = false ){

		global $post, $agilitySettings;
		$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
		$src = wp_get_attachment_image_src( $post_thumbnail_id , 'full' );
		$video_url = get_post_meta( $post->ID, 'featured_video' , true );

		$feature_type = get_post_meta( $post->ID , 'feature_type', true );

		?>
		
		<!-- featured-image -->
		<div class="featured-image img-wrapper <?php echo $class; ?>">

		<?php if( $feature_type == 'video-self' ): ?>
			<?php agility_featured_videojs(); ?>
		<?php elseif( $feature_type == 'image' && is_single() && !$agilitySettings->op( 'enable_lightbox_single' ) ): ?>

			<!-- featured image - single - Lightbox disabled -->
			<?php the_post_thumbnail( $size , array( 'class' => 'scale-with-grid')); ?>

			<?php if( $expander ): ?>
			<span class="single-post-feature-expander">&harr;</span>
			<?php endif; ?>

			<?php if( $showCaption ): ?>
			<div class="photo-credit"><?php echo get_post_field('post_excerpt', $post_thumbnail_id); ?></div>
			<?php endif; ?>

		<?php elseif( !is_single() && !$agilitySettings->op( 'enable_lightbox' ) ): ?>
			
			<!-- featured image - Lightbox disabled -->
			<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( $size , array( 'class' => 'scale-with-grid')); ?>
			</a>

			<?php if( $expander ): ?>
			<span class="single-post-feature-expander">&harr;</span>
			<?php endif; ?>

			<?php if( $showCaption ): ?>
			<div class="photo-credit"><?php echo get_post_field('post_excerpt', $post_thumbnail_id); ?></div>
			<?php endif; ?>

		<?php elseif( get_post_meta( $post->ID , 'feature_type', true ) == 'video-embed' && $video_url ): ?>

			<!-- lightbox with embedded video -->
			<a href="#video-<?php echo $post->ID; ?>" data-rel="prettyPhoto<?php echo $gallery; ?>" class="video-link" title="<?php 
				echo strip_tags( get_the_excerpt() ); ?>" <?php agility_alternative_href(); ?> ><?php 
				the_post_thumbnail( $size , array( 'class' => 'scale-with-grid' , 'alt' => get_the_title() )); ?></a>
			<div id="video-<?php echo $post->ID; ?>" class="inline-video-lightbox-content"><?php agility_video_embed( $video_url ); ?>
			</div>
			<!-- end lightbox with embedded video -->
		
		<?php else: ?>

			<!-- lightbox with image -->
			<a href="<?php echo $src[0] ?>" data-rel="prettyPhoto<?php echo $gallery; ?>" class="img-link preload" title="<?php 
				echo get_the_title( $post_thumbnail_id ); ?>" <?php agility_alternative_href(); ?> ><?php 
				the_post_thumbnail( $size , array( 'class' => 'scale-with-grid')); ?><?php 
				if( $textOverImage ) echo "<span class='text-over-image'>$textOverImage</span>"; ?></a>
			<!-- end lightbox with image -->

			<?php if( $expander ): ?>
			<span class="single-post-feature-expander">&harr;</span>
			<?php endif; ?>

			<?php if( $showCaption ): ?>
			<div class="photo-credit"><?php echo get_post_field('post_excerpt', $post_thumbnail_id); ?></div>
			<?php endif; ?>

		<?php endif; ?>

		</div>
		<!-- end .featured-image -->

		<?php

}

/* Used for mobile devices that do not display lightboxes */
function agility_alternative_href(){
	if( !is_single() ): 
		?> data-href-alt="<?php the_permalink(); ?>" <?php
	endif;
}

/**
 * Display a featured embedded video
 */
function agility_featured_video_embedded( $video_url ){
	?>
					<div class="featured-video">
						<?php agility_video_embed( $video_url ); ?>					
					</div>
	<?php
}

/**
 * Display an embedded video
 */
function agility_video_embed( $video_url , $flex = false ){
	global $wp_embed;
	//echo $flex ? 'yes':'no';
	?>	
						<div class="video-wrapper">
							<div class="video-container <?php if( $flex ) echo 'video-flex'; ?>">
								<?php echo $wp_embed->run_shortcode( '[embed]'.$video_url.'[/embed]' ); ?>
							</div><!-- end .video-container -->
						</div><!-- end .video-wrapper -->					
	<?php
}


/**
 * Outputs a complete commenting form for use within a template.
 * Most strings and form fields may be controlled through the $args array passed
 * into the function, while you may also choose to use the comment_form_default_fields
 * filter to modify the array of default fields if you'd just like to add a new
 * one or remove a single field. All fields are also individually passed through
 * a filter of the form comment_form_field_$name where $name is the key used
 * in the array of fields.
 *
 * @since 1.0
 * @param array $args Options for strings, fields etc in the form
 * @param mixed $post_id Post ID to generate the form for, uses the current post if null
 * @return void
 */
function agility_comment_form( $args = array(), $post_id = null ) {
	global $id;

	if ( null === $post_id )
		$post_id = $id;
	else
		$id = $post_id;

	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = ! empty( $user->ID ) ? $user->display_name : '';

	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$fields =  array(
		'author' => '<p class="comment-form-author '.( $req ? 'comment-input-required' : '' ).'">' . '<label for="author" class="fallback">' . __( 'Name' , 'agility' ) . '</label> ' .
		            '<input id="author" name="author" type="text" placeholder="' . __( 'Name' , 'agility' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
		'email'  => '<p class="comment-form-email '.( $req ? 'comment-input-required' : '' ).'"><label for="email" class="fallback">' . __( 'Email' , 'agility' ) . '</label> ' .
		            '<input id="email" name="email" type="text" placeholder="' . __( 'Email' , 'agility' ) . '" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
		'url'    => '<p class="comment-form-url"><label for="url" class="fallback">' . __( 'Website' , 'agility' ) . '</label>' .
		            '<input id="url" name="url" type="text" placeholder="' . __( 'Website' , 'agility' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
	);

	$required_text = ' ' . __('Required fields are marked with a grey bar.', 'agility' );
	$defaults = array(
		'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field'        => '<p class="comment-form-comment"><label for="comment" class="fallback">' . _x( 'Comment', 'noun', 'agility' ) . '</label><textarea id="comment" placeholder="Comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		'must_log_in'          => '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email address will not be published.', 'agility' ) . ( $req ? $required_text : '' ) . '</p>',
		'comment_notes_after'  => '<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',
		'id_form'              => 'commentform',
		'id_submit'            => 'submit',
		'title_reply'          => __( 'Leave a Reply', 'agility' ),
		'title_reply_to'       => __( 'Leave a Reply to %s', 'agility' ),
		'cancel_reply_link'    => __( '&times; Cancel reply', 'agility' ),
		'label_submit'         => __( 'Post Comment', 'agility' ),
	);

	$args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

	?>
		<?php if ( comments_open() ) : ?>
			<?php do_action( 'comment_form_before' ); ?>
			<div id="respond">
				<h3 id="reply-title"><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?> <small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small></h3>
				<?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
					<?php echo $args['must_log_in']; ?>
					<?php do_action( 'comment_form_must_log_in_after' ); ?>
				<?php else : ?>
					<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>">
						<?php do_action( 'comment_form_top' ); ?>
						<?php if ( is_user_logged_in() ) : ?>
							<?php echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ); ?>
							<?php do_action( 'comment_form_logged_in_after', $commenter, $user_identity ); ?>
						<?php else : ?>
							<?php echo $args['comment_notes_before']; ?>
							<?php
							do_action( 'comment_form_before_fields' );
							foreach ( (array) $args['fields'] as $name => $field ) {
								echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
							}
							do_action( 'comment_form_after_fields' );
							?>
						<?php endif; ?>
						<?php echo apply_filters( 'comment_form_field_comment', $args['comment_field'] ); ?>
						<?php echo $args['comment_notes_after']; ?>
						<p class="form-submit">
							<input name="submit" type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" value="<?php echo esc_attr( $args['label_submit'] ); ?>" />
							<?php comment_id_fields( $post_id ); ?>
						</p>
						<?php do_action( 'comment_form', $post_id ); ?>
					</form>
				<?php endif; ?>
			</div><!-- #respond -->
			<?php do_action( 'comment_form_after' ); ?>
		<?php else : ?>
			<?php do_action( 'comment_form_comments_closed' ); ?>
		<?php endif; ?>
	<?php
}

/**
 * Display a permalink
 */
function agility_permalink( $text , $class ){
	?>
	<a href="<?php the_permalink(); ?>" class="<?php echo $class; ?>" ><?php echo $text; ?></a>
	<?php
}

/**
 * Display the latest posts in bloglist format
 */
function agility_latest_bloglist( $args = array() , $cols = 'eleven' ){

	$args = wp_parse_args( $args , array(
		'orderby'				=>	'date',
		'order'					=>	'DESC',
		'posts_per_page'		=>	2,
		'ignore_sticky_posts'	=>	true
		));

	$latest = new WP_Query( $args );
	if ( $latest->have_posts() ) { 
		agility_bloglist( $latest , $cols );
	}
	wp_reset_postdata();
}

/**
 * Show a blog list.
 *
 * @param wp_query $query
 * @param string $cols The number of columns - options are 'eight' or 'eleven'
 */
function agility_bloglist( $query=null, $cols='eight' ){

	if( is_null( $query ) ){
		global $wp_query;
		$query = $wp_query;
	}
	
	$left_cols = 'two';
	$right_cols = 'six';
	
	
	$meta = true;
	$k = 0;
	?>
	
				<!-- Blog List -->
				<div class="bloglist cf">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<?php /*foreach($posts as $id => $post){*/
						global $post;
						$alphaomega = 'alpha'; // $k % 2 == 0 ? 'alpha' : 'omega';
						$k++;
						if( has_post_thumbnail() ){
							switch($cols){
		
									case 'eight':
										$left_cols = 'two';
										$right_cols = 'six';
										
										break;
										
									case 'eleven':
										$left_cols = 'three';
										$right_cols = 'eight';
										
										break;

									case 'four':
										$left_cols = 'four omega';
										$right_cols = 'four alpha';
										break;
								}
						}
						else{
							//the_title(); echo 'has nothumb';
							switch($cols){
							
								case 'eight':
									$left_cols = '';
									$right_cols = 'eight alpha';
									
									break;
									
								case 'eleven':
									$left_cols = '';
									$right_cols = 'eleven alpha';
									
									break;

								case 'four':
									$left_cols = 'four omega';
									$right_cols = 'four alpha';
									break;
								
							}
						}
					?>
					
					<!-- blog list article -->
					<article class="post <?php echo $cols; ?> columns <?php echo $alphaomega; ?> <?php if($meta) echo 'with-meta'; ?>">
						
						<?php if( has_post_thumbnail() ): ?>
						<div class="<?php echo $left_cols; ?> columns alpha">
							<?php agility_featured_image( agility_image_size( $post->ID , 640 ), 'full-width', false );	?>
						</div>
						<?php endif; ?>
						
						<div class="<?php echo $right_cols; ?> columns omega">
							<header class="cf">
								<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
								<?php if($meta): ?>
								<div class="entry-meta">
									<span class="cat-links alpha">
										<?php
										/* translators: used between list items, there is a space after the comma */
										$category_list = get_the_category_list( __( ', ', 'agility' ) );
										echo $category_list;
										?>
									</span>
									<span class="posted-on">
										<?php agility_posted_on(); ?>
									</span>
								</div>
								<?php endif; ?>								
							</header>

							<div class="entry-excerpt">
								<?php the_excerpt(); //agility_post_excerpt($id, $post, false); ?>
							</div>
						</div>
					</article>
					<!-- end blog list article -->
					
					<?php endwhile; ?>
					
				</div>
				<!-- end .bloglist -->
	<?php
}

/**
 * Test whether a meta field is set to on or off and returns a binary result for easy testing
 */
function agility_meta_on( $key , $default = 'off', $post_id = 0 , $single = true ){
	if( $post_id == 0 ){
		global $post;
		$post_id = $post->ID;
	}

	switch( $default ){
		case 'on':
			return get_post_meta( $post_id , $key , $single ) == 'off' ? false : true;
			break;

		case 'off':
			return get_post_meta( $post_id , $key , $single ) == 'on' ? true : false;
			break;

	}

	return get_post_meta( $post_id , $key , $single ) == 'on' ? true : false;
	
}


/**
 * Show a basic grid/mosaic format
 *
 */
function agility_mosaic( $query , $column_size , $ops ){

	if( is_null( $query ) ){
		global $wp_query;
		$query = $wp_query;
	}

	$defaults = array(
		'show_title'	=>	'on',	//todo
		'wrap'			=>	'on',
		'column_container'	=>	16, //11
		'size'			=>	'large',
	);
	extract( wp_parse_args( $ops , $defaults ));

//	$divisor = agility_divide_columns( $column_size , $column_container );

	$grid_columns = $column_size;
	$grid_columns_class = agility_grid_columns_class( $grid_columns );
	$items_per_row = agility_divide_columns( $grid_columns, $column_container );

	$k = 0;
	?>
	
				<!-- Blog List -->
				<div class="mosaic">
					<div class="row">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<?php 
							global $post;
							$alphaomega =  agility_alphaomega( $k , $items_per_row );; 
							if( $wrap == 'on' ){
								
								if( $k % $items_per_row == 0 ){
									//$alphaomega = 'alpha';
									if( $k > 0 ) echo '</div> <!-- end .row -->  <div class="row">'; 
								}
								//else if( ($k+1) % $divisor == 0 ) $alphaomega = 'omega';
							}

							$img_size = agility_image_size( $post->ID , 640 );

						?>
						<div class="<?php echo $grid_columns_class . ' ' . $alphaomega; ?>"><?php 
							
							switch( $post->post_type ){

								case 'attachment':
									$_post = $post;
									$link;
									if ( empty( $_post ) || ( 'attachment' != $_post->post_type ) || ! $url = wp_get_attachment_url( $_post->ID ) ){
										$link = __( 'Missing Attachment', 'agility' );
									}
									else{

										$post_title = esc_attr( $_post->post_title );

										if ( $size && 'none' != $size )
											$link_text = wp_get_attachment_image( $_post->ID, $size, false, array( 'alt' => $_post->post_title, 'class' => "attachment-$size scale-with-grid" ) );
										else
											$link_text = '';

										if ( trim( $link_text ) == '' )
											$link_text = $_post->post_title;

										$link = '<a href="'.$url.'" data-rel="prettyPhoto" title="'.strip_tags( $_post->post_excerpt ).'">'.$link_text.'</a>';
									}

									echo $link;
									break;

								default: 
									agility_featured_image( $img_size , 'full-width', false , 'gallery' );
									break;
							}							
							if( $show_title == 'on' ): ?><h6><?php the_title(); ?></h6><?php endif; ?>
						</div>
					<?php $k++; endwhile; ?>
					</div> <!-- end .row -->
				</div> <!-- end .mosaic -->
	<?php
}


/**
 * Display social media icons based on options set in the Control Panel
 */
function agility_social_media_icons( $order = array() ){

	global $agilitySettings;

	$social_media = array(
		'twitter'	=>	'Twitter',
		'facebook'	=>	'Facebook',
		'forrst' 	=>	'Forrst',
		'dribbble'	=>	'Dribbble', 
		'vimeo'		=>	'Vimeo', 
		'youtube'	=>	'YouTube', 
		'linkedin'	=>	'LinkedIn',
		'pinterest'	=>	'Pinterest',
		'flickr'	=>	'Flickr',
		'tumblr'	=>	'Tumblr',
	);

	if( !empty( $order ) ){
		$_social_media = array();
		foreach( $order as $site_id ){
			$_social_media[$site_id] = $social_media[$site_id];
		}
		$social_media = $_social_media;
	}

	$social_media = apply_filters( 'agility_social_media_filter' , $social_media );

	foreach( $social_media as $site => $title ){
		if( $identifier = $agilitySettings->op( $site ) ){
			agility_social_media_icon( $site, $identifier, $title );	
		}
	}

	do_action( 'agility_social_media' );

}

/**
 * Print an individual social media icon
 */
function agility_social_media_icon( $site , $identifier, $title = '' ){

	$url = '';

	if( strpos( trim( $identifier ) , 'http' ) === 0 ){

		$url = trim( $identifier );
	}
	else{
		
		switch( $site ){

			case 'facebook':
				$url = 'http://www.facebook.com/'.$identifier;
				break;

			case 'twitter':
				$url = 'http://twitter.com/'.$identifier;
				break;

			case 'forrst':
				$url = 'https://forrst.com/people/'.$identifier;
				break;

			case 'dribbble':
				$url = 'http://dribbble.com/'.$identifier;
				break;

			case 'vimeo':
				$url = 'https://vimeo.com/'.$identifier;
				break;

			case 'youtube':
				$url = 'http://youtube.com/user/'.$identifier;
				break;

			case 'linkedin':
				$url = 'http://linkedin.com/in/'.$identifier;
				break;

			case 'pinterest':
				$url = 'http://pinterest.com/'.$identifier;
				break;

			case 'flickr':
				$url = 'http://www.flickr.com/photos/'.$identifier;
				break;

			case 'tumblr':
				$url = 'http://'.$identifier.'.tumblr.com';
				break;

		}
	}

	?><a href="<?php echo $url; ?>" target="_blank" class="social-media tooltip-container" title="<?php echo $title; 
		?>" ><span class="tooltip-anchor icon <?php echo $site; ?>-icon"></span><span class="tooltip"><?php echo $title; 
		?></span></a><?php

}


function agility_first_item_flag( $is_latest ){
	
	if( $is_latest || is_sticky() ): ?>
	
	<div class="latest-indicator">
		<?php if( is_sticky() ): ?>
			<?php _e( 'Sticky' , 'agility' ); ?>
		<?php else: ?>
			<?php _e( 'Latest' , 'agility' ); ?>
		<?php endif; ?>
	</div>

	<?php endif;

}
