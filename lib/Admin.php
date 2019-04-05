<?php
namespace Theme;

class Admin extends Singleton
{
	protected function __construct()
	{
		if( function_exists( 'acf_add_options_page' ) ){
			acf_add_options_page([
				'menu_title' => 'Theme Options',
				'page_title' => 'Theme Options',
				'capability' => 'manage_options',
				'menu_slug' => 'theme',
				'post_id' => 'theme',
				'position' => '2.1'
			]);
		}

		Scripts::init();

		add_action( 'admin_head', function(){
			?>
			<style type="text/css">
			.ac-image {
				background-color: transparent !important;
			}
			.ac-image > img {
				max-width: 100% !important;
			}
			</style>
			<?php
		});
	}
}
