<?php
add_filter('body_class', function( $classes ){
	$classes[] = 'et_full_width_page';
	return $classes;
});

get_header();
// lets grab the layout
?>
<div id="main-content">
    <?php while ( have_posts() ) : the_post(); 
        $email = get_field( 'email' );
        ?>
        <h1 class="screen-reader-text">
            <?php the_title(); ?>
        </h1>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
        <div class="et_pb_section et_pb_section_0 et_section_regular">
				
				
				
				
                <div class="et_pb_row et_pb_row_0">
            <div class="et_pb_column et_pb_column_2_5 et_pb_column_0    et_pb_css_mix_blend_mode_passthrough">
            
            
            <div class="et_pb_module et_pb_image et_pb_image_0 et_always_center_on_mobile">
            
            
            <span class="et_pb_image_wrap ">
                <a href="mailto:<?php echo $email; ?>?bcc:info@cabothouse.com" target="_blank"><?php the_post_thumbnail( ); ?></a>
            </span>
        </div><div class="et_pb_module et_pb_text et_pb_text_0 et_pb_bg_layout_light  et_pb_text_align_left">
            
            
            <div class="et_pb_text_inner">
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
<a href="mailto:<?php echo $email; ?>" target="_blank"><?php echo $email; ?></a>
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
            </div>
        </div> <!-- .et_pb_text -->
        </div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_3_5 et_pb_column_1    et_pb_css_mix_blend_mode_passthrough">
            
        <?php
        echo "<!--";
        print_r( get_field( 'portfolio' ) );
        echo "-->";
        $portfolio = get_field( 'portfolio' );
        if( !empty($portfolio) ){
            ob_start();
            ?>
            [et_pb_slider show_pagination="off" admin_label="Designer Portfolio Photos" _builder_version="3.21.1" module_class="slider-4-3"]
            <?php
            foreach( $portfolio as $image){
                ?>
            [et_pb_slide _builder_version="3.21.1" background_image="<?php echo $image['url'] ?>"]
            [/et_pb_slide]
                <?php
            }
            ?>
            [/et_pb_slider]
            <?php
        
            echo do_shortcode( ob_get_clean() );
        }
        ?>
        
        <div class="et_pb_module et_pb_text et_pb_text_1 et_pb_bg_layout_light  et_pb_text_align_left">
            
            
            <div class="et_pb_text_inner">
                <?php
                wp_reset_postdata();
                wp_reset_query();
                the_content();
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
            echo do_shortcode('[gravityform id="'.$designer_form_id.'" title="false" description="false" ajax="true"]');
        }
            ?>
        </div>
            <?php
        ?>
        </div> <!-- .et_pb_column -->
            
            
        </div> <!-- .et_pb_row -->
            
            
        </div>


		</article> <!-- .et_pb_post -->

	<?php endwhile; ?>
</div>
<?php
get_footer();
?>
