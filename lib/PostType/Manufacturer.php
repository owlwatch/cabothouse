<?php

namespace Theme\PostType;

use Theme\Singleton;

//use Theme\Taxonomy\Manufacturer as Taxonomy;

class Manufacturer extends Singleton
{

	const NAME = 'manufacturer';

	public function __construct()
	{
		add_action('init', [$this, 'register'], 1);
		add_filter('et_builder_post_types', [$this, 'filterBuilderPostTypes']);
		add_filter('the_content', [$this, 'the_content'], 10);
	}

	public function register()
	{
		$labels = [
			"name"               => _x("Manufacturers", "post type general name", "cabothouse"),
			"singular_name"      => _x("Manufacturer", "post type singular name", "cabothouse"),
			"menu_name"          => _x("Manufacturers", "admin menu", "cabothouse"),
			"name_admin_bar"     => _x("Manufacturer", "add new on admin bar", "cabothouse"),
			"add_new"            => _x("Add New", "challenge", "cabothouse"),
			"add_new_item"       => __("Add New Manufacturer", "cabothouse"),
			"new_item"           => __("New Manufacturer", "cabothouse"),
			"edit_item"          => __("Edit Manufacturer", "cabothouse"),
			"view_item"          => __("View Manufacturer", "cabothouse"),
			"all_items"          => __("All Manufacturers", "cabothouse"),
			"search_items"       => __("Search Manufacturers", "cabothouse"),
			"parent_item_colon"  => __("Parent Manufacturer:", "cabothouse"),
			"not_found"          => __("No manufacturers found.", "cabothouse"),
			"not_found_in_trash" => __("No manufacturers found in Trash.", "cabothouse")
		];

		register_post_type(self::NAME, [
			"labels"              	=> $labels,
			"public"              	=> true,
			"has_archive"           => false,
			"hierarchical"			=> false,
			"show_ui"				=> true,
			"show_in_menu"        	=> true,
			'exclude_from_search'   => true,
			'rewrite'               => [
				'with_front'          => false,
				'slug'                => 'manufacturers',
			],
			"menu_icon"				=> "dashicons-tag",
			"supports"				=> ["title", "author", "editor", "thumbnail", "excerpts"],
			"show_in_rest"       	=> true,
			"rest_base"          	=> "manufacturers",
			"rest_controller_class" => "WP_REST_Posts_Controller",
		]);
	}

	public function filterBuilderPostTypes($post_types)
	{
		$post_types[] = self::NAME;
		return $post_types;
	}

	public function the_content($content)
	{
		if (!is_singular(self::NAME)) {
			return $content;
		}
		// lets grab the layout
		$layout = get_field('manufacturer_layout', 'theme');
		if ($layout) {
			add_shortcode('manufacturer_content', function () use($content){
				ob_start();
				$website = get_field('website');
?>
				<h1 class="chx-manufacturer__title">
					<?php
					if ($website) {
					?>
						<a href="<?php echo $website; ?>" target="_blank">
						<?php
					}
					if (has_post_thumbnail()) {
						the_post_thumbnail('full', ['alt' => get_the_title()]);
					} else {
						the_title();
					}
					if ($website) {
						?>
						</a>
					<?php
					}
					?>
				</h1>
				<?php
				echo $content;
				if ($website) {
				?>
					<p style="text-align: center;">
						<a href="<?php echo $website; ?>" target="_blank">
							Visit Website
							<i class="fas fa-external-link-alt"></i>
						</a>
					</p>
			<?php
				}
				return ob_get_clean();
			});
			return do_shortcode($layout->post_content);
		}
	}
}
