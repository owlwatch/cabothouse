<?php

namespace Theme;

class Theme extends Singleton
{
	const VERSION = '2.0.9';
	
	protected function __construct()
	{
		if( !is_admin() ){
			Front::init();
		}
		else {
			Admin::init();
		}

		Acf::init();
		Shortcodes::init();

		$this->registerPostTypesAndTaxonomies();
		$this->registerPlugins();
		$this->registerServices();

		$this->registerSidebars();

	}

	protected function registerPostTypesAndTaxonomies()
	{
		PostType\Location::init();
		PostType\Designer::init();
		PostType\Manufacturer::init();

		Taxonomy\Location::init();

		Service\ShadowTaxonomy::register(
			PostType\Manufacturer::NAME,
			'product_brand'
		);

		Service\ShadowTaxonomy::register(
			PostType\Location::NAME
		);
		add_action('init', function(){
			register_taxonomy_for_object_type( 'category', 'page' );
			register_taxonomy_for_object_type( 'post_tag', 'page' );
		});
	}

	protected function registerPlugins()
	{
		\Theme\Plugin\Algolia::init();
		\Theme\Plugin\ContactWidget::init();
	}

	protected function registerServices()
	{
		Service\GravityForms::init();
		Service\CollectionService::init();
	}

	public function registerSidebars()
	{
		register_sidebar([
			'name' => 'Logo Right',
			'id' => 'logo-right',
			'before_widget' => '<div class="logo-right">',
			'after_widget' => '</div>'
		]);
	}
}
