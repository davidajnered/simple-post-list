<?php
/*
 * Plugin Name: Simple Post List
 * Version: 1.0
 * Plugin URI: http://www.davidajnered.com/
 * Description: Simple Post List is a widget that lists your posts.
 * Author: David Ajnered
 */

class simple_post_list extends WP_Widget {

  /**
  * Init method
  */
  function simple_post_list(){
    $widget_ops = array('classname' => 'simple_post_list',
                        'description' => __("Create a list with posts"));

    $control_ops = array('width' => 100, 'height' => 100);
    $this->WP_Widget('simple_post_list', __('Simple Post List'), $widget_ops, $control_ops);
  }

 /**
  * Displays the widget
  */
  function widget($args, $instance) {
    if(!empty($instance)) {
      // Variables
      $title = $instance['title'];
      $length = (int)$instance['length'];
      $selection = $instance['selection'];
      $limit = $instance['limit'];
      $thumbnail = $instance['thumbnail'];
      $thumbnail_size = $instance['thumbnail_size'];
      $data_to_use = $instance['data_to_use'];
      $link = $instance['link'];

      include_once('includes/db_queries.php');
      if(!empty($selection)) {
        $limit = !is_int($limit) ? (int)$limit : $limit;
        $limit = $limit == 0 ? 1 : $limit;
        $data = spl_get_posts($selection, $limit);
        //Print to view
        include('includes/view.php');
      } else {
        if(!$data) {
          $title = "Simple Post List";
          $length = 100;
          $data = (object)array(
            'post_title' => 'Error!',
            'post_content' => 'This widget needs configuration',
          );
        }
      }
    }
  }

 /**
  * Saves the widget settings
  */
  function update($new_instance, $old_instance) {
    $thumb = strip_tags(stripslashes($new_instance['thumbnail']));
    $instance = $old_instance;
    $instance['title'] = strip_tags(stripslashes($new_instance['title']));
    $instance['selection'] = strip_tags(stripslashes($new_instance['selection']));
    $instance['limit'] = strip_tags(stripslashes($new_instance['limit']));
    $instance['thumbnail'] = $thumb != 'checked' ? FALSE : TRUE;
    $instance['thumbnail_size'] = strip_tags(stripslashes($new_instance['thumbnail_size']));
    $instance['data_to_use'] = strip_tags(stripslashes($new_instance['data_to_use']));
    $instance['length'] = strip_tags(stripslashes($new_instance['length']));
    $instance['link'] = strip_tags(stripslashes($new_instance['link']));
    $instance['link_to'] = strip_tags(stripslashes($new_instance['link_to']));
    return $instance;
  }

 /**
  * GUI for backend
  */
  function form($instance) {
    $title = htmlspecialchars($instance['title']);
    $selection = htmlspecialchars($instance['selection']);
    $limit = htmlspecialchars($instance['limit']);
    $thumbnail = htmlspecialchars($instance['thumbnail']);
    $thumbnail_size = htmlspecialchars($instance['thumbnail_size']);
    $data_to_use = htmlspecialchars($instance['data_to_use']);
    $length = htmlspecialchars($instance['length']);
    $link = htmlspecialchars($instance['link']);
    $link_to = htmlspecialchars($instance['link_to']);

    /* Print interface */
    include('includes/interface.php');
  }

} /* End of class */

/**
 * Register Widget
 */
function simple_post_list_init() {
  register_widget('simple_post_list');
}
add_action('widgets_init', 'simple_post_list_init');

/**
 * Add CSS and JS to head
 */
function simple_post_list_head() {
  echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('url').'/wp-content/plugins/simple-post-list/css/simple-post-list.css" />';
  echo '<script type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/simple-post-list/js/simple-post-list.js"></script>';
}
add_action('admin_head', 'simple_post_list_head');

?>