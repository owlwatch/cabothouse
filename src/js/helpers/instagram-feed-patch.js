jQuery(function($){

	class Patch {

		constructor( $widget ){
			this.$widget = jQuery('[data-elfsight-instagram-feed-options]');
			if( !this.$widget.length ){
				return;
			}
			this.poll();
		}

		poll(){
			if( this.$widget.data('EappsInstagramFeed') ){
				this.ready();
			}
			else {
				setTimeout( () => this.poll(), 10);
			}
		}

		ready(){
			var widget = this.$widget.data('EappsInstagramFeed');
			if( !widget || !widget.layout || !widget.layout.popup ){
				return;
			}
			var popup = widget.layout.popup;
			var open = popup.open;
			var close = popup.close;
			popup.open = function(){
				popup.$element.show();
				open.apply(popup, arguments);
			};
			popup.close = function(){
				popup.$element.hide();
				close.apply(popup, arguments);
			};
		}
	}

	new Patch();

});
