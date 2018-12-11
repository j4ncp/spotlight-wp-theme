jQuery(document).ready(function() {
	// initialize the igwidget
	jQuery.ajax({
		type: 'POST',
		data: { 'action': 'igdata'},
		url: igparams.ajaxurl,
		success: function(data) {
		  	jQuery('#igfeed-list').html(data);
		}
	});
});