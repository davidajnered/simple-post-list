<?php
error_log(var_export($post, TRUE));
$output = $args['before_widget'].$args['before_title'];

// Use custom title or post title
if($title != NULL) {
  $output .= $title;
}

$output .= '<h3>' . $post->post_title . '</h3>';

$output .= $args['after_title'];

// Show thumbnail
if($thumbnail == TRUE) {
  $output .= '<a href="' . $url . '">';
  $output .= get_the_post_thumbnail($post->ID, $thumbnail_size);
  $output .= '</a>';
}

// Use post content or post excerpt
if($data_to_use == 'excerpt') {
  $content = strip_tags($post->post_excerpt);
} else {
  $content = strip_tags($post->post_content);
}

// Show the specified length of the content
if($length <= -1) {
  $content = '';
} else if (strlen($content) > $length) {
  if($length > 0) {
    $content = substr($content, 0, $length) . '&hellip; ';
  }
}

$url = get_bloginfo('url');
$url .= '?p='.$post->ID;
$html_link = '<a href="' . $url . '">' . $link . '</a>';

// Link to post of category
$output .= '<p>' . $content . ' ' . $html_link . '</p>' . $args['after_widget'];

// Print
echo $output;