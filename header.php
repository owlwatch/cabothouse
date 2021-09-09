<?php
ob_start();

add_filter( 'et_html_logo_container', function( $html ){
	$search = '</div>';
	$pos = strrpos( $html, $search );
	if( $pos !== false ){
		ob_start();
		dynamic_sidebar('logo-right');
		$updated = ob_get_clean().'</div>';
		$html = substr_replace($html, $updated, $pos, strlen($search) );
	}
	return $html;
});

include get_template_directory().'/header.php';
$header = ob_get_clean();

$svg = file_get_contents( get_stylesheet_directory().'/src/svg/logo-helvetica.svg' );

/*
// lets replace the logo with the svg
$header = preg_replace_callback('#<img[^>]+?id="logo"[^>]*?/>#', function($matches) use ($svg){
	$imgElement = new SimpleXMLElement( $matches[0] );
	$svgElement = new SimpleXMLElement( $svg );
	foreach( $imgElement[0]->attributes() as $k => $v ){
		// if( $k !== 'src' ){
		// 	$svgElement->addAttribute($k, (string)$v);
		// }
		// else {
		// 	$attrs = $svgElement->attributes();
		//
		// 	if( isset($attrs[$k]) ){
		// 		$svgElement[$k] = $v;
		// 	}
		// 	else {
		// 		$svgElement->addAttribute($k, '#logo-helvetica.svg');
		// 	}
		// }
	}
	return $svgElement->asXML();
}, $header);
*/

echo $header;
