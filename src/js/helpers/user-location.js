import Cookies from './cookies';
import distance from './geo';
const $ = jQuery;

class UserLocation {

	constructor(){
		this.stores = cabothouse_config.locations;
		if( cabothouse_config.my_store ){
			this.initLocation();
		}
		this.initEvents();
		this.dropdown = false;
	}

	initLocation(){

		this.ipinfo = Cookies.get('ipinfo');

		let location = Cookies.get('location');
		if( location && location.id ){
			// get the users preferred store
			this.setLocation( location );
			return;
		}

		if( this.ipinfo && this.ipinfo.loc && cabothouse_config.user_ip === this.ipinfo.ip ){
			return this.lookupLocation();
		}
		else if( cabothouse_config.ipinfo_token ){
			$.ajax({
				url: 'https://ipinfo.io',
				headers: {
					'Authorization' : 'Bearer '+cabothouse_config.ipinfo_token,
					'Accept' : 'application/json'
				}
			}).done( res => {

				Cookies.set('ipinfo', res);
				this.ipinfo = res;
				this.lookupLocation();
			})
		}

	}

	initEvents(){
		$(document).on('click', '[data-toggle="location-dropdown"]', this.toggleDropdown.bind(this) );
		$(window).on('resize orientationchange', this.onResize.bind(this) );
		$(document).on('click', '.location-dropdown__background', this.backgroundClick.bind( this ) );
		$(document).on('click', '[data-action="set-location"]', this.setLocationClick.bind( this ) );
		$(document).on('click', '[data-action="contact-store"]', this.contactLocationClick.bind( this ) );
	}

	lookupLocation(){

		if( !this.ipinfo ){
			return;
		}

		// lets get the closest location
		let coords = this.ipinfo.loc.split(',');
		this.stores.forEach( store => {
			store.distance = distance( store.location.lat, store.location.lng, Number(coords[0]), Number(coords[1]), )
		});

		this.stores.sort( (a,b) => a.distance - b.distance);
		this.setLocation( this.stores[0] );
		this.location = this.stores[0];

	}

	getCoords(){
		if( this.location ){
			return [this.location.location.lat, this.location.location.lng];
		}
		if( this.ipinfo ) {
			return this.ipinfo.loc.split(',');
		}
		return false;
	}

	sortStores(){
		// lets get the closest location
		let coords = this.getCoords();
		if( !coords ){
			return this.stores;
		}

		this.stores.forEach( store => {
			store.distance = distance( store.location.lat, store.location.lng, Number(coords[0]), Number(coords[1]), )
		});

		this.stores.sort((a,b) => {
			if( this.location ){
				return this.location.id === a.id ? -1 : this.location.id === b.id ? 1 : a.distance - b.distance;
			}
			return a.distance - b.distance;
		});
		return this.stores;
	}

	onChange( callback ){
		if( this.location ){
			callback( this.location );
		}
		this.updateUI();
		$(document).on('userlocation', (e,location) => {callback(location);} );
	}

	setLocation( location ){
		this.location = location;
		Cookies.set('location', location, 365 );
		$(document).trigger('userlocation', [location]);
		this.updateUI();
	}

	setLocationClick( e ){
		let id = Number($(e.target).data('id'));
		let store = this.stores.find( s => s.id === id );
		if( store ){
			this.setLocation( store );
		}
		this.hideDropdown();
	}

	contactLocationClick( e ){
		let id = Number($(e.target).data('id'));
		let store = this.stores.find( s => s.id === id );
		console.log( e.target, id, store );
		if( store ){
			$(document).trigger('contact-store', [store] );
		}
	}

	updateUI(){
		// make sure the dom is ready before updating ui
		$(() => {
			if( !this.location ){
				let $container = $('#et-top-navigation');
				$container.find('#user-location').remove();
				return;
			}
			else {
				let triggerClass = this.dropdown ? ' -open' : '';
				if( !this.$trigger ){
					this.$trigger = $(
						`<div id="user-location" class="user-location${triggerClass}">
							<div class="user-location__label">
								Your Store:
							</div>
							<div class="user-location__value" data-toggle="location-dropdown">
								<span data-name="store-name">${this.location.name}</span>
								<span class="fa fa-angle-down"></span>
							</div>
						</div>`
					);
					this.$trigger.insertBefore( '#et_mobile_nav_menu' );
				}
				else {
					this.$trigger.find('[data-name="store-name"]').html( this.location.name );
				}

				if( !this.$dropdown ){
					// lets also create the dropdown
					this.$dropdown = $(`
						<div id="user-location-dropdown" class="location-dropdown">
							<div class="location-dropdown__background"></div>
							<ul class="location-dropdown__items"></ul>
						</div>
					`);
					this.updateDropdownList();
				}
			}
		})
	}

	updateDropdownList(){
		let $ul = this.$dropdown.find('ul');
		$ul.empty();

		this.sortStores().forEach( store => {
			let classes = ['location-dropdown__item'];
			let isUserLocation = false;
			if( this.location && this.location.id === store.id ){
				classes.push('location-dropdown__item--selected');
				isUserLocation = true;
			}
			let address = store.address.trim().split('\n')[0];
			classes = classes.join(' ');
			let phoneLink = store.phone.replace(/[^\d]/g,'');
			let $li = $(`
				<li class="${classes}">
					<div class="location-dropdown__item-info">
						<div class="location-dropdown__item-name">
							${store.name}
						</div>
						<div class="location-dropdown__item-address">
							${address}
						</div>
						<div class="location-dropdown__item-phone">
							<a href="tel:${phoneLink}">
								${store.phone}
							</a>
						</div>
					</div>
					<div class="location-dropdown__item-button location-dropdown__button-container">
					</div>
				</li>
			`);
			let $btn_container = $li.find('.location-dropdown__button-container');
			if( isUserLocation ){
				let $btn = $(`
					<button class="et_pb_button location-dropdown__button location-dropdown__button-selected" data-action="set-location" data-id="${store.id}">
						My store
					</button>
				`);
				$btn_container.append( $btn );
			}
			else {
				let $btn = $(`
					<button class="et_pb_button location-dropdown__button" type="button" data-id="${store.id}" data-action="set-location">
						Set as my store
					</button>
				`);
				$btn_container.append( $btn );
			}
			$ul.append( $li );
		});
	}

	toggleDropdown(){
		if( this.dropdown ){
			this.hideDropdown();
		}
		else {
			this.showDropdown( $(window).width() < 981 ? 'mobile' : 'desktop' );
		}
	}

	hideDropdown(){
		this.$trigger.removeClass('-open');
		if( this.dropdown == 'mobile' ){
			this.$dropdown
				.css({'height': this.$dropdown.outerHeight()})
				.animate({
					height: 0
				}, 300, () => {
					this.$dropdown.remove();
					setTimeout( () => $(window).trigger('resize'), 100 );
				});
		}
		else {
			this.$dropdown.animate({
				'opacity': 0
			}, 300, () => {
				this.$dropdown.remove()
				$(window).trigger('resize');
			});
		}
		this.dropdown = false;
	}

	showDropdown(style){
		this.updateUI();
		if( !this.$dropdown ){
			return;
		}
		if( this.dropdown === style ){
			return;
		}
		this.updateDropdownList();
		this.$trigger.addClass('-open');
		if( style == 'mobile' ){
			this.$dropdown.insertAfter( this.$trigger );
			if( !this.dropdown ){
				this.$dropdown
					.css({'height': '0', 'top': 'auto', 'opacity': 1})
					.animate({
						height: this.$dropdown[0].scrollHeight
					}, 300, () => {
						this.$dropdown.css({'height': 'auto'});
						$(window).trigger('resize');
					});
			}
			else {
				this.$dropdown.css({'height': 'auto', 'top': 'auto', 'opacity': 1});
			}
		}
		else {
			this.$dropdown.css({'opacity': 0});
			this.$dropdown.appendTo( 'body' );
			this.$dropdown.animate({'opacity': 1}, 300);
			this.dropdown = style;
			this.onResize();
		}

		this.dropdown = style;
	}

	backgroundClick(e){
		this.toggleDropdown();
	}

	onResize(){
		if( !this.dropdown ){
			return;
		}
		if( $(window).width() < 981 && this.dropdown !== 'mobile' ){
			// move to mobile view
			this.showDropdown('mobile');
		}
		else if( $(window).width() > 980 ){
			if( this.dropdown !== 'desktop' ){
				// move to mobile view
				this.showDropdown('desktop');
			}
			var offset = $('#main-header').outerHeight() + $('body').offset().top;
			console.log( offset );
			this.$dropdown.css({
				top: offset,
				height: $(window).height() - offset
			});
		}
	}

}

window.UserLocation = new UserLocation();

export default window.UserLocation;
