<?php
class Cpo_Tweets extends WP_Widget {

	function Cpo_Tweets(){
		$widget_ops = array('description' => 'A&ntilde;ade una lista con tus &uacute;ltimos tweets.' );
		parent::WP_Widget(false, '&nbsp;'.__('CPO - Twitter Stream', 'cpotheme'), $widget_ops);      
	}
	
	function widget($args, $instance){  
		extract($args);
		$title = $instance['title'];
		$limit = $instance['limit']; if(!$limit) $limit = 5;
		$username = $instance['username'];
		$unique_id = $args['widget_id'];
		echo $before_widget;
		if($title) 
			echo $before_title.$title.$after_title; 
		else 
			echo $before_title.'&Uacute;ltimos Tweets'.$after_title;
		echo '<div class="tweet_wrapper">';
		echo '<div class="tweet_username"><strong><a href="http://twitter.com/'.$username.'">@'.$username.'</a></strong></div>';
		echo '<ul id="twitter_status_'.$unique_id.'"><li></li></ul>';
		echo '</div>';
		echo cpo_display_tweets($unique_id, $username, $limit);
		echo $after_widget;
   }

   function update($new_instance, $old_instance){                
       return $new_instance;
   }

   function form($instance){        
   
       $title = esc_attr($instance['title']);
       $limit = esc_attr($instance['limit']);
	   $username = esc_attr($instance['username']);
       ?>
       <p>
	   	   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'cpotheme'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
       </p>
       <p>
	   	   <label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username', 'cpotheme'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('username'); ?>"  value="<?php echo $username; ?>" class="widefat" id="<?php echo $this->get_field_id('username'); ?>" />
       </p>
       <p>
	   	   <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Number of Tweets', 'cpotheme'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('limit'); ?>"  value="<?php echo $limit; ?>" class="" size="3" id="<?php echo $this->get_field_id('limit'); ?>" />
       </p>
      <?php
   }
   
} 
register_widget('Cpo_Tweets'); 



// Latest Tweets
// Displays the latest tweets
function cpo_display_tweets($id, $username, $limit){

echo '<div id="tweet-container">';
echo '<input type="hidden" id="tweet-id" value="'.$id.'"/>';
echo '<input type="hidden" id="tweet-username" value="'.$username.'"/>';
echo '<input type="hidden" id="tweet-limit" value="'.$limit.'"/>';
echo '</div>';

} ?>