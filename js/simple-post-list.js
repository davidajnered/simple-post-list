/*
 * Implement javascript and ajax stuff
 */
jQuery(document).ready(function() {

  initCheckbox();

  jQuery('.spl-thumbnail-checkbox').live('click', function() {
    if(jQuery(this).is(':checked')) {
      jQuery(this).parentsUntil('.spl-thumbnail').find('.spl-thumbnail-dropdown-wrapper').slideDown(100);
    } else {
      jQuery(this).parentsUntil('.spl-thumbnail').find('.spl-thumbnail-dropdown-wrapper').slideUp(100);
    }
  });

});

jQuery(document).ajaxSuccess(function() {
  initCheckbox();
});

function initCheckbox() {
  jQuery('.spl-thumbnail-checkbox').each(function(key, object) {
    if(jQuery(object).is(':checked')) {
      jQuery(object).parentsUntil('.spl-thumbnail').find('.spl-thumbnail-dropdown-wrapper').show();
    } else {
      jQuery(object).parentsUntil('.spl-thumbnail').find('.spl-thumbnail-dropdown-wrapper').hide();
    }
  });
}