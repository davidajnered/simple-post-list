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
  private $ignore;

 /**
  * Init method
  */
  function simple_post_list() {
    $widget_ops = array(
      'classname' => 'simple_post_list',
      'description' => __("Create a list with posts")
    );
    $control_ops = array('width' => 100, 'height' => 100);
    $this->WP_Widget('simple_post_list', __('Simple Post List'), $widget_ops, $control_ops);
  }

 /**
  * Settings function that sets all active fields used by the widget
  *
  * @return
  *   array with all fields
  */
  function fields() {
    $fields = array(
      'widget_title',
      'length',
      'selection',
      'has_thumbnail',
      'thumbnail_size',
      'data_to_use',
      'paragraph',
      'posts_per_blog',
      'link',
      'template',
      'limit',
      'ignore',
    );
    return $fields;
  }

 /**
  * Displays the widget
  * @param $args
  * @param $instance
  */
  function widget($args, $instance) {
    if(!empty($instance)) {
      foreach($this->fields() as $field) {
        ${$field} = $instance[$field];
      }
      $this->length = (int)$length;
      $this->ignore = $ignore;

      // Set default limit
      $limit = !is_int($limit) ? (int)$limit : $limit;
      $limit = $limit == 0 ? 1 : $limit;

      include_once('includes/db_queries.php');
      set_ignore($ignore);
      if(!empty($selection)) {
        $ex = explode(':', $selection);
        $type = $ex[0];
        $selection = $ex[1];
        $data_array = spl_get_posts($selection, $limit, $posts_per_blog);
        $inc = $template ? ABSPATH . $template : ABSPATH . '/plugins/simple-post-list/templates/spl_' . $type . '_default_template.php';
      }
      include('includes/output.php');
    }
  }

  /**
   * Finds all available template files
   *
   * @return
   *   all available template files
   */
  function spl_get_template_files() {
    $relpath = 'wp-content/themes/' . get_template();
    $path = ABSPATH . $relpath;
    $template_files = array();
    if(is_dir($path)) {
      if ($folder = opendir($path)) {
        while(($file = readdir($folder)) !== FALSE) {
          if(strpos($file, 'spl_post_') !== FALSE ||
            strpos($file, 'spl_comment_') !== FALSE ||
            strpos($file, 'spl_blog_') !== FALSE) {
            $file_path = $relpath . '/' . $file;
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
   * Reads the comments in the template file to get the template information
   *
   * @param $file
   *   An active template file
   *
   * @return
   *   Array with template file information
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
    $fp = fopen(ABSPATH . $file, 'r');
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
   * Shortens the content or excerpt to the user specified length
   *
   * @param $content
   *   The post content or excerpt
   *
   * @return
   *   Shorter content
   */
  function spl_shorten($content) {
    $content = strip_tags($content);
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
  * Tries to find the first paragraph (<p>) of the post content or excerpt.
  * If there are no <p>-tags if falls back on spl_shorten instead
  *
  * @param $content
  *   The post content or except
  *
  * @return
  *   First paragraph if one exists, else whatever spl_shorten returns
  */
  function spl_paragraph($content) {
    $start = strpos($content, '<p>');
    $end = strpos($content, '</p>', $start);
    $paragraph = substr($content, $start, $end - $start + 4);
    if($start == FALSE || $end == FALSE) {
      $paragraph = $this->spl_shorten($content);
    }
    return strip_tags($paragraph);
  }

 /**
  * Saves the widget settings
  *
  * @param $new_instance
  * @param $old_instance
  */
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $special_fields = array('has_thumbnail', 'paragraph', 'posts_per_blog');
    foreach($this->fields() as $field) {
      $instance[$field] = strip_tags(stripslashes($new_instance[$field]));
      if(in_array($field, $special_fields)) {
        $instance[$field] = $instance[$field] != 'checked' ? FALSE : TRUE;
      }
    }

    // Set global variables
    $this->length         = (int)$length;
    $this->ignore         = $ignore;

    return $instance;
  }

 /**
  * GUI for backend
  *
  * @param $instance
  */
  function form($instance) {
    foreach($this->fields() as $field) {
      ${$field} = htmlspecialchars($instance[$field]);
    }

    $template_files = $this->spl_get_template_files();
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