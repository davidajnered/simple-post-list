<?php print $args['before_widget']; ?>

<?php if($title != NULL) {
  print $args['before_title'] . $title . $args['after_title'];
} ?>

<?php $id = 0; foreach($data as $post) : ?>
  <li class="simple-post-list-post" id="simple-post-list-id-<?php print $id; ?>">
    <h3><?php print $post->post_title; ?></h3>
    <?php if($thumbnail == TRUE) : ?>
      <a href="<?php print $url; ?>"><?php print get_the_post_thumbnail($post->ID, $thumbnail_size); ?></a>
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
    <p>
      <?php print $content; ?>
      <a href="<?php print get_bloginfo('url') . '?p=' . $post->ID; ?>"><?php print $link; ?></a>
    </p>
  </li>
<?php $id++; endforeach; ?>

<?php print $args['after_widget']; ?>