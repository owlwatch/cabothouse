(function($){
	$(document).on('click', 'a.modal, li.modal a', e => {
		e.preventDefault();
		let $target = $(e.target);
		if( !$target.is('a') ){
			$target = $target.find('a');
		}
		console.log( e );
		$("#modal-iframe").iziModal({
		    title: $target.text(),
			headerColor: '#111111',
		    overlayClose: true,
		    iframe : true,
		    openFullscreen: false,
		    borderBottom: false,
			zindex: 999999,
			iframeHeight: $target.data('height') || 350
		});
		$('#modal-iframe').iziModal('open', e);
	});
})(jQuery);
