<?php include_once('db_queries.php'); ?>

<div class="simple-post-list">
  <p>
    <label for="<?php echo $this->get_field_name('widget_title'); ?>"><?php echo __('Title:') ?></label><br>
    <input id="<?php echo $this->get_field_id('widget_title') ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" value="<?php echo $widget_title; ?>"/>
  </p>

  <?php
  $options[] = array('type' => 'post', 'value' => 'most_commented_post', 'name' => 'Post: Most commented');
  $options[] = array('type' => 'post', 'value' => 'recent_commented_post', 'name' => 'Post: Latest commented');
  $options[] = array('type' => 'post', 'value' => 'recent_updated_post', 'name' => 'Post: Latest updated');
  $options[] = array('type' => 'comment', 'value' => 'recent_comments', 'name' => 'Comment: Latest comments');
  if(WP_ALLOW_MULTISITE == TRUE) {
    $options[] = array('type' => 'blog', 'value' => 'recent_post_from_other_blogs', 'name' => 'Blog: Last posts from site');
  } ?>

  <p>
    <label for="<?php echo $this->get_field_name('selection'); ?>"><?php echo __('Select list:'); ?></label><br>
    <select name="<?php echo $this->get_field_name('selection'); ?>" id="<?php echo $this->get_field_id('selection'); ?>">
      <option value=""> [Please make your selection] </option>
      <?php foreach($options as $option) : ?>
        <?php $value = $option['type'] . ':' . $option['value']; ?>
        <option value="<?php print $value; ?>" <?php echo ($value == $instance['selection']) ? 'selected' : ''; ?>><?php print $option['name']; ?></option>
      <?php endforeach; ?>
    </select>
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('limit'); ?>"><?php echo __('Number of posts in the list:') ?></label><br>
    <input id="<?php echo $this->get_field_id('limit') ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>"/>
  </p>

  <p>
    Use:
    <input id="<?php echo $this->get_field_id('data_to_use'); ?>" name="<?php echo $this->get_field_name('data_to_use'); ?>" type="radio" value="content" <?php echo $data_to_use == 'content' ? 'checked': ''; ?> />
    content
    <input id="<?php echo $this->get_field_id('data_to_use'); ?>" name="<?php echo $this->get_field_name('data_to_use'); ?>" type="radio" value="excerpt" <?php echo $data_to_use == 'excerpt' ? 'checked': ''; ?> />
    excerpt
  </p>

  <div class="spl-thumbnail">
    <div class="spl-thumbnail-wrapper">
      <p>
        <input id="<?php echo $this->get_field_id('has_thumbnail') ?>" class="spl-thumbnail-checkbox" name="<?php echo $this->get_field_name('has_thumbnail'); ?>" type="checkbox" value="checked" <?php echo $has_thumbnail ? 'checked': ''; ?>>
        Show thumbnail in push
      </p>

      <p class="spl-thumbnail-dropdown-wrapper">
        <label for="<?php echo $this->get_field_name('thumbnail_size'); ?>"><?php echo __('Select thumbnail size:'); ?></label><br>
        <select name="<?php echo $this->get_field_name('thumbnail_size'); ?>" id="<?php echo $this->get_field_id('thumbnail_size'); ?>">
        <?php include_once('db_queries.php');
          foreach(spl_get_thumbnail_sizes() as $name => $desc) : ?>
            <option <?php echo ($name == $instance['thumbnail_size']) ? 'selected' : '' ?> value="<?php echo $name; ?>">
              <?php echo $desc; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </p>
    </div>
  </div>

  <p>
    <label for="<?php echo $this->get_field_name('length'); ?>"><?php echo __('Length in characters:'); ?></label><br>
    <input id="<?php echo $this->get_field_id('length'); ?>" name="<?php echo $this->get_field_name('length'); ?>" type="text" value="<?php echo $length; ?>" />
    <small>&nbsp;0=show all and -1=show none</small>
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('link'); ?>"><?php echo __('Link title:'); ?></label><br>
    <input id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
  </p>

  <?php if($template_files) : ?>
    <p class="spl-template">
      <label for="<?php echo $this->get_field_name('template'); ?>"><?php echo __('Select template:'); ?></label><br>
      <select name="<?php echo $this->get_field_name('template'); ?>" id="<?php echo $this->get_field_id('template'); ?>">
        <option value=""> [Use default template] </option>
        <?php foreach($template_files as $template) : ?>
          <option <?php echo ($template['Path'] == $instance['template']) ? 'selected' : '' ?> value="<?php echo $template['Path']; ?>">
            <?php echo $template['Name']; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </p>
  <?php endif; ?>

</div>
