<?php
/**
 *
 * Style Name: Default Blog Template
 * Class: Simple Post List
 * Description: The default template used to display blog posts
 * Author: David Ajnered
 * Version: 1.0
 * Author URI: http://davidajnered.com/
 *
 */
?>

<h3><?php print $title; ?></h3>
<?php if($has_thumbnail == TRUE) : ?>
  <a href="<?php print $guid; ?>"><?php print get_the_post_thumbnail($ID, $thumbnail_size); ?></a>
<?php endif; ?>

  <p>
    <?php print $this->spl_shorten($content, $length); ?>
    <a href="<?php print $guid ?>"><?php print $link; ?></a>
  </p>
      
; ?>