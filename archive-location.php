<?php get_header(); ?>

<div id="main-content">
<?php
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$post_format = et_pb_post_format(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
			<?php
			the_content();
			?>
		</article> <!-- .et_pb_post -->
<?php
		endwhile;

		if ( function_exists( 'wp_pagenavi' ) )
			wp_pagenavi();
		else
			get_template_part( 'includes/navigation', 'index' );
	else :
		get_template_part( 'includes/no-results', 'index' );
	endif;
?>
</div> <!-- #main-content -->

<?php

get_footer();
