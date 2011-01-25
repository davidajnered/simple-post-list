<?php print $args['before_widget']; ?>

<?php if($title != NULL) {
  print $args['before_title'] . $title . $args['after_title'];
} ?>

<ol>
  <?php $id = 0; foreach($data as $comment) : ?>
    <li class="simple-post-list-comment" id="simple-post-list-id-<?php print $id; ?>">
      <?php if(!empty($comment->comment_author_url)) : ?>
        <span class="spl-comment-author"><a href="<?php print $comment->comment_author_url; ?>"><?php print $comment->comment_author; ?></a></span>
      <?php else : ?>
        <span class="spl-comment-author"><?php print $comment->comment_author; ?></span>
      <?php endif; ?>
      
      <?php
        $content = $comment->comment_content;
        if($length <= -1) {
          $content = '';
        } else if (strlen($content) > $length) {
          if($length > 0) {
            $content = substr($content, 0, $length) . '&hellip; ';
          }
        }
      ?>
      <p class="spl-comment-content">
        <?php print $content; ?>
        
        <div class="spl-comment-meta">
          <a class="spl-commented-post-link" href="<?php print $comment->guid; ?>"><?php print $comment->post_title; ?></a>
          <span class="spl-comment-date"><?php print human_time_diff(strtotime($comment->comment_date), current_time('timestamp')) . ' ago'; ?></span>
        </div>
      </p>
    </li>
  <?php $id++; endforeach; ?>
</ol>

<?php print $args['after_widget']; ?>