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

<?php print $args['before_widget']; ?>

<?php if($title != NULL) {
  print $args['before_title'] . $title . $args['after_title'];
} ?>

<ol>
  <?php $id = 0; foreach($data as $post) : ?>
    <li class="simple-post-list simple-post-list-blog blog-<?php print strtolower(str_replace(' ', '-', $post->blogname)); ?>" id="simple-post-list-id-<?php print $id; ?>">
      <h4><?php print $post->post_title; ?></h4>
      <?php if($has_thumbnail == TRUE) : ?>
        <a href="<?php print $post->guid; ?>"><?php print get_the_post_thumbnail($post->ID, $thumbnail_size); ?></a>
      <?php endif; ?>

      <?php
        $content = ($data_to_use == 'excerpt') ? strip_tags($post->post_excerpt) : strip_tags($post->post_content);
        // Show the specified length of the content
        if($length <= -1) {
          $content = '';
        } else if (strlen($content) > $length) {
          if($length > 0) {
            $content = substr($content, 0, $length) . '&hellip; ';
          }
        }
      ?>
      <p class="spl-blog-comment">
        <?php print $content; ?>
        <a href="<?php print get_bloginfo('url') . '?p=' . $post->ID; ?>"><?php print $link; ?></a>

        <div class="spl-blog-meta">
          <a class="spl-blog-post-link" href="<?php print $post->guid; ?>"><?php print $post->post_title; ?></a> written by
          <span class="spl-blog-author"><?php print $post->author; ?></span>
          <span class="spl-blog-date"><?php print human_time_diff(strtotime($post->post_date), current_time('timestamp')) . ' ago'; ?></span> in
          <span class="spl-blog-blogname"><?php print $post->blogname; ?></span>
        </div>
      </p>
    </li>
  <?php $id++; endforeach; ?>
</ol>

<?php print $args['after_widget']; ?>