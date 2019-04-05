<?php
namespace Theme\PostType;

use Theme\Singleton;

use Theme\Taxonomy\Designer as Taxonomy;

class Designer extends Singleton
{

	const NAME = 'designer';

	public function __construct()
	{
		add_action( 'init', [$this, 'register'], 1 );
	}

	public function register()
	{
		$labels = [
			"name"               => _x( "Designers", "post type general name", "cabothouse" ),
			"singular_name"      => _x( "Designer", "post type singular name", "cabothouse" ),
			"menu_name"          => _x( "Designers", "admin menu", "cabothouse" ),
			"name_admin_bar"     => _x( "Designer", "add new on admin bar", "cabothouse" ),
			"add_new"            => _x( "Add New", "challenge", "cabothouse" ),
			"add_new_item"       => __( "Add New Designer", "cabothouse" ),
			"new_item"           => __( "New Designer", "cabothouse" ),
			"edit_item"          => __( "Edit Designer", "cabothouse" ),
			"view_item"          => __( "View Designer", "cabothouse" ),
			"all_items"          => __( "All Designers", "cabothouse" ),
			"search_items"       => __( "Search Designers", "cabothouse" ),
			"parent_item_colon"  => __( "Parent Designer:", "cabothouse" ),
			"not_found"          => __( "No Designers found.", "cabothouse" ),
			"not_found_in_trash" => __( "No Designers found in Trash.", "cabothouse" )
		];

		register_post_type( self::NAME, [
			"labels"              	=> $labels,
			"public"              	=> true,
			"has_archive"           => false,
			"hierarchical"			=> false,
			"show_ui"				=> true,
			"show_in_menu"        	=> true,
			'rewrite'               => [
				'with_front'          => false,
				'slug'                => 'designers',
			],
			"menu_icon"				=> "dashicons-id-alt",
			"supports"				=> ["title","author","editor","thumbnail","excerpts"],
			"show_in_rest"       	=> true,
			"rest_base"          	=> "designers",
			"rest_controller_class" => "WP_REST_Posts_Controller",
    	]);
	}
}
