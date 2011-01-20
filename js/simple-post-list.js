/*
 * Implement javascript and ajax stuff
 */
jQuery(document).ready(function() {

  initCheckbox();

  jQuery('.spp_thumbnail_checkbox').live('click', function() {
    if(jQuery(this).is(':checked')) {
      jQuery(this).parentsUntil('.spp-thumbnail').find('.spp_thumbnail_dropdown_wrapper').show();
    } else {
      jQuery(this).parentsUntil('.spp-thumbnail').find('.spp_thumbnail_dropdown_wrapper').hide();
    }
  });

});

jQuery(document).ajaxSuccess(function() {
  initCheckbox();
});

function initCheckbox() {
  jQuery('.spp_thumbnail_checkbox').each(function(key, object) {
    if(jQuery(object).is(':checked')) {
      jQuery(object).parentsUntil('.spp-thumbnail').find('.spp_thumbnail_dropdown_wrapper').show();
    } else {
      jQuery(object).parentsUntil('.spp-thumbnail').find('.spp_thumbnail_dropdown_wrapper').hide();
    }
  });
}