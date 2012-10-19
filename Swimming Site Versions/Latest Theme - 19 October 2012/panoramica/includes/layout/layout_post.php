<?php 
//Displays a formatted byline for the current post
function cpotheme_post_byline(){
    printf(__('Posted on %1$s', 'cpotheme'), get_the_date());
    $author_alt = sprintf(esc_attr__('View all posts by %s', 'cpotheme'), get_the_author());
    $author = sprintf('<a href="%1$s" title="%2$s">%3$s</a>', get_author_posts_url(get_the_author_meta('ID')), $author_alt, get_the_author());
    printf(__(' by %1$s', 'cpotheme'), $author);

    //Add category
    if(!is_page()) printf(__(' in %1$s', 'cpotheme'), get_the_category_list(', '));
}

//Displays a shortened byline with only the author and categories
function cpotheme_post_shortbyline(){
    $author_alt = sprintf(esc_attr__('View all posts by %s', 'cpotheme'), get_the_author());
    $author = sprintf('<a href="%1$s" title="%2$s">%3$s</a>', get_author_posts_url(get_the_author_meta('ID')), $author_alt, get_the_author());
    printf(__('By %1$s', 'cpotheme'), $author);

    //Add category
    if(!is_page()) printf(__(' in %1$s', 'cpotheme'), get_the_category_list(', '));
}


//Displays tag list for current post
function cpotheme_post_tags(){
	$tag_list = get_the_tag_list('', ', ');
	$posted_in = __('Tagged as %1$s', 'cpotheme');
	if($tag_list != '')printf($posted_in, $tag_list);
}

//Displays a description of the author of the current post
function cpotheme_post_authorbio(){ ?>
	<div id="entry_author-info" class="author_bio">
		<div id="author-avatar" class="avatar">
			<?php echo get_avatar(get_the_author_meta('user_email'), apply_filters('cpotheme_author_bio_avatar_size', 60)); ?>
		</div>
		<div id="author-description" class="description">
			<h4><?php printf(esc_attr__('Posted by %s', 'cpotheme'), get_the_author()); ?></h4>
			<?php the_author_meta('description'); ?>
			<div id="author-link" class="link">
				<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
					<?php printf(__('View all posts by %s', 'cpotheme'), get_the_author()); ?>
				</a>
			</div>
		</div>
	</div> <?php
}

// Adds More Info links to previews
function cpotheme_post_readmore(){
	return '';
}


//Displays Read More link on excerpts with cutoff
add_filter('excerpt_more', 'cpotheme_auto_excerpt_more');
function cpotheme_auto_excerpt_more($more){
	return ' &hellip;'.cpotheme_post_readmore();
}


//Displays Read More link on excerpts
add_filter('get_the_excerpt', 'cpotheme_custom_excerpt_more');
function cpotheme_custom_excerpt_more($output){
	if(has_excerpt() && !is_attachment())
		$output .= cpotheme_post_readmore();
	return $output;
}

// Limits text preview lengths to a certain size
add_filter('excerpt_length', 'cpotheme_post_excerpt_length');
function cpotheme_post_excerpt_length($length){
	return 25;
}

//Paginates the post content
function cpotheme_post_pagination(){
	$query = $GLOBALS['wp_query'];
	$posts_per_page = 7;
	$current_page = max(1, absint($query->get('paged')));
	$total_pages = max(1, absint($query->max_num_pages));

	if(1 == $total_pages) return;

	$request = $query->request;
	$numposts = $query->found_posts;

	$pages_to_show = 8;
	$larger_page_to_show = 10;
	$larger_page_multiple = 2;
	$pages_to_show_minus_1 = $pages_to_show - 1;
	$half_page_start = floor( $pages_to_show_minus_1/2 );
	$half_page_end = ceil( $pages_to_show_minus_1/2 );
	$start_page = $current_page - $half_page_start;

	$end_page = $current_page + $half_page_end;
	
	if(($end_page - $start_page) != $pages_to_show_minus_1)
		$end_page = $start_page + $pages_to_show_minus_1;

	if($end_page > $total_pages){
		$start_page = $total_pages - $pages_to_show_minus_1;
		$end_page = $total_pages;
	}

	if($start_page < 1)
		$start_page = 1;

	$out = '';

	//First Page Link
	if($current_page > 1){
		$out .= '<a class="page first_page" href="'.esc_url(get_pagenum_link(1)).'">'.__('First Page', 'cpotheme').'</a>';
	}

	//Previous Page Link
	//$out .= get_previous_posts_link("Recientes");

	//Show each page
	foreach(range($start_page, $end_page) as $i){
		if($i == $current_page){
			$out .= "<span>$i</span>";
		}else{ 
			$out .= '<a class="page" href="'.esc_url(get_pagenum_link($i)).'">'.$i.'</a>';
		}
	}
	
	//Next Page Link
	//$out .= get_next_posts_link("Antiguos");

	//Last Page Link
	if($current_page < $total_pages){
		$out .= '<a class="page last_page" href="'.esc_url(get_pagenum_link($total_pages)).'">'.__('Last Page', 'cpotheme').'</a>';
	}
	
	$out = '<div id="pagination">'.$out.'</div>';

	echo $out;
}

?>