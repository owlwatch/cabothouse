<?php

require_once __DIR__."/vendor/autoload.php";

Theme\Theme::init();

add_filter('et_builder_google_fonts', function( $fonts ){
	return $fonts;
});

//Remove shortcode youtube from Jacpack
function thq_remove_jetpack_shortcode_youtube($shortcodes)
{
    $dir_jetpack_shortcodes = plugin_dir_path( __DIR__ ).'jetpack/modules/shortcodes/';
    $shortcodes_remove = array('youtube.php');
    foreach ($shortcodes_remove as $sc) {
        if ($key = array_search($dir_jetpack_shortcodes . $sc, $shortcodes)) {
            unset($shortcodes[$key]);
        }
    }
    return $shortcodes;
}

add_filter('jetpack_shortcodes_to_include', 'thq_remove_jetpack_shortcode_youtube');

//Add filter for work
add_filter('oembed_result', 'thq_hide_related_videos_youtube', 10, 3);
function thq_hide_related_videos_youtube($data)
{
    $data = preg_replace('/(youtube\.com.*)(\?feature=oembed)(.*)/', '$1?rel=0&showinfo=0$3', $data);
    return $data;
}

/*
add_filter('wp_get_attachment_image_src', 'staticize_attachment_src', null, 4);
function staticize_attachment_src($image, $attachment_id, $size, $icon){
	if (is_array($image) && !empty($image[0])) {
		$image[0] = str_replace( 'https://cabothousefurniture.com', 'https://cabothouse.b-cdn.net', $image[0] );
	}
	return $image;
}
*/

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
	if( strpos($url, home_url()) !== false ){
		error_log( print_r($parsed_args, 1));
	}
	return $preempt;
}, 10, 3);
