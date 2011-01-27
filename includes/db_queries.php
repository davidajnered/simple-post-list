<?php

/**
 * This is where all the queries are made
 */
function spl_get_posts($type = 'recent_updated_post', $limit = 1) {
  global $wpdb;
  switch($type) {

    // Post
    case 'recent_commented_post':
      $query = get_common_query('post') . "
         WHERE post_type = 'post'
         AND post_status = 'publish'
         AND comment_approved = 1
         GROUP BY ID
         ORDER BY comment_date DESC
         LIMIT $limit;";
      $data = $wpdb->get_results($query);
      break;

    // Post
    case 'most_commented_post':
      $query = get_common_query('post') . "
         WHERE post_type = 'post'
         AND post_status = 'publish'
         AND comment_approved = 1
         GROUP BY ID
         ORDER BY comment_count DESC
         LIMIT $limit;";
      $data = $wpdb->get_results($query);
      break;

    // Post
    case 'recent_updated_post':
      $query = 
        $query = get_common_query('post') .
         "WHERE post_type = 'post'
         AND post_status = 'publish'
         GROUP BY ID
         ORDER BY post_date DESC
         LIMIT $limit;";
      $data = $wpdb->get_results($query);
      break;

    // Comments
    case 'recent_comments':
      $query = get_common_query('comment') .
         "WHERE c.comment_approved = 1
         ORDER BY c.comment_date DESC
         LIMIT $limit;";
      $data = $wpdb->get_results($query);
      break;

    // Blogs
    case 'recent_post_from_other_blogs':
      $main_blog_prefix = $wpdb->get_blog_prefix(BLOG_ID_CURRENT_SITE);
      $query = "SELECT count(blog_id) AS blogs FROM " . $main_blog_prefix . "blogs;";
      $nbr_blogs = $wpdb->get_results($query);
      $blogs = (int)$nbr_blogs[0]->blogs;
      for($i = 0; $i < $blogs; $i++) {
        // Setup blog specific tables
        $prefix = $main_blog_prefix;
        $prefix .= ($i == 0) ? '' : $i + 1 . '_';
        $post_table = $prefix . 'posts';
        $option_table = $prefix . 'options';
        $term_relation_table = $prefix . 'term_relationships';
        $term_tax_table = $prefix . 'term_taxonomy';
        $comments_table = $prefix . 'comments';
        $user_table = $main_blog_prefix . 'users';

        $query =
          "SELECT $post_table.ID AS id, post_title AS title, post_content AS content, post_excerpt AS excerpt, post_date AS date, post_status, guid AS url, term_id, count(comment_post_ID) as comments, comment_date AS comment_date, display_name AS author
           FROM $post_table
           LEFT JOIN ($term_relation_table, $term_tax_table, $comments_table, $user_table)
           ON (object_id = $post_table.ID AND $term_tax_table.term_taxonomy_id = $term_tax_table.term_taxonomy_id AND comment_post_ID = $post_table.ID AND post_author = $user_table.ID)
           WHERE post_type = 'post'
           AND post_status = 'publish'
           GROUP BY ID
           ORDER BY post_date DESC
           LIMIT $limit;";
        $post_data = $wpdb->get_results($query);
        // Option table is different and needs a separate query
        $query = "SELECT * FROM $option_table WHERE option_name = 'blogname';";
        $blog_options = $wpdb->get_results($query);

        // Store values
        foreach($post_data as $posts) {
          // Specify a query of add the fields you want to grab to the array in sanitize_option_data function call
          $blog_data = sanitize_option_data($blog_options, array('blogname'));
          $posts->blogname = $blog_data['blogname'];
          $data[] = $posts;
        }
      }
      break;

    // Fallback. Creating custom error instead of generating a PHP error later on...
    default:
      // Not tested so I'm not sure this works
      $error = new stdClass;
      $error->content = 'Error!';
      $data = array(array($error));
      break;

  }
  return $data;
}

/**
 *
 */
function get_common_query($type) {
  global $wpdb;
  switch($type) {

    case 'post':
      $select = "SELECT ID AS id, post_title AS title, post_content AS content, post_excerpt AS except, post_date AS date, post_status, guid AS post_url, term_id, comment_count AS comments, comment_date AS comment_date
      FROM {$wpdb->posts}
      LEFT JOIN ({$wpdb->term_relationships}, {$wpdb->term_taxonomy}, {$wpdb->comments})
      ON (object_id = id AND {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id AND comment_post_ID = id) ";
      break;

    case 'comment':
      $select = "SELECT comment_ID AS id, comment_post_ID AS post_id, comment_date as date, comment_content AS content, comment_author AS author, comment_author_url AS author_url, comment_author_email AS author_email, guid AS post_url, post_title, post_date
      FROM {$wpdb->comments} AS c
      LEFT JOIN ({$wpdb->users} AS u, {$wpdb->posts} AS p)
      ON (c.comment_author = u.user_login AND c.comment_post_ID = p.ID) ";
      break;

    case 'blog':
      break;
  }
  return $select;
}

/**
 * The results from the option table is quite ugly and needs to be beatifulized
 */
function sanitize_option_data($data, $fields = NULL) {
  $sanitized = array();
  foreach($data as $option) {
    if($fields != NULL &&  in_array($option->option_name, $fields)) {
      $sanitized[$option->option_name] = $option->option_value;
    } else {
      $sanitized[$option->option_name] = $option->option_value;
    }
  }
  return $sanitized;
}

/**
 * Get a list of available thumbnail sizes
 */
function spl_get_thumbnail_sizes() {
  global $wpdb;
  $data = $wpdb->get_results(
   "SELECT meta_value FROM {$wpdb->postmeta}
    WHERE post_id = (SELECT max(post_id) FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attachment_metadata');"
  );

  foreach($data as $object) {
    $data_array = unserialize($object->meta_value);
    if($data_array != FALSE && is_array($data_array)) {
      foreach($data_array['sizes'] as $key => $values) {
        $options[$key] = $key . ' [H:'.$values['height'].'px W:'.$values['width'].'px]';
      }
      ksort($options);
    } else {
      $options = array();
    }
  }
  return $options;
}