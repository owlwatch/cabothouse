<?php

require_once __DIR__."/vendor/autoload.php";

use \Exception;

Theme\Theme::init();

add_filter('et_builder_google_fonts', function( $fonts ){
	return $fonts;
});
/*
function woocommerce_output_content_wrapper() {
	if(is_product_category()) {
		if(is_product_category( 'Living Room' )) {
			echo do_shortcode('[et_pb_section global_module=1077][/et_pb_section]');
		} elseif(is_product_category( 'Bedroom' )) {
			echo do_shortcode('[et_pb_section global_module=1077][/et_pb_section]');
		} elseif(is_product_category( 'Dining Room' )) {
			echo do_shortcode('[et_pb_section global_module=1077][/et_pb_section]');
		} elseif(is_product_category( 'Leather' )) {
			echo do_shortcode('[et_pb_section global_module=1077][/et_pb_section]');
		} elseif(is_product_category( 'Occasional' )) {
			echo do_shortcode('[et_pb_section global_module=1077][/et_pb_section]');
		}
		echo '<div class="max-width-content">';
	}
}

add_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 18, 0 );

function woocommerce_output_content_wrapper_end() {
	if(is_product_category()) {
		echo '</div><!-- End Max Width Content -->';
	}
}

add_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 99 );
*/
/*
function exception_error_handler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

*/

add_filter('pre_http_request', function($preempt, $parsed_args, $url){
	$e = new Exception('test');
	error_log( 'pre_http_request: '.$url."\n".$e->getTraceAsString());
});