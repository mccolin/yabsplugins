<?php
/*
Plugin Name: YABS Beer List Widget
Plugin URI: http://yetanotherbeershow.com/
Description: A widget that displays beers listed in order of selectable metadata, such as score, ABV, type, etc.
Author: Colin McCloskey
Version: 0.9
Author URI: http://mccolin.com/
*/


/**
 * TopBeersWidget Class
 * The class controlling the sidebar widget for top beers display
 */
class Yabs_List_Widget extends WP_Widget {
  
  /** constructor */
  function Yabs_List_Widget() {
    parent::WP_Widget(false, $name = "YABS List");
  }
  
  /** actual widget content display */
  function widget($args, $instance) {
    extract( $args );
    $title = apply_filters('widget_title', $instance['title']);
    $sort_by = $instance['sort_by'];
    $num_to_show = $instance['num_to_show'];
    
    $posts = get_posts(array(
      'posts_per_page' => $num_to_show * 1,
      'meta_key' => $sort_by,   // 'yabs_beer_abv',
      'orderby'  => 'meta_value',
      'order'    => 'DESC',
    ));
    ?>
      <?php echo $before_widget; ?>
        <?php if ( $title ) echo $before_title . $title . $after_title; ?>
        <ol>
          <?php 
            foreach($posts as $post) :
            setup_postdata($post); 
          ?>
          <li>
            <a href="<?php echo get_permalink( $post->ID ); ?>"><?php echo get_the_title($post->ID); ?></a>
            <small> <?php echo get_post_meta($post->ID, $sort_by, true); ?></small>
          </li>
          <?php endforeach; ?>
        </ol>
        
      <?php echo $after_widget; ?>
    <?php        
  }
  
  /** widget updater code */
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['sort_by'] = strip_tags($new_instance['sort_by']);
    return $instance;
  }
  
  /** widget edit form */
  function form($instance) {
    $title = esc_attr($instance['title']);
    $sort_by = esc_attr($instance['sort_by']);
    $num_to_show = esc_attr($instance['num_to_show']);
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
      </label>
      <label for="<?php echo $this->get_field_id('sort_by'); ?>"><?php _e('Sort By:'); ?>
        <input class="widefat" id="<?php echo $this->get_field_id('sort_by'); ?>" name="<?php echo $this->get_field_name('sort_by'); ?>" type="text" value="<?php echo $sort_by; ?>" />
      </label>
      <label for="<?php echo $this->get_field_id('num_to_show'); ?>"><?php _e('Num to Show:'); ?>
        <input class="widefat" id="<?php echo $this->get_field_id('num_to_show'); ?>" name="<?php echo $this->get_field_name('num_to_show'); ?>" type="text" value="<?php echo $num_to_show; ?>" />
      </label>
      
      
    </p>
    <?php
  }
  
} // class TopBeersWidget

add_action('widgets_init', create_function('', 'return register_widget("Yabs_List_Widget");'));





