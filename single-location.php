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
			<h1 style="text-align: center; padding: 0.5em; background: #f2f2f2; border-bottom: 1px solid #d8d8d8;">
				Locations: <?php the_title(); ?>
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
