<?php
namespace Theme;

class Front extends Singleton
{
	protected function __construct()
	{
		add_action( 'woocommerce_before_main_content', [$this, 'categoryHeader'] );

		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

		add_filter( 'algolia_wc_should_display_instantsearch', function( $show ){

			if( is_shop() ){
				return true;
			}
			return $show;
		});

		add_action( 'wp_enqueue_scripts', [$this, 'registerScripts'] );

		add_filter( 'gform_confirmation_anchor', '__return_false' );

		add_action( 'et_after_main_content', [$this, 'footerBar'] );
	}

	public function registerScripts()
	{
		wp_deregister_script( 'db_pb_map_pin' );
		wp_enqueue_style( 'fontawesome', 'https://use.fontawesome.com/releases/v5.3.1/css/all.css' );
		wp_enqueue_style( 'izimodal', 'https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.min.css' );
		wp_enqueue_style( 'cabothouse', get_stylesheet_directory_uri().'/build/cabothouse.css' );
		wp_enqueue_script( 'izimodal', 'https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/js/iziModal.min.js' );
		wp_enqueue_script( 'cabothouse', get_stylesheet_directory_uri().'/build/cabothouse.js', ['izimodal'] );

		wp_localize_script( 'cabothouse', 'cabothouse_config', [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'ipinfo_token' => 'ac9a280546bc3c',
			'user_ip' => $_SERVER['REMOTE_ADDR'],
			'locations' => Service\LocationService::getLocations()
		]);
	}

	public function categoryHeader()
	{
		if( !is_tax() ){
			return;
		}
		$qo = get_queried_object();
		$key = "{$qo->taxonomy}_{$qo->term_id}";
		$image = get_field( 'header_image', $key );
		$layout = get_field( 'override_default_layout', $key );

		if( !$image && !$layout ){
			return;
		}

		if( !$layout ){
			$layout = get_field( 'product_term_header_layout', 'theme' );
			if( !$layout ){
				return;
			}
		}

		$variables = [
			'term_title' => '<div class="collection-label">Collections</div>'.get_queried_object()->name,
			'term_description' => get_queried_object()->description,
		];

		$content = do_shortcode( $layout->post_content );

		// replace variables
		$content = preg_replace_callback( '#{{(.+?)}}#', function($matches) use($variables){
			$name = trim($matches[1]);
			if( isset( $variables[$name] ) ){
				return $variables[$name];
			}
			return '';
		}, $content );

		// special case for the background image
		if( $image ){
			$image_url = $image['url'];
			$content = preg_replace( '#background_image="(.*?)"#', 'background_image="'.$image_url.'"', $content );
		}

		remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
		remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10);
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		add_filter( 'woocommerce_show_page_title', '__return_false' );


		echo $content;
	}

	public function footerBar()
	{
		get_template_part('templates/layout/footer-bar');
	}
}
