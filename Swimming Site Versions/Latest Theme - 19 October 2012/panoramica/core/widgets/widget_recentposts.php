<?php

class Cpotheme_Widget_RecentPosts extends WP_Widget{
	
	function Cpotheme_Widget_RecentPosts(){
		$widget_ops = array('classname' => 'cpotheme_recent_posts', 'description' => __('Displays the most recent posts with date and thumbnail.', 'cpotheme'));
		$this->WP_Widget('cpotheme-recent-posts', __('CPO - Recent Posts', 'cpotheme'), $widget_ops);
		$this->alt_option_name = 'cpotheme_recent_posts';
		add_action('save_post', array(&$this, 'flush_widget_cache'));
		add_action('deleted_post', array(&$this, 'flush_widget_cache'));
		add_action('switch_theme', array(&$this, 'flush_widget_cache'));
	}

	function widget($args, $instance){
		$cache = wp_cache_get('cpotheme_recent_posts', 'widget');
		if(!is_array($cache)) $cache = array();
		
		if(isset($cache[$args['widget_id']])){
			echo $cache[$args['widget_id']];
			return;
		}
		ob_start();
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$number = $instance['number'];
		if(!is_numeric($number)) $number = 5; elseif($number < 1) $number = 1; elseif($number > 99) $number = 99;
		
		$recent_posts = new WP_Query(array('posts_per_page' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1));
		if($recent_posts->have_posts()):
		echo $before_widget;
		if($title != '') echo $before_title.$title.$after_title; ?>
		
		<div class="recent_posts">
			<?php while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
			<div class="item">
				<a class="thumbnail" href="<?php the_permalink(); ?>" >
					<?php the_post_thumbnail('thumbnail', array('title' => '')); ?>
				</a>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				<div class="meta"><?php the_date(); ?></div>
			</div>
			<?php endwhile; ?>
		</div>
		<?php echo $after_widget;
		wp_reset_query();
		endif;
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('cpotheme_recent_posts', $cache, 'widget');
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();
		$alloptions = wp_cache_get('alloptions', 'options');
		if(isset($alloptions['cpotheme_recent_entries']))
		delete_option('cpotheme_recent_entries');
		return $instance;
	}

	function flush_widget_cache(){
		wp_cache_delete('cpotheme_recent_posts', 'widget');
	}

	function form($instance){
		$instance = wp_parse_args((array) $instance, array('title' => ''));
		$title = esc_attr($instance['title']);
		if(!isset($instance['number']) || !$number = (int)$instance['number']) $number = 5; ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'cpotheme'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of Posts', 'cpotheme'); ?></label><br/>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
        </p>
	<?php }
}
register_widget('Cpotheme_Widget_RecentPosts'); ?>