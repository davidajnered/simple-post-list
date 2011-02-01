<?php
/*
 * Plugin Name: Simple Post List
 * Version: 1.0
 * Plugin URI: http://www.davidajnered.com/
 * Description: Simple Post List is a widget that lists your posts.
 * Author: David Ajnered
 */

class simple_post_list extends WP_Widget {
  private $length;

  /**
  * Init method
  */
  function simple_post_list() {
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
      $widget_title         = $instance['widget_title'];
      $this->length         = (int)$instance['length'];
      $selection            = $instance['selection'];
      $has_thumbnail        = $instance['has_thumbnail'];
      $thumbnail_size       = $instance['thumbnail_size'];
      $data_to_use          = $instance['data_to_use'];
      $paragraph            = $instance['paragraph'];
      $link                 = $instance['link'];
      $template             = $instance['template'];
      $limit                = $instance['limit'];
      // Set default limit
      $limit = !is_int($limit) ? (int)$limit : $limit;
      $limit = $limit == 0 ? 1 : $limit;

      include_once('includes/db_queries.php');
      if(!empty($selection)) {
        $ex = explode(':', $selection);
        $type = $ex[0];
        $selection = $ex[1];
        $data_array = spl_get_posts($selection, $limit);
        $inc = $template ? $template : WP_PLUGIN_DIR . '/simple-post-list/' . 'templates/spl_' . $type . '_default_template.php';
      }
      include('includes/output.php');
    }
  }

  /**
   *
   */
  function spl_get_themes() {
    $path = ABSPATH . '/wp-content/themes/' . get_template();
    $template_files = array();
  	if(is_dir($path)) {
  	  if ($folder = opendir($path)) {
        while(($file = readdir($folder)) !== FALSE) {
          if(strpos($file, 'spl_post_') !== FALSE ||
            strpos($file, 'spl_comment_') !== FALSE ||
            strpos($file, 'spl_blog_') !== FALSE) {
            $file_path = $path . '/' . $file;
            $template = $this->spl_fetch_template($file_path);
            $template['Path'] = $file_path;
            if(!empty($template['Path']) && !empty($template['Name'])) {
              $template_files[] = $template;
            }
          }
        }
      }
    }
  	return $template_files;
  }

  /**
   *
   */
  function spl_fetch_template($file = NULL) {
  	$default_headers = array(
  		'Name'          => 'Style Name',
  		'Class'         => 'Class',
  		'Description'   => 'Description',
  		'Version'       => 'Version',
  		'Author'        => 'Author',
  		'AuthorURI'     => 'Author URI',
  	);
  	$fp = fopen($file, 'r');
  	$file_data = fread($fp, 8192);
  	fclose($fp);
  	
  	foreach($default_headers as $field => $regex) {
  		preg_match('/^[ \t\/*#]*' . preg_quote($regex, '/') . ':(.*)$/mi', $file_data, ${$field});
  		if (!empty(${$field})) {
  			${$field} = _cleanup_header_comment(${$field}[1]);
  		} else {
  			${$field} = '';
			}
  	}
  	$file_data = compact(array_keys($default_headers));
  	return $file_data;
  }

  /**
   *
   */
  function spl_shorten($content) {
    if($this->length <= -1) {
      $content = '';
    }
    else if (strlen($content) > $this->length) {
      if($this->length > 0) {
        $content = substr($content, 0, $this->length) . '&hellip; ';
      }
    }
    return $content;
  }

 /**
  *
  */
  function spl_paragraph($content) {
    $start = strpos($content, '<p>');
    $end = strpos($content, '</p>', $start);
    $paragraph = substr($content, $start, $end - $start + 4);
    return $paragraph;
  }

 /**
  * Saves the widget settings
  */
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['widget_title']   = strip_tags(stripslashes($new_instance['widget_title']));
    $instance['selection']      = strip_tags(stripslashes($new_instance['selection']));
    $instance['limit']          = strip_tags(stripslashes($new_instance['limit']));
    $instance['has_thumbnail']  = strip_tags(stripslashes($new_instance['has_thumbnail'])) != 'checked' ? FALSE : TRUE;
    $instance['thumbnail_size'] = strip_tags(stripslashes($new_instance['thumbnail_size']));
    $instance['data_to_use']    = strip_tags(stripslashes($new_instance['data_to_use']));
    $instance['paragraph']      = strip_tags(stripslashes($new_instance['paragraph'])) != 'checked' ? FALSE : TRUE;
    $instance['length']         = strip_tags(stripslashes($new_instance['length']));
    $instance['link']           = strip_tags(stripslashes($new_instance['link']));
    $instance['link_to']        = strip_tags(stripslashes($new_instance['link_to']));
    $instance['template']       = strip_tags(stripslashes($new_instance['template']));
    return $instance;
  }

 /**
  * GUI for backend
  */
  function form($instance) {
    $widget_title   = htmlspecialchars($instance['widget_title']);
    $selection      = htmlspecialchars($instance['selection']);
    $limit          = htmlspecialchars($instance['limit']);
    $has_thumbnail  = htmlspecialchars($instance['has_thumbnail']);
    $thumbnail_size = htmlspecialchars($instance['thumbnail_size']);
    $data_to_use    = htmlspecialchars($instance['data_to_use']);
    $paragraph      = htmlspecialchars($instance['paragraph']);
    $length         = htmlspecialchars($instance['length']);
    $link           = htmlspecialchars($instance['link']);
    $link_to        = htmlspecialchars($instance['link_to']);

    $template_files = $this->spl_get_themes();
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