<?php
namespace Theme\Plugin;

use Theme\Singleton;
use Theme\PostType\Location;

class ContactWidget extends Singleton
{
	protected function __construct()
	{
		if( !is_admin() ){
			add_action( 'wp_footer', [$this, 'output'] );
		}
		add_filter( 'gform_pre_render', [$this, 'preRenderForm'] );
	}

	public function output()
	{
		get_template_part( 'templates/plugin/contact-widget' );
	}

	public function preRenderForm( $form )
	{
		foreach( $form['fields'] as &$field ){

			if( $field->type !== 'select' || $field->inputName !== 'location' ){
				continue;
			}

			$locations = get_posts([
				'post_type' => Location::NAME,
				'orderby' => 'name',
				'order' => 'asc',
				'posts_per_page' => -1
			]);

			$choices = [];
			foreach( $locations as $location ){
				$choices[] = ['text'=>$location->post_title, 'value'=>$location->ID];
			}
			$field->placeholder = 'Select a Store';
			$field->choices = $choices;
		}
		return $form;
	}
}
