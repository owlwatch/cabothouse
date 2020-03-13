<div class="location-designers <?php echo $class; ?>">
    <?php if( is_singular( \Theme\PostType\Location::NAME ) ){
        ?>
    <h4 class="location-designers">
        Our Designers
    </h4>
        <?php
    }
    ?>
    <ul class="location-designers__list">
    <?php
    usort( $query->posts, function($a, $b){
    
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
    while( $query->have_posts() ){
        $query->the_post();
        if( is_singular() && get_queried_object_id() === get_the_ID() ){
            continue;
        }
        ?>
        <li class="location-designers__list-item">
            <a href="<?php echo get_permalink(); ?>">
                <span class="location-designers__thumbnail"
                    style="background-image: url(<?php the_post_thumbnail_url( 'small' ); ?>)">
                </span>
                <div>
                    <?php the_title(); ?>
                    <?php 
                    if( $title = get_field( 'title') ){
                        ?>
                    <span class="chx-designer__company-title"><br /><?php echo $title; ?></span>
                        <?php
                    }
                    ?>
                </div>
            </a>
        </li>
        <?php
    }
    wp_reset_postdata();
    wp_reset_query();
    ?>
    </ul>
</div>