<?php
/**
 *
 * Style Name: Default Comment Template
 * Class: Simple Post List
 * Description: The default template used to display comments
 * Author: David Ajnered
 * Version: 1.0
 * Author URI: http://davidajnered.com/
 *
 * Template variables:
 * $id - The comment ID
 * $date
 * $content
 * $author
 * $author_url
 * $author_email
 * $post_id
 * $post_url
 * $post_title
 * $post_date
 * $has_thumbnail
 * $thumbnail
 */
?>

<?php if(!empty($author_url)) : ?>
  <span class="spl-comment-author"><a href="<?php print $author_url; ?>"><?php print $author; ?></a></span>
<?php else : ?>
  <span class="spl-comment-author"><?php print $author; ?></span>
<?php endif; ?>

<p class="spl-comment-content">
  <?php print $content; ?>

  <div class="spl-comment-meta">
    <a class="spl-commented-post-link" href="<?php print $post_url; ?>"><?php print $post_title; ?></a>
    <span class="spl-comment-date"><?php print human_time_diff(strtotime($date), current_time('timestamp')) . ' ago'; ?></span>
  </div>
</p>