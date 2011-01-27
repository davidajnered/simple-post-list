<?php
/**
 *
 * Style Name: Default Post Template
 * Class: Simple Post List
 * Description: The default template used to display posts
 * Author: David Ajnered
 * Version: 1.0
 * Author URI: http://davidajnered.com/
 *
 * Template variables:
 * $id
 * $title
 * $content
 * $date
 * $post_status
 * $url
 * $term_id
 * $comments
 * $comment_date
 * $has_thumbnail
 * $thumbnail
 */
?>

<h3><?php print $title; ?></h3>
<?php if($has_thumbnail == TRUE) : ?>
  <a href="<?php print $guid; ?>"><?php print get_the_post_thumbnail($ID, $thumbnail_size); ?></a>
<?php endif; ?>
<p>
  <?php print $content; ?>
  <a href="<?php print $url ?>"><?php print $link; ?></a>
</p>
      
