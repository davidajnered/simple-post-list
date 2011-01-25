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
 * Template Variables
 * $ID, $title, $content, $excerpt, $date, $url, $comments, $comment_date
 */
?>

<?php error_log(var_export($ID, TRUE)); ?>
<?php error_log(var_export($title, TRUE)); ?>
<?php error_log(var_export($content, TRUE)); ?>
<?php error_log(var_export($excerpt, TRUE)); ?>
<?php error_log(var_export($date, TRUE)); ?>
<?php error_log(var_export($url, TRUE)); ?>
<?php error_log(var_export($comments, TRUE)); ?>
<?php error_log(var_export($comment_date, TRUE)); ?>
<?php error_log(var_export($has_thumbnail, TRUE));?>
<?php error_log(var_export($thumbnail_size, TRUE));?>

<h3><?php print $title; ?></h3>
<?php if($has_thumbnail == TRUE) : ?>
  <a href="<?php print $guid; ?>"><?php print get_the_post_thumbnail($ID, $thumbnail_size); ?></a>
<?php endif; ?>

  <p>
    <?php print $this->spl_shorten($content, $length); ?>
    <a href="<?php print $guid ?>"><?php print $link; ?></a>
  </p>
      
