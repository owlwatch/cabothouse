import UserLocation from './user-location';
(function($){
	var location;
	function setLocation(_location){
		location = _location;
		updateLocation();
	}

	function updateLocation( _location ){
		location = _location ? _location : location;
		if( location ){
			$('.design-survey .chx-store select option[value="'+location.id+'"]')
				.prop('selected', true);
		}
	}

	function pageRender(e, i){
		let $wrapper = $('#gform_wrapper_'+i);
		if( !$wrapper.find('form').hasClass('design-survey') ){
			return;
		}
		let page = 1;
		let $active = $wrapper.find( '.gform_page' ).filter((i,el) => {
			if( $(el).is(':visible') ){
				page = i;
				return true;
			}
			return false;
		});
		let total = $wrapper.find('.gform_page').length-1;

		if( $active.length && !$active.hasClass('no-paging') ){
			let $toolbar = $('<div />');

			let title = '';
			if( $active.hasClass('about-your-room') ){
				title = 'About Your Room';
			}
			else if( $active.hasClass('wrapping-up') ){
				title = 'Wrapping Up';
			}
			else {
				title = 'Getting to Know You';
			}

			$wrapper.find('form').prepend( $toolbar );
			$toolbar.addClass( 'paging-toolbar' );
			$toolbar.html(`
				<span class="label">${title}</span>
				<span class="pages">Question ${page} of ${total}</span>
			`);
		}

		// check for the image column
		let $image = $active.find('.image-right');
		if( $image.length ){
			let $fields = $active.find('.gform_page_fields');
			let $footer = $active.find('.gform_page_footer');

			let $ct = $('<div />').addClass('flex-row');
			$ct.insertBefore( $fields );
			let $div = $('<div />');
			$fields.appendTo( $div );
			$footer.appendTo( $div );
			$div.appendTo( $ct );
			let $col = $('<div />');
			$col.appendTo( $ct );
			$col.html( $image.html() );
			$image.remove();
		}

		updateLocation();

	}
	UserLocation.onChange(setLocation);
	$(document).on('gform_post_render', pageRender);
})(jQuery);
