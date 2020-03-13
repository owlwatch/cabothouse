<?php
namespace Theme\PostType;

use Theme\Singleton;

use Theme\Taxonomy\Location as Taxonomy;

class Location extends Singleton
{

	const NAME = 'location';

	public function __construct()
	{
		add_action( 'init', [$this, 'register'], 1 );
		add_filter( 'the_content', [$this, 'contentFilter'] );
		add_filter( 'body_class', [$this, 'bodyClass'] );
		add_filter( 'et_builder_post_types', [$this, 'filterBuilderPostTypes'] );
	}

	public function register()
	{
		$labels = [
			"name"               => _x( "Locations", "post type general name", "cabothouse" ),
			"singular_name"      => _x( "Location", "post type singular name", "cabothouse" ),
			"menu_name"          => _x( "Locations", "admin menu", "cabothouse" ),
			"name_admin_bar"     => _x( "Location", "add new on admin bar", "cabothouse" ),
			"add_new"            => _x( "Add New", "challenge", "cabothouse" ),
			"add_new_item"       => __( "Add New Location", "cabothouse" ),
			"new_item"           => __( "New Location", "cabothouse" ),
			"edit_item"          => __( "Edit Location", "cabothouse" ),
			"view_item"          => __( "View Location", "cabothouse" ),
			"all_items"          => __( "All Locations", "cabothouse" ),
			"search_items"       => __( "Search Locations", "cabothouse" ),
			"parent_item_colon"  => __( "Parent Location:", "cabothouse" ),
			"not_found"          => __( "No locations found.", "cabothouse" ),
			"not_found_in_trash" => __( "No locations found in Trash.", "cabothouse" )
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
				'slug'                => 'locations',
			],
			"menu_icon"				=> "dashicons-building",
			"supports"				=> ["title","author","editor","thumbnail","excerpts"],
			"show_in_rest"       	=> true,
			"rest_base"          	=> "locations",
			"rest_controller_class" => "WP_REST_Posts_Controller",
    	]);
	}

	public function contentFilter( $content )
	{
		if( is_page( 'locations' )  ){
			return $this->locationsPage();
		}

		if( is_singular( self::NAME ) ){
			return $this->singleLocation() . $content;
		}

		return $content;

	}

	public function locationsPage()
	{
		$query = new \WP_Query([
			'post_type' => 'location',
			'posts_per_page' => -1,
			'orderby' => 'name',
			'order' => 'asc'
		]);


		$output = '';

		$pins = [];
		$rows = [];

		while( $query->have_posts() ){
			$query->the_post();
			$pins[] = $this->mapPin( get_the_ID() );
			$rows[] = $this->locationRow( get_the_ID() );
		}
		// we also need to replace the pin
		wp_reset_postdata();
		wp_reset_query();
		$pins = implode('', $pins );
		$map = do_shortcode('[et_pb_section bb_built="1" fullwidth="on" specialty="off" custom_margin="||60px"][et_pb_fullwidth_map child_filter_saturate="0%" _builder_version="3.17.6" address_lat="42.6694492" address_lng="-71.6469634" mouse_wheel="off" address="74 West St, Pepperell, MA 01463, USA"]'.$pins.'[/et_pb_fullwidth_map][/et_pb_section]');

		return $map.implode('',$rows);
	}

	public function mapPin( $id )
	{
		$name = get_post( $id  )->post_title;
		$address = get_field( 'address', $id );
		$location = get_field( 'location', $id );
		$lat = $location['lat'];
		$lng = $location['lng'];
		$address_attr = esc_attr( str_replace("\n", ' ', $address) );
		ob_start();
		?>[et_pb_map_pin _builder_version="3.17.6" zoom_level="15" pin_address_lat="<?php echo $lat ?>" pin_address_lng="<?php echo $lng ?>" title="<?php echo $name ?>" db_start_open="off" pin_address="<?php echo $address_attr ?>"]
<?php echo $address ?>
[/et_pb_map_pin]<?php
		return ob_get_clean();
	}

	public function locationRow( $id  )
	{
		global $SHORTCODE_POST_ID;
		$template = get_field( 'template_location_row', 'theme' );
		$SHORTCODE_POST_ID = $id;
		$output = do_shortcode( $template->post_content );
		$SHORTCODE_POST_ID = null;

		$location = get_field('location', $id );
		$output = preg_replace(
			'/(data(\-center)?\-lat)="(.*?)"/',
			'$1="'.$location['lat'].'"',
			$output
		);
		$output = preg_replace(
			'/(data(\-center)?\-lng)="(.*?)"/',
			'$1="'.$location['lng'].'"',
			$output
		);
		$output = preg_replace(
			'/(data-title)="(.*?)"/',
			'$1="'.esc_attr($location['address']).'"',
			$output
		);
		$output = preg_replace(
			'/(class="et_pb_map_pin".*?<h3[^>]+>)(.*?)<\/h3>/ism',
			'${1}'.$location['address'].'</h3>',
			$output
		);

		return $output;
	}

	public function singleLocation()
	{
		$sections = get_field( 'location_page_sections', 'theme' );

		if( empty($sections) ) return '';

		$output = '';
		foreach( $sections as $section ){
			$output.= $section->post_content;
		}

		$output = do_shortcode( $output );

		/*
		<div class="et_pb_map" data-center-lat="42.488990269668" data-center-lng="-71.210866138623" data-zoom="15" data-mouse-wheel="on" data-mobile-dragging="on"></div>
			<div class="et_pb_map_pin" data-initial="open" data-lat="42.486237" data-lng="-71.2110378" data-title="Burlington, MA">
			<h3 style="margin-top: 10px;">Burlington, MA</h3>
			<div class="infowindow">66 Mall Road<br />
Burlington, MA 01803</div>
		</div>
		 */

		$dom = new \DOMDocument();
		$internalErrors = libxml_use_internal_errors(true);
		$dom->loadHTML( $output );
		libxml_use_internal_errors($internalErrors);

		// lets find the
		$finder = new \DOMXPath( $dom );
		$classname="et_pb_map_container";
		$nodes = $finder->query("//*[contains(@class, '$classname')]");
		if( $nodes->length ){
			$name = get_post()->post_title;
			$address = get_field( 'address' );
			$location = get_field( 'location' );
			$lat = $location['lat'];
			$lng = $location['lng'];
			$center_lat = $lat + (42.488990269668 - 42.486237);
			$center_lng = $lng + (-71.210866138623 +71.2110378);
			$node = $nodes->item(0);

			$replace = <<<EOC
<div class="et_pb_map" data-center-lat="{$center_lat}" data-center-lng="{$center_lng}" data-zoom="15" data-mouse-wheel="on" data-mobile-dragging="on"></div>
<div class="et_pb_map_pin" data-initial="open" data-lat="{$lat}" data-lng="{$lng}" data-title="{$name}">
	<h3 style="margin-top: 10px;">{$name}</h3>
	<div class="infowindow">
		{$address}
	</div>
</div>
EOC;
		}

		// /wp_enqueue_script('db_pb_map_pin');

		while ($node->hasChildNodes())
        	$node->removeChild($node->firstChild);

		$fragment = $node->ownerDocument->createDocumentFragment();
	    $fragment->appendXML($replace);

		$node->appendChild( $fragment );

		$html = $dom->saveHTML();
		preg_match('/<body>(.*)?<\/body>/ims', $html, $matches );
		return $matches[1];
	}

	public function bodyClass( $classes )
	{
		if( is_singular( self::NAME ) ){
			$classes[] = 'et_full_width_page';
		}
		return $classes;
	}

	public function filterBuilderPostTypes( $post_types )
	{
		$post_types[] = self::NAME;
		return $post_types;
	}
}
