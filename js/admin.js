jQuery(function () {
	jQuery(document).on('click', '.jgch-handlediv', function(e){
		e.preventDefault();
		var box = jQuery(this).parent('.jgch-postbox');
		if (jQuery(box).hasClass('closed')) {
			box.removeClass('closed');
			box.find('.jgch-arrow-down').removeClass('jgch-arrow-down').addClass('jgch-arrow-up');
			box.find('form').slideDown();		
		} else {
			box.addClass('closed');
			box.find('.jgch-arrow-up').removeClass('jgch-arrow-up').addClass('jgch-arrow-down');
			box.find('form').slideUp();	
		}
	});	
});
