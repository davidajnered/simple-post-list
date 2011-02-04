<?php print $args['before_widget']; ?>
  <?php print ($widget_title != NULL) ? $args['before_title'] . $widget_title . $args['after_title'] : ''; ?>
  <ol>
    <?php if(file_exists($inc)) : ?>
      <?php $id = 0;
        $old_variables = array();
        if($data_array != NULL) :
          foreach($data_array as $data) :
            // We want to use the simplest variable names possible,
            // and to make sure they doesn't conflict with other variables,
            // we store old ones so we can restore them after the loop
            foreach($data as $key => $value) {
              $old_variables[$key] = ${$key};
              // Format content and excerpt to the user specified length
              if($key == 'content' || $key == 'excerpt') {
                if($paragraph) {
                  $value = $this->spl_paragraph($value);
                } else {
                  $value = $this->spl_shorten($value);
                }
              }
              ${$key} = $value;
            }
            if($blog_id) {
              // Probably not the most efficient solution
              switch_to_blog($blog_id);
            }
          ?>

          <li class="simple-post-list simple-post-list-<?php print $type; ?>" id="simple-post-list-id-<?php print $id; ?>">
            <?php include($inc); ?>
          </li>

          <?php
            restore_current_blog();
            // Restore the old variable values
            foreach($old_variables as $key => $value) {
              ${$key} = $value;
            }
            $id++; endforeach;
          ?>
      <?php else : ?>
        <li class="simple-post-list simple-post-list-<?php print $type; ?>" id="simple-post-list-id-<?php print $id; ?>">
          <p>There are no posts to list</p>
        </li>
      <?php endif; ?>
    <?php else : ?>
      <li class="simple-post-list simple-post-list-<?php print $type; ?>" id="simple-post-list-id-<?php print $id; ?>">
        <h2>Your template file does not exist.</h2>
        <p>Maybe it has been renamed? Please re-configure this widget.</p>
      </li>
    <?php endif; ?>
  </ol>
<?php print $args['after_widget']; ?>