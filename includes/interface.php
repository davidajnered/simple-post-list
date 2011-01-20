<?php include_once('db_queries.php'); ?>

<div class="simple-post-list">
  <p>
    <label for="<?php echo $this->get_field_name('title'); ?>"><?php echo __('Title:') ?></label><br>
    <input id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
    <small>If empty the posts title will be used</small>
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('selection'); ?>"><?php echo __('Select list:'); ?></label><br>
    <select name="<?php echo $this->get_field_name('selection'); ?>" id="<?php echo $this->get_field_id('selection'); ?>">
      <option value=""> [Please make your selection] </option>
      <option value="most_comment" <?php echo ('most_comment' == $instance['selection']) ? 'selected' : '' ?>>Most commented</option>
      <option value="recent_comment" <?php echo ('recent_comment' == $instance['selection']) ? 'selected' : '' ?>>Recent comments</option>
      <option value="recent_update" <?php echo ('recent_update' == $instance['selection']) ? 'selected' : '' ?>>Recent updates</option>
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

  <div class="spp-thumbnail">
  <div class="spp-thumbnail-wrapper">
  <p>
    <input id="<?php echo $this->get_field_id('thumbnail') ?>" class="spp_thumbnail_checkbox" name="<?php echo $this->get_field_name('thumbnail'); ?>" type="checkbox" value="checked" <?php echo $thumbnail ? 'checked': ''; ?>>
    Show thumbnail in push
  </p>

  <p class="spp_thumbnail_dropdown_wrapper">
    <label for="<?php echo $this->get_field_name('thumbnail_size'); ?>"><?php echo __('Select thumbnail size:'); ?></label><br>
    <select name="<?php echo $this->get_field_name('thumbnail_size'); ?>" id="<?php echo $this->get_field_id('thumbnail_size'); ?>">
    <?php include_once('db_queries.php');
      foreach(spp_get_thumbnail_sizes() as $name => $desc) : ?>
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
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('link'); ?>"><?php echo __('Link title:'); ?></label><br>
    <input id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
  </p>
</div>
