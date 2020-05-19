<?php
add_filter('body_class', function( $classes ){
	$classes[] = 'et_full_width_page';
	return $classes;
});
get_header();
?>
<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<h1 class="screen-reader-text">
				<?php the_title(); ?>
			</h1>
			<?php
			the_content();
			?>
		</div>
	</div>
</div>
<?php
get_footer();
?>
