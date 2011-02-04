<?php

/**
 * This is where all the queries are made
 */
function spl_get_posts($type = 'recent_updated_post', $limit = 1, $hard_limit = FALSE) {
  global $wpdb;
  switch($type) {

    // Post
    case 'recent_commented_post':
      $query = get_common_query('post') . "WHERE post_type = 'post'
         AND post_status = 'publish'
         AND comment_approved = 1
         GROUP BY ID
         ORDER BY comment_date DESC
         LIMIT $limit;";
      $data = $wpdb->get_results($query);
      foreach($data AS $post) {
        $tags = spl_get_tags($post->id);
        $post->tags = $tags;
      }
      break;

    // Post
    case 'most_commented_post':
      $query = get_common_query('post') . "WHERE post_type = 'post'
        AND post_status = 'publish'
        AND comment_approved = 1
        GROUP BY ID
        ORDER BY comment_count DESC
        LIMIT $limit;";
      $data = $wpdb->get_results($query);
      foreach($data AS $post) {
        $tags = spl_get_tags($post->id);
        $post->tags = $tags;
      }
      break;

    // Post
    case 'recent_updated_post':
      $query = get_common_query('post') . "WHERE post_type = 'post' AND post_status = 'publish' GROUP BY ID ORDER BY post_date DESC LIMIT $limit;";
      $data = $wpdb->get_results($query);
      foreach($data AS $post) {
        $tags = spl_get_tags($post->id);
        $post->tags = $tags;
      }
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
      $wpdb_stash = clone $wpdb;
      $blogs = spl_get_all_blogs($ignore);
      if($blogs != NULL) {
        foreach($blogs as $blog) {
          $wpdb->blogid = $blog;
          $wpdb->set_prefix( $wpdb->base_prefix );
          $post_data = $wpdb->get_results($wpdb->prepare(
            "SELECT {$wpdb->posts}.ID AS id, post_title AS title, post_content AS content, post_excerpt AS excerpt, post_date AS date, post_status, guid AS post_url, term_id, count(comment_post_ID) as comments, comment_date AS comment_date, display_name AS author
             FROM {$wpdb->posts}
             LEFT JOIN ({$wpdb->term_relationships}, {$wpdb->term_taxonomy}, {$wpdb->comments}, {$wpdb->users})
             ON (
               {$wpdb->term_relationships}.object_id = {$wpdb->posts}.ID AND
               {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id AND
               {$wpdb->comments}.comment_post_ID = {$wpdb->posts}.ID AND
               {$wpdb->posts}.post_author = {$wpdb->users}.ID
             )
             WHERE post_type = 'post'
             AND post_status = 'publish'
             GROUP BY ID
             ORDER BY post_date DESC
             LIMIT $limit;"));
          // Option table is different and needs a separate query
          $fields = array('blogname', 'blogdescription', 'siteurl');
          $query = "SELECT * FROM $wpdb->options WHERE ";
          $i = 0;
          foreach($fields as $field) {
            $query .= ($i > 0) ? " OR " : "";
            $query .= "option_name = '$field'";
            $i++;
          }
          $query .= ";";
          $blog_options = $wpdb->get_results($query);
          // Store values
          foreach($post_data as $posts) {
            // Specify a query of add the fields you want to grab to the array in sanitize_option_data function call
            $blog_data = sanitize_option_data($blog_options, $fields);
            foreach($blog_data as $key => $value) {
              $posts->$key = $value;
            }
            $posts->blog_id = $blog;
            $data[] = $posts;
          }
          if($hard_limit && count($data) >= $limit) {
            break;
          }
        }
      } else {
        $data = NULL;
      }
      $wpdb = clone $wpdb_stash;
      break;

    default:
      $data = NULL;
      break;

  }
  return $data;
}

function spl_get_all_blogs() {
  global $wpdb;
  global $ignore;
  if(!is_array($ignore)) {
    $ignore = explode(',', $ignore);
    $ignore = $ignore[0] == '' ? FALSE : $ignore;
  }
  $blogs = NULL;
  $query = "SELECT blog_id FROM $wpdb->blogs";
  if($ignore != FALSE) {
    $i = 0;
    foreach($ignore as $ignored_blog_id) {
      $query .= ($i == 0) ? " WHERE " : " AND ";
      $query .= " blog_id != " . $ignored_blog_id;
      $i++;
    }
  }
  $query .= ";";
  foreach($wpdb->get_results($query) as $key => $value) {
    $blogs[] = $value->blog_id;
  }
  return $blogs;
}

/**
 *
 */
function get_common_query($type) {
  global $wpdb;
  switch($type) {

    case 'post':
      $select =
        "SELECT p.ID AS id,
          post_title AS title,
          post_content AS content,
          post_excerpt AS except,
          guid AS post_url,
          comment_count AS comments,
          comment_date AS comment_date,
          display_name AS author
        FROM {$wpdb->posts} AS p
        LEFT JOIN ({$wpdb->term_relationships} AS tr, {$wpdb->term_taxonomy} AS tt, {$wpdb->comments} AS c, {$wpdb->users} AS u)
        ON (
          tr.object_id = p.id 
          AND tr.term_taxonomy_id = tt.term_taxonomy_id
          AND c.comment_post_ID = p.id
          AND p.post_author = u.ID
        ) "; // Leave whitespace
      break;

    case 'comment':
      $select =
        "SELECT comment_ID AS id,
          comment_post_ID AS post_id,
          comment_date as date,
          comment_content AS content,
          comment_author AS author,
          comment_author_url AS author_url,
          comment_author_email AS author_email,
          guid AS post_url,
          post_title,
          post_date
        FROM {$wpdb->comments} AS c
        LEFT JOIN ({$wpdb->users} AS u, {$wpdb->posts} AS p)
        ON (c.comment_author = u.user_login AND c.comment_post_ID = p.ID) ";
      break;

    case 'blog':
      break;
  }
  return $select;
}

function spl_get_tags($id) {
  global $wpdb;
  $query =
   "SELECT t.name FROM {$wpdb->term_relationships} AS tr
    LEFT JOIN ({$wpdb->terms} AS t, {$wpdb->term_taxonomy} AS tt)
    ON (tr.term_taxonomy_id = t.term_id AND tt.term_taxonomy_id = tr.term_taxonomy_id)
    WHERE object_id = $id
    AND tt.taxonomy = 'post_tag'";
  $tags_array = $wpdb->get_results($query);
  foreach($tags_array as $tag) {
    $tags[] = $tag->name;
  }
  return $tags;
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
function spl_get_thumbnail_sizes($ignore) {
  global $wpdb;
  $options = array();
  $wpdb_stash = clone $wpdb;
  foreach(spl_get_all_blogs($ignore) as $blog) {
    $wpdb->blogid = $blog;
    $wpdb->set_prefix( $wpdb->base_prefix );
    $data = $wpdb->get_results($wpdb->prepare(
      "SELECT meta_value FROM {$wpdb->postmeta}
      WHERE post_id = (SELECT max(post_id) FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attachment_metadata');"
    ));

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
  }
  
  return $options;
}