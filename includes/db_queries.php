<?php

function spl_get_posts($type = 'recent_updated_post', $limit = 1) {
  global $wpdb;
  switch($type) {

    case 'recent_commented_post':
      $query =
        "SELECT ID, post_title, post_content, post_excerpt, post_date, post_status, guid, term_id, count(comment_post_ID) as nbr_comments, max(comment_date) as comment_date
         FROM {$wpdb->posts}
         LEFT JOIN {$wpdb->term_relationships}
         ON object_id = ID
         LEFT JOIN {$wpdb->term_taxonomy}
         ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
         LEFT JOIN {$wpdb->comments}
         ON comment_post_ID = ID
         WHERE post_type = 'post'
         AND post_status = 'publish'
         AND comment_approved = 1
         GROUP BY ID
         ORDER BY comment_date DESC
         LIMIT $limit;";
      $data = $wpdb->get_results($query);
      break;

    case 'most_commented_post':
      $query =
        "SELECT ID, post_title, post_content, post_excerpt, post_date, post_status, guid, term_id, comment_count, comment_date
         FROM {$wpdb->posts}
         LEFT JOIN {$wpdb->term_relationships}
         ON object_id = ID
         LEFT JOIN {$wpdb->term_taxonomy}
         ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
         LEFT JOIN {$wpdb->comments}
         ON comment_post_ID = ID
         WHERE post_type = 'post'
         AND post_status = 'publish'
         AND comment_approved = 1
         GROUP BY ID
         ORDER BY comment_count DESC
         LIMIT $limit;";
      $data = $wpdb->get_results($query);
      break;

    case 'recent_updated_post':
      $query = 
        "SELECT ID, post_title, post_content, post_excerpt, max(post_date), post_status, guid, term_id, count(comment_post_ID) as nbr_comments, comment_date
         FROM {$wpdb->posts}
         LEFT JOIN {$wpdb->term_relationships}
         ON object_id = ID
         LEFT JOIN {$wpdb->term_taxonomy}
         ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
         LEFT JOIN {$wpdb->comments}
         ON comment_post_ID = ID
         WHERE post_type = 'post'
         AND post_status = 'publish'
         GROUP BY ID
         ORDER BY post_date DESC
         LIMIT $limit;";
      $data = $wpdb->get_results($query);
      break;
      
    case 'recent_comments':
      // Fixa!!!!!!!!!!!!!!!!!!!!!!!!!!! ---------------------------- ____________________________
      $query = 
        "SELECT * FROM {$wpdb->comments} AS c
         LEFT JOIN {$wpdb->users} AS u ON c.comment_author = u.user_login
         LEFT JOIN {$wpdb->posts} AS p ON c.comment_post_ID = p.ID
         WHERE c.comment_approved = 1
         ORDER BY c.comment_date DESC
         LIMIT $limit;";
      $data = $wpdb->get_results($query);
      break;
      
    case 'recent_post_from_other_blogs':
      $main_blog_prefix = $wpdb->get_blog_prefix(BLOG_ID_CURRENT_SITE);
      $query = "SELECT count(blog_id) AS blogs FROM " . $main_blog_prefix . "blogs;";
      $nbr_blogs = $wpdb->get_results($query);
      $blogs = (int)$nbr_blogs[0]->blogs;
      for($i = 0; $i < $blogs; $i++) {
        $prefix = $main_blog_prefix;
        $prefix .= ($i == 0) ? '' : $i + 1 . '_';
        $post_table = $prefix . 'posts';
        $option_table = $prefix . 'options';
        $term_relation_table = $prefix . 'term_relationships';
        $term_tax_table = $prefix . 'term_taxonomy';
        $comments_table = $prefix . 'comments';
        $user_table = $main_blog_prefix . 'users';

        $query =
          "SELECT $post_table.ID, post_title, post_content, post_excerpt, post_date, post_status, guid, term_id, count(comment_post_ID) as nbr_comments, comment_date, display_name AS author
           FROM $post_table
           LEFT JOIN $term_relation_table
           ON object_id = ID
           LEFT JOIN $term_tax_table
           ON $term_tax_table.term_taxonomy_id = $term_tax_table.term_taxonomy_id
           LEFT JOIN $comments_table
           ON comment_post_ID = ID
           LEFT JOIN $user_table
           ON post_author = $user_table.ID
           WHERE post_type = 'post'
           AND post_status = 'publish'
           GROUP BY ID
           ORDER BY post_date DESC
           LIMIT $limit;";
        $post_data = $wpdb->get_results($query);

        // Specify a query of add the fields you want to grab to the array in sanitize_option_data function call
        $query = "SELECT * FROM $option_table WHERE option_name = 'blogname';";
        $blog_data = sanitize_option_data($wpdb->get_results($query), array('blogname'));
        $post_data[0]->blogname = $blog_data['blogname'];
        $data[] = $post_data[0];
      }
      break;

    default:
      $data = array('', '');
      break;

  }
  return $data;
}

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