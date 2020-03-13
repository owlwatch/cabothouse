<?php
// lets grab the designers
$designers = new WP_Query([
    'post_type' => 'designer',
    'posts_per_page' => -1
]);
?>
<div class="chx-designers">
<?php
while( $designers->have_posts() ){
    $designers->the_post();
    $terms = wp_get_object_terms( get_the_ID(), \Theme\Taxonomy\Location::NAME );
    $src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
    ?>
    <a class="chx-designer" href="<?php echo get_permalink(); ?>">
    
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
    </a>
<?php
}
?>
</div>
<?php
wp_reset_query();
wp_reset_postdata();