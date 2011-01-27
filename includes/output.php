<?php $old_variables = array(); ?>
<?php print $args['before_widget']; ?>
  <?php print ($widget_title != NULL) ? $args['before_title'] . $widget_title . $args['after_title'] : ''; ?>
  <ol>
    <?php
      $id = 0;
      foreach($data_array as $data) :
        // We want to use the simplest variable names possible,
        // and to make sure they doesn't conflict with other variables,
        // we store old ones so we can restore them after the loop 
        foreach($data as $key => $value) {
          $old_variables[$key] = ${$key};
          if($key == 'content' || $key == 'excerpt') {
            $value = $this->spl_shorten($value);
          }
          ${$key} = $value;
        }
      ?>

      <li class="simple-post-list simple-post-list-<?php print $type; ?>" id="simple-post-list-id-<?php print $id; ?>">
        <?php include($inc); ?>
      </li>

      <?php
        // Restore the old variable values
        foreach($old_variables as $key => $value) {
          ${$key} = $value;
        }
      ?>
    <?php $id++; endforeach; ?>
  </ol>
<?php print $args['after_widget']; ?>