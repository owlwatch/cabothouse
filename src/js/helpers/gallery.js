(function($){


	$(document).on('et_pb_after_init_modules', function(){
		jQuery('.et_post_gallery').each(function(){

			$(this).data().magnificPopup.image = {
				titleSrc: function(item){
					var title = item.el.attr('title');
					var image = item.el.attr('href');
					var $data = $('<div />');
					$('<h4 />')
						.html(title)
						.appendTo( $data )
						;
					return $data.html();
				},

				markup: '<div class="mfp-figure">'+
			        		'<div class="mfp-close"></div>'+
							'<div class="mfp-top-bar">'+
								'<div class="mfp-title"></div>'+
							'</div>'+
			            	'<div class="mfp-img"></div>'+
			            	'<div class="mfp-bottom-bar">'+
								'<div class="mfp-details">'+
									'<div class="mfp-details-buttons">'+
										'<button class="mfp-info" data-toggle="mfp-caption">'+
											'<span class="fas fa-angle-up"></span> '+
											'Details'+
										'</button>'+
										'<button class="mfp-info" data-action="inquire">'+
											'<span class="fas fa-comment-alt"></span> '+
											'Ask about this item'+
										'</button>'+
									'</div>'+
									'<div class="mfp-caption-container">'+
										'<div class="mfp-caption"></div>'+
									'</div>'+
								'</div>'+
								//'<div class="inquire-button"></div>'+
			              		'<div class="mfp-counter"></div>'+
			            	'</div>'+
			          	'</div>' // Popup HTML markup. `.mfp-img` div will be replaced with img tag, `.mfp-close` by close button
			};
			$(this).on('mfpMarkupParse', function(event, template, values, item){
				var title = item.el.attr('title');
				var image = item.el.attr('href');

				// caption?
				var caption = item.el
				                  .closest('.et_pb_gallery_item')
								  .find('.et_pb_gallery_caption')
								  .html();

				if( caption ) caption = caption.trim();

				setTimeout(()=>{
					// add our stuff to the top bar
					if( caption ){
						$('.mfp-caption').html( caption );
						$('[data-toggle="mfp-caption"]').show();
					}
					else {
						$('.mfp-caption').html('');
						$('[data-toggle="mfp-caption"]').hide();
					}
					$('[data-action="inquire"]')
						.data('title', title)
						.data('image', image);
				},0);

			});

		});
		$(document).on('click', '[data-toggle="mfp-caption"]', e => {
			let $btn = $(e.target);
			let $ct = $('.mfp-caption-container');
			if( !$ct.height() ){
				$btn.addClass('-active');
				$ct.animate({
					height: $ct[0].scrollHeight
				}, 200, () => {
					$ct.css({ height: 'auto' } );
				});

			}
			else {
				$btn.removeClass('-active');
				$ct.css({height: $ct.outerHeight()});
				$ct.animate({
					height: 0
				}, 200);
			}
		});

		$(document).on('click', '[data-action="inquire"]', e => {
			var $btn = $(e.target);
			$(document).trigger('inquire.contact-widget', [
				$btn.data('title'), $btn.data('image')
			]);
		});
	});
})(jQuery);
