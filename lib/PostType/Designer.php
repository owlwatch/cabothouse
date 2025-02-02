<?php

namespace Theme\PostType;

use Theme\Singleton;

use Theme\Taxonomy\Designer as Taxonomy;

class Designer extends Singleton
{

	const NAME = 'designer';

	public function __construct()
	{
		add_action('init', [$this, 'register'], 1);
		add_filter('the_content', [$this, 'the_content'], 1);
	}

	public function register()
	{
		$labels = [
			"name"               => _x("Designers", "post type general name", "cabothouse"),
			"singular_name"      => _x("Designer", "post type singular name", "cabothouse"),
			"menu_name"          => _x("Designers", "admin menu", "cabothouse"),
			"name_admin_bar"     => _x("Designer", "add new on admin bar", "cabothouse"),
			"add_new"            => _x("Add New", "challenge", "cabothouse"),
			"add_new_item"       => __("Add New Designer", "cabothouse"),
			"new_item"           => __("New Designer", "cabothouse"),
			"edit_item"          => __("Edit Designer", "cabothouse"),
			"view_item"          => __("View Designer", "cabothouse"),
			"all_items"          => __("All Designers", "cabothouse"),
			"search_items"       => __("Search Designers", "cabothouse"),
			"parent_item_colon"  => __("Parent Designer:", "cabothouse"),
			"not_found"          => __("No Designers found.", "cabothouse"),
			"not_found_in_trash" => __("No Designers found in Trash.", "cabothouse")
		];

		register_post_type(self::NAME, [
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
			"supports"				=> ["title", "author", "editor", "thumbnail", "excerpts"],
			"show_in_rest"       	=> true,
			"rest_base"          	=> "designers",
			"rest_controller_class" => "WP_REST_Posts_Controller",
		]);
	}

	public function the_content($content)
	{
		if (!is_singular(self::NAME)) {
			return $content;
		}

		$email = get_field( 'email' );
    // [et_pb_section fb_built="1" _builder_version="4.10.6" _module_preset="default"][et_pb_row _builder_version="4.10.6" _module_preset="default" column_structure="2_5,3_5"][et_pb_column _builder_version="4.10.6" _module_preset="default" type="2_5"][et_pb_text _builder_version="4.10.6" _module_preset="default" hover_enabled="0" sticky_enabled="0"][/et_pb_text][/et_pb_column][et_pb_column _builder_version="4.10.6" _module_preset="default" type="3_5"][et_pb_text _builder_version="4.10.6" _module_preset="default" hover_enabled="0" sticky_enabled="0"][/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section]
    ob_start();
    ?>
    [et_pb_section fb_built="1" _builder_version="4.10.6" _module_preset="default"]
    [et_pb_row _builder_version="4.10.6" _module_preset="default" column_structure="2_5,3_5"]
    [et_pb_column _builder_version="4.10.6" _module_preset="default" type="2_5"]
    <div class="et_pb_module et_pb_image et_pb_image_0 et_always_center_on_mobile">
        <span class="et_pb_image_wrap ">
            <a href="mailto:<?php echo $email; ?>?bcc=info@cabothouse.com" target="_blank"><?php the_post_thumbnail( ); ?></a>
        </span>
    </div>
    [et_pb_text _builder_version="4.10.6" _module_preset="default" hover_enabled="0" sticky_enabled="0"]
    <p>Interior Designer<br>
<strong>
    <?php the_title(); ?>
    <?php 
    if( $title = get_field( 'title') ){
        ?>
    <span class="chx-designer__company-title">| <?php echo $title; ?></span>
        <?php
    }
    ?>
</strong>
</p>
<p>Location<br>
<?php
echo get_the_term_list( get_the_ID(), \Theme\Taxonomy\Location::NAME );
?>
</p>
<?php
$phone = get_field( 'phone' );
if( $phone ){
    ?>
<p>Phone<br>
<a href="tel:<?php echo preg_match('/[^\d]/', $phone); ?>"><?php echo $phone; ?></a>
    <?php
}
if( $email ){
    ?>
<p>Email<br>
<a href="mailto:<?php echo $email; ?>?bcc=info@cabothouse.com" target="_blank"><?php echo $email; ?></a>
    <?php
}
$terms = wp_get_object_terms( get_the_ID(), \Theme\Taxonomy\Location::NAME );

if( count( $terms ) ){
    $shadow = \Theme\Service\ShadowTaxonomy::getByTaxonomy( \Theme\Taxonomy\Location::NAME );
    $location = $shadow->getPost( $terms[0]->term_id );
    $html = do_shortcode('[location-designers id="'.$location->ID.'"]');
    if( preg_match('#<li#', $html ) ){
        ?>
<div>
    <p>
        Designers in <?php echo $location->post_title ?>
    </p>
    <?php
    echo $html;
    ?>
</div>
        <?php
    }
}

$page = get_page_by_path('interior-designers');
if( $page ){
    ?>
    <hr style="border-style: solid; border-width: 1px 0 0; border-color: #ccc;" />
<p>
<a 
    href="<?php echo get_permalink( $page ) ?>" 
    >
    <span class="fa fa-angle-left"></span> All Designers
</a>
</p>
    <?php
}
?>
    [/et_pb_text]
    [/et_pb_column]
    [et_pb_column _builder_version="4.10.6" _module_preset="default" type="3_5"]
    <?php
        $portfolio = get_field( 'portfolio' );
        if( !empty($portfolio) ){
            ?>
[et_pb_slider _builder_version="4.25.2" _module_preset="default" height="500px" hover_enabled="0" sticky_enabled="0" auto="on" animation_style="fade" auto_speed="5000"]
            <?php
            foreach( $portfolio as $image){
                ?>
[et_pb_slide _builder_version="4.25.2" _module_preset="default" title_text="dining room thumbnail" background_image="<?php echo $image['url'] ?>" background_enable_image="on" hover_enabled="0" sticky_enabled="0"]
[/et_pb_slide]
                <?php
            }
            ?>
[/et_pb_slider]
            <?php
        }
        ?>
[et_pb_text _builder_version="4.10.6" _module_preset="default" hover_enabled="0" sticky_enabled="0"]
        <div class="et_pb_module et_pb_text et_pb_text_1 et_pb_bg_layout_light  et_pb_text_align_left">
            <div class="et_pb_text_inner">
                <?php
                wp_reset_postdata();
                wp_reset_query();
                echo get_post()->post_content;
                ?>
            </div>
        </div> <!-- .et_pb_text -->
        <?php
        $designer_form_id = get_field( 'designer_contact_form_id', 'theme' );
        if( false && $designer_form_id ){
            ?>
        <div class="chx-designer-form">
            <hr style="border-style: solid; border-width: 1px 0 0; border-color: #ccc;" />
            <h4>Contact <?php echo get_field( 'first_name' ); ?></h4>
            <?php
            echo '[gravityform id="'.$designer_form_id.'" title="false" description="false" ajax="true"]';
        }
            ?>
        </div>
            <?php
        ?>
    [/et_pb_text]
    [/et_pb_column]
    [/et_pb_row]
    [/et_pb_section]
    <?php
    $content = do_shortcode( ob_get_clean() );

?>
		<style>
			.et_pb_slider .et_pb_slide_image {
				margin-top: 0 !important;
			}

			.et_pb_slide {
				padding: 0;
			}

			.et_pb_slide_image img {
				max-height: inherit !important;
				width: 100%;
				height: 100%;
			}

			.et_pb_slide_description {
				display: none !important;
			}

			.et_pb_slider .et_pb_slide_0 {
				background-color: #FFFFFF !important;
			}

			.et_pb_bg_layout_light .et-pb-arrow-next,
			.et_pb_bg_layout_light .et-pb-arrow-prev {
				color: #FFFFFF !important;
			}

			.et_pb_empty_slide {
				height: auto !important;
			}
		</style>
<?php
		echo $content;

	}
}
