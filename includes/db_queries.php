<?php

function spl_get_posts($type = 'recent_update', $limit = 1) {
  global $wpdb;
  switch($type) {

    case 'recent_comment':
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

    case 'most_commented':
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

    case 'recent_update':
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
  }
  return $data;
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