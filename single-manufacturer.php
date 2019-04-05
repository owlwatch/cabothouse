<?php
add_filter('body_class', function( $classes ){
	$classes[] = 'et_full_width_page';
	return $classes;
});

get_header();
// lets grab the layout
$layout = get_field( 'manufacturer_layout', 'theme' );
if( $layout ){
	add_shortcode( 'manufacturer_content', function(){
		ob_start();
		$website = get_field( 'website' );
		?>
		<h1 class="chx-manufacturer__title">
			<?php
			if( $website ){
				?>
				<a href="<?php echo $website; ?>" target="_blank">
				<?php
			}
			if( has_post_thumbnail() ){
				the_post_thumbnail('full', ['alt' => get_the_title()]);
			}
			else {
				the_title();
			}
			if( $website ){
				?>
				</a>
				<?php
			}
			?>
		</h1>
		<?php
		the_content();
		if( $website ){
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
?>
<div id="main-content">
	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php echo do_shortcode($layout->post_content); ?>
		</article> <!-- .et_pb_post -->

	<?php endwhile; ?>
</div>
<?php
}
else {
	?>
<div id="main-content">
	<div class="container">
		<p>
			Manufacturer layout is not set.
		</p>
	</div>
	<?php
}
get_footer();
?>
