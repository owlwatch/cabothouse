<?php
// lets grab the manufacturers
$designers = new WP_Query([
    'post_type' => 'designer',
    'posts_per_page' => -1
]);
// sort designers by location
$locations = get_terms('location',[
    'orderby' => 'name'
]);
// map locations to there id
$location_map = [];
foreach( $locations as $location ){
    $location_map[$location->term_id] = $location;
    $location_map[$location->term_id]->designers=[];
}

usort( $designers->posts, function($a, $b){
    
    $a->isManager = preg_match('/manager/i', get_field( 'title', $a ) );
    $b->isManager = preg_match('/manager/i', get_field( 'title', $b ) );
    if( $a->isManager && !$b->isManager ){
        return -1;
    }
    if( $b->isManager && !$a->isManager ){
        return 1;
    }
    return strcasecmp( $a->post_title, $b->post_title );
});

// set the 
foreach( $designers->posts as $designer ){
    $terms = wp_get_object_terms( $designer->ID, 'location' );
    $designer->locations = $terms;
    foreach( $terms as $term ){
        $location_map[$term->term_id]->designers[] = $designer;
    }
}
?>

<div class="chx-designers-locations">
    <?php
    foreach( $location_map as $location_term ){
        if( !count( $location_term->designers ) ){
            continue;
        }
        $shadow = \Theme\Service\ShadowTaxonomy::getByTaxonomy( 'location' );
        $location = $shadow->getPost( $location_term->term_id );
        ?>
    <div class="chx-designers-location">
        <div class="et_pb_row">
            <div class="et_pb_column et_pb_column_4_4">
                
                <h3 class="chx-designers-location__title">
                    <a href="<?php echo get_permalink( $location ) ?>">
                        <?php echo $location->post_title; ?>
                    </a>
                </h3>
                <div class="chx-designers">
                <?php
                global $post;
                foreach( $location_term->designers as $designer ){
                    $post = $designer;
                    setup_postdata( $post );
                    $terms = wp_get_object_terms( get_the_ID(), \Theme\Taxonomy\Location::NAME );
                    $src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
                    ?>
                    <a class="chx-designer" href="<?php echo get_permalink(); ?>">
                    <!--
                        <div class="chx-designer__card">

                            <div class="chx-designer__card-front" style="background-image: url(<?php echo $src[0]; ?>);">
                                <h3 class="chx-designer__card-title chx-designer__card-title--front">
                                    <?php the_title(); ?>
                                </h3>
                            </div>

                            <div class="chx-designer__card-back">
                                <div class="chx-designer__card-info">
                                    <h3 class="chx-designer__card-title chx-designer__card-title--back">
                                        <?php the_title(); ?>
                                    </h3>
                                    <div class="chx-designer__card-bio">
                                        <?php the_content(); ?>
                                    </div>
                                    <div class="chx-designer__card-location">
                                        <?php
                                        echo get_the_term_list( get_the_ID(), \Theme\Taxonomy\Location::NAME );
                                        ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        -->
                        <div class="chx-designer__image" style="background-image: url(<?php echo $src[0]; ?>);">
                        </div>
                        <h3 class="chx-designer__title">
                            <?php the_title(); ?>
                            <?php 
                            if( $title = get_field( 'title') ){
                                ?>
                            <span class="chx-designer__company-title">| <?php echo $title; ?></span>
                                <?php
                            }
                            ?>
                        </h3>
                    </a>
                <?php
                }
                ?>
                </div>
            </div>
        </div>
    </div>
        <?php
    }
    ?>
</div>
<?php
wp_reset_query();
wp_reset_postdata();