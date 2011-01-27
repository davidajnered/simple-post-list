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
 * $post_date
 * $post_status
 * $post_url
 * $author
 * $tags - array with tags
 * $comments
 * $comment_date
 * $has_thumbnail
 * $thumbnail
 */
?>

<h3><a href="<?php print $post_url; ?>"><?php print $title; ?></a></h3>
<?php if($has_thumbnail == TRUE) : ?>
  <a href="<?php print $post_url; ?>"><?php print get_the_post_thumbnail($id, $thumbnail); ?></a>
<?php endif; ?>

<div class="content">
  <?php print $content; ?>
</div>

<div class="post-meta">
  <div class="tags">
    <?php foreach($tags as $tag) : ?>
      <span class="tag"><?php print $tag; ?></span>
    <?php endforeach; ?>
  </div>

  <div class="comment"><?php print $comments; ?></div>
  <div class="comment"><?php print $comment_date; ?></div>
  <div class="author"><?php print $author; ?></div>
  <a href="<?php print $post_url; ?>"><?php print $link; ?></a>
</div>
      
