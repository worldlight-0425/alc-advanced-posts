(function($){
	'use strict';
	$(document).ready( function() {
		var $count = $('.js-meta__item--views-count').first();
		var $id = $count.data('id');
		var $nonce = $count.data('nonce');

		$.get(alchemistsPostViews.ajaxurl + '?action=alchemists-ajax-counter&nonce=' + $nonce + '&p=' + $id, function( html ) {
			$($count).html( html );
		});
	});
})(jQuery);
