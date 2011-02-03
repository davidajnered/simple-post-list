<?php include_once('db_queries.php'); ?>

<div class="simple-post-list">
  <p>
    <label for="<?php echo $this->get_field_name('widget_title'); ?>"><?php echo __('Title:') ?></label><br>
    <input id="<?php echo $this->get_field_id('widget_title') ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" value="<?php echo $widget_title; ?>"/>
  </p>

  <?php
    foreach(spl_get_all_blogs() as $blog) {
      $options[$blog['id']]['blogname'] = $blog['name'];
      $options[$blog['id']]['option'][] = array('type' => 'post', 'value'    => 'recent_updated_post', 'name'    => 'Post: Latest');
      $options[$blog['id']]['option'][] = array('type' => 'post', 'value'    => 'most_commented_post', 'name'    => 'Post: Most commented');
      $options[$blog['id']]['option'][] = array('type' => 'post', 'value'    => 'recent_commented_post', 'name'  => 'Post: Latest commented');
      $options[$blog['id']]['option'][] = array('type' => 'comment', 'value' => 'recent_comments', 'name'        => 'Comment: Latest comments');
    }
    if(WP_ALLOW_MULTISITE == TRUE) {
      $options['general']['blogname'] = 'General';
      $options['general']['option'][] = array('type' => 'blog', 'value' => 'recent_post_from_other_blogs', 'name' => 'Blog: Latest posts from all blogs');
    }
  ?>
  <p>
    <label for="<?php echo $this->get_field_name('selection'); ?>"><?php echo __('Select list:'); ?></label><br>
    <select name="<?php echo $this->get_field_name('selection'); ?>" id="<?php echo $this->get_field_id('selection'); ?>">
      <option value=""> [Please make your selection] </option>
      <?php foreach($options as $blog) : ?>
        <?php error_log(var_export($blog, TRUE));?>
        <option><?php print $blog['blogname']; ?></option>
        <?php foreach($blog['option'] as $data) : ?>
          
          <?php $value = $data['type'] . ':' . $data['value']; ?>
          <option value="<?php print $value; ?>" <?php echo ($value == $instance['selection']) ? 'selected' : ''; ?>>
            - <?php print $data['name']; ?>
          </option>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </select>
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('limit'); ?>"><?php echo __('Number of posts in the list:') ?></label><br>
    <input id="<?php echo $this->get_field_id('limit') ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>"/>
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
        if(spl_get_thumbnail_sizes() != NULL) :
          foreach(spl_get_thumbnail_sizes() as $name => $desc) : ?>
            <option <?php echo ($name == $instance['thumbnail_size']) ? 'selected' : '' ?> value="<?php echo $name; ?>">
              <?php echo $desc; ?>
            </option>
          <?php endforeach; ?>
        <?php else : ?>
          <option> [You need to upload an image] </option>
        <?php endif; ?>
        </select>
      </p>
    </div>
  </div>

  <p>
    <input id="<?php echo $this->get_field_id('data_to_use'); ?>" name="<?php echo $this->get_field_name('data_to_use'); ?>" type="radio" value="content" <?php echo $data_to_use == 'content' ? 'checked': ''; ?> />
    content
    <input id="<?php echo $this->get_field_id('data_to_use'); ?>" name="<?php echo $this->get_field_name('data_to_use'); ?>" type="radio" value="excerpt" <?php echo $data_to_use == 'excerpt' ? 'checked': ''; ?> />
    excerpt
    <input id="<?php echo $this->get_field_id('paragraph'); ?>" name="<?php echo $this->get_field_name('paragraph'); ?>" type="checkbox" value="checked" <?php echo $paragraph == TRUE ? 'checked': ''; ?> />
    paragraph
  </p>

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
