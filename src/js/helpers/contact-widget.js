import UserLocation from './user-location';
(function($){

	class ContactWidget {

		constructor(){
			UserLocation.onChange(this.setLocation.bind(this));
			$(this.init.bind(this));
			$(this.fixMagnificPopup.bind(this));

		}

		init(){
			this.initElements();
			this.initEvents();
		}

		fixMagnificPopup(){
			if( !$.magnificPopup || !$.magnificPopup.instance ){
				return;
			}
			$.magnificPopup.instance._onFocusIn = function(e){
				var $target = $(e.target);
				if( !$target.is('.mfp-content button') ){
					$.magnificPopup.proto._onFocusIn.call(this,e);
				}
			}
		}

		initElements(){
			this.$el = $('.contact-widget');
			this.$trigger = this.$el.find( '.contact-widget__trigger' );
			this.$content = this.$el.find( '.contact-widget__content' );
			this.$block = this.$el.find( '.contact-widget__block' );
			this.formId = Number( this.$el.find( 'form' ).attr( 'id' ).replace( /^gform_/, '' ) );
			this.formHTML = this.$el.find( '.contact-widget__form' ).html();
		}

		initEvents(){
			this.$trigger.on('click', this.toggle.bind(this) );
			this.$el.on('focusin', this.cancelFocusBubble.bind(this) );
			this.$el.on('keyup keydown keypress', this.stopPropagation.bind(this) );
			$(document).on('inquire.contact-widget', this.inquire.bind(this) );
			$(document).on('contact-store', this.contactStore.bind(this) );
			$(document).on('gform_confirmation_loaded', this.onConfirmation.bind(this) );
		}

		toggle(){
			this.$el.hasClass('open') ? this.close() : this.open();
		}

		open( title, image  ){
			if( !this.$el.find( 'form' ).length ){
				this.$el.find( '.contact-widget__form' ).html( this.formHTML );
				this.initForm();
			}
			this.$el.find('.contact-widget__inquiry').remove();
			if( title && image ){
				this.addImage( title, image );
			}
			else {
				this.clearImage();
			}
			let isOpen = this.$el.hasClass('open');
			this.$el.addClass('open');
			if( isOpen ){
				console.log( isOpen );
				this.$el.animate({
					'margin-bottom':20
				},150,()=>{
					this.$el.animate({
						'margin-bottom':0
					},130,()=>{
						this.$el.animate({
							'margin-bottom':13
						},120,()=>{
							this.$el.animate({
								'margin-bottom':0
							},100,()=>{
								// this.$el.animate({
								// 	'margin-bottom':7
								// },100,()=>{
								// 	this.$el.animate({
								// 		'margin-bottom':0
								// 	},100,()=>{
								//
								// 	});
								// });
							});
						});
					});
				});
			}
			this.initForm();
		}

		initForm(){
			this.$el.find('[aria-required]').attr('required', 'required');
			this.updateLocation();
			setTimeout(()=>this.$el.find('input').first().focus(), 300 );
		}

		addImage( title, image ){
			this.$el.find('[data-input-name="collection_image"]').val( image );
			this.$el.find('[data-input-name="collection_item"]').val( title );
			this.$el.find( '.gform_wrapper' ).prepend($(`
				<div class="contact-widget__inquiry">
					<img class="contact-widget__inquiry-image" src="${image}" />
					<div class="contact-widget__inquiry-title">Inquiring about <strong>${title}</strong></div>
				</div>
			`));
		}

		clearImage(){
			this.$el.find('[data-input-name="collection_image"]').val('');
			this.$el.find('[data-input-name="collection_item"]').val('');
		}

		updateLocation( location ){
			location = location ? location : this.location;
			if( location && this.$el ){
				this.$el.find('.chx-store select option[value="'+location.id+'"]')
					.prop('selected', true);
			}
		}

		close(){
			this.$el.removeClass('open');
		}

		cancelFocusBubble(e){
			e.stopPropagation();
			e.stopImmediatePropagation();
		}

		stopPropagation(e){
			e.stopPropagation();
			e.stopImmediatePropagation();
		}

		inquire(e, title, image){
			this.open( title, image );
		}

		contactStore( e, store ){
			this.open();
			this.updateLocation(store);
		}

		setLocation(location){
			this.location = location;
			this.updateLocation();
		}

		onConfirmation( e, formId ){
			if( this.formId !== formId ) return;
		}
	}

	window.contactWidget = new ContactWidget();
})(jQuery);
