<?php

namespace Theme;

class Theme extends Singleton
{
	const VERSION = '2.0.0';
	
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
}
