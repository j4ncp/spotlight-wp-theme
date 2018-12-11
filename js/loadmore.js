/* this handles fetching more posts on the click of a button */
jQuery(function($){
	$('.loadmore-button').click(function()  {
		var button = $(this);
		var data = {
			'action': 'loadmore',
			'query': params.posts,
			'page': params.current_page
		};
		$.ajax({
			url: params.ajaxurl,
			data: data,
			type: 'POST',
			beforeSend: function(xhr) {
				// change button text
				button.text('Loading...');
			},
			success: function(data) {
				if (data) {
					button.text('More');
					$('.loadmore-container').before(data);
					params.current_page++;
					if (params.current_page == params.max_page)
						button.remove();  // remove the button if this was the last page
				} else {
					button.remove();  // remove button if there is no data
				}
			}
		});
	});
});
