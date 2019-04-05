<?php
namespace Theme\Service;

use Theme\PostType\Location;

use WP_Query;

class LocationService
{
	public function __construct()
	{

	}

	public static function getLocations()
	{
		$query = new WP_Query([
			'post_type' => Location::NAME,
			'posts_per_page' => -1
		]);

		$locations = [];
		foreach( $query->posts as $post ){
			// simple objects
			$locations[] = [
				'id' => $post->ID,
				'name' => $post->post_title,
				'slug' => $post->post_name,
				'address' => get_field( 'address', $post->ID, false ),
				'location' => get_field( 'location', $post->ID ),
				'phone' => get_field( 'phone', $post->ID ),
			];
		}

		return $locations;
	}
}
