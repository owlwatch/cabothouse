<?php
namespace Theme\Service;

use Theme\Singleton;

class CollectionService extends Singleton
{
	public function __construct()
	{
		add_filter( 'et_pb_gallery_shortcode_output', [$this, 'addManufacturersToGalleryItems'] );
	}

	public function addManufacturersToGalleryItems( $content )
	{
		// get all the urls
		if( !is_string($content) || !preg_match_all('/href="(.*?wp-content\/uploads.*?)"/', $content, $matches ) ){
			return $content;
		}

		global $wpdb;
		$placeholders = array_fill(0, count( $matches[1] ), '%s' );
		$placeholders = implode( ', ', $placeholders );
		//$urls = array_map( 'strtolower', $matches[1] );
		$urls = $matches[1];
		$sql = $wpdb->prepare( "SELECT ID, guid FROM {$wpdb->posts} WHERE post_type='attachment' AND guid IN ($placeholders)", $urls );

		$results = $wpdb->get_results( $sql );
		$shadow = \Theme\Service\ShadowTaxonomy::getByTaxonomy( 'product_brand' );

		foreach( $results as &$result ){

			$terms = wp_get_post_terms( $result->ID, 'product_brand' );


			if( count( $terms ) ){
				$term = $terms[0];
				$post = $shadow->getPost( $term->term_id );
				$json = json_encode([
					'name' => $term->name,
					'url' => get_permalink( $post )
				]);
				$content = str_replace(
					'href="'.$result->guid.'"',
					'href="'.$result->guid.'" data-manufacturer="'.$json.'"',
					$content
				);
			}

		}

		return $content;

	}
}
