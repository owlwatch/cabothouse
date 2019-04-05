<?php
namespace Theme\Taxonomy;

use Theme\Singleton;

class Location extends Singleton
{

	const NAME = 'location';

	public function __construct()
	{
		add_action( 'init', [$this, 'register'], 1 );
	}

	public function register()
	{
		// Add new taxonomy, make it hierarchical (like categories)
	    $labels = array(
	        'name'              => _x( 'Locations', 'taxonomy general name', 'cabothouse' ),
	        'singular_name'     => _x( 'Location', 'taxonomy singular name', 'cabothouse' ),
	        'search_items'      => __( 'Search Locations', 'cabothouse' ),
	        'all_items'         => __( 'All Locations', 'cabothouse' ),
	        'parent_item'       => __( 'Parent Location', 'cabothouse' ),
	        'parent_item_colon' => __( 'Parent Location:', 'cabothouse' ),
	        'edit_item'         => __( 'Edit Location', 'cabothouse' ),
	        'update_item'       => __( 'Update Location', 'cabothouse' ),
	        'add_new_item'      => __( 'Add New Location', 'cabothouse' ),
	        'new_item_name'     => __( 'New Location Name', 'cabothouse' ),
	        'menu_name'         => __( 'Locations', 'cabothouse' ),
	    );

	    $args = array(
	        'hierarchical'      => true,
	        'labels'            => $labels,
	        'show_ui'           => true,
	        'show_admin_column' => true,
	        'query_var'         => true,
	        'rewrite'           => array( 'slug' => 'location' ),
	    );

		register_taxonomy( self::NAME, array( 'product', 'post', 'designer' ), $args );

	}
}
