<?php
namespace Theme;

class Scripts extends Singleton
{
	protected function __construct()
	{
		add_action( 'admin_init', [$this, 'controller'] );
	}

	public function controller()
	{
		if( !isset( $_REQUEST['theme-script'] ) ){
			return;
		}
		$fn = $_REQUEST['theme-script'];
		if( !is_callable( [$this, $fn] ) ){
			return;
		}

		header( 'Content-Type: text/plain; charset=utf-8' );
		$this->$fn();
		exit;
	}

	protected function test()
	{
		echo 'Test.';
	}

	protected function syncBrands()
	{
		$posts = get_posts([
			'post_type' => PostType\Manufacturer::NAME,
			'posts_per_page' => -1
		]);

		foreach( $posts as $post ) wp_update_post( $post );
	}

	protected function syncLocations()
	{
		$posts = get_posts([
			'post_type' => PostType\Location::NAME,
			'posts_per_page' => -1
		]);

		foreach( $posts as $post ) wp_update_post( $post );
	}

	protected function testAttachmentManufacturers()
	{
		$posts = get_posts([
			'post_type' => 'attachment',
			'posts_per_page' => -1
		]);

		foreach( $posts as $post ){
			$manufacturer = get_post_meta( $post->ID, 'manufacturer', true );
			if( !empty( $manufacturer ) ){
				wp_set_post_terms( $post->ID, (int) $manufacturer, 'product_brand' );
			}
		}
	}

	protected function importBrandsFromTax()
	{
		$terms = get_terms([
			'taxonomy' => 'product_brand',
			'hide_empty' => false
		]);
		foreach( $terms as $term ){
			// print_r( $term );
			// print_r([
			// 	'title' => $term->name,
			// 	'slug' => $term->slug,
			// 	'thumbnail_id' => get_term_meta( $term->term_id, 'thumbnail_id', true )
			// ]);

			$manufacturers = get_posts([
				'post_status' => 'any',
				'name' => $term->slug,
				'post_type' => 'manufacturer'
			]);

			if( empty( $manufacturers ) ){
				// lets create this
				$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
				$id = wp_insert_post([
					'post_title' => $term->name,
					'post_content' => $term->description,
					'post_name' => $term->slug,
					'post_type' => 'manufacturer',
					'post_status' => 'publish'
				]);
				if( $thumbnail_id ){
					set_post_thumbnail( $id, $thumbnail_id );
				}

				echo "CREATE {$term->name}\n";
			}
		}
	}
}
