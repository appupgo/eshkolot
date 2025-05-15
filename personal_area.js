jQuery(document).ready(function ($) {
	
	if(window.innerWidth < 768){
    jQuery('.nameForScreens').html('שם')
    jQuery('.passForScreens').html('סיסמא')
}
	
  jQuery('.entry-title').hide();

  jQuery(document).on('click',".openD", function(event){
			$(this).parents('tr').next().toggle();
	});

  jQuery(document).on('click',"#download, #download_user",function(event) {
    var user = [];
    if(jQuery(this).is('#download_user')){
      user = [jQuery(this).parents('.expan_child').prev().data('id')];
    }
    alert('התוכנה תשלח אליך למייל תוך מספר דקות');
	  jQuery.ajax({
      type: 'POST',
      url: "/wp-admin/admin-ajax.php?cloudflare=true",
      dataType: 'text',
      data: {
          action: 'download_software',
          nonce: 'ajax-nonce',
          user: user,
          event: jQuery(this).attr('id')
      },
      success: function(response){
      },
      error: function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      }
    });		
  });

  function downloadFileWithNewExtension(url, fileName) {
    // URL of the file to be downloaded
    var fileUrl = url;
    // Desired file name with new extension
    var newFileName = fileName;
    // Create a temporary anchor element
    var anchor = document.createElement('a');
    anchor.href = fileUrl;
    anchor.download = newFileName;
    // Trigger a click event on the anchor element
    anchor.click();  
  }

  jQuery(document).on('click',".display_courses_path",function(event){
    var parent = jQuery(this).parents('[class^="course_or_path"]');
    parent.siblings('[class="course_path_'+parent.attr('class').match(/\d+/)[0]+'"]').toggle();
  });

});
