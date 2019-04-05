<?php

namespace Theme\Shortcode;
use Theme\Singleton;

abstract class AbstractShortcode extends Singleton
{
	protected $tag;

	protected function __construct()
	{
		if( !isset( $this->tag ) ){
			throw new Exception( 'Class extending AbstractShortcode must define a tag property' );
		}
		$this->addShortcode();
	}

	protected function addShortcode()
	{
		add_shortcode( $this->getTag(), [$this, 'doShortcode'] );
	}

	public function doShortcode( $atts, $content, $tag )
	{
		ob_start();
		$this->run( $atts, $content, $tag );
		$output = ob_get_clean();
		return $output;
	}

	public function getTag()
	{
		return $this->tag;
	}

	public function template( $vars, $template=null )
	{
		if( !$template ){
			$template = 'templates/shortcodes/'.$this->tag.'.php';
		}
		if( !preg_match('/\.php$/', $template ) ){
			$template .= '.php';
		}

		if( !($tmpl = locate_template( $template )) ){
			return false;
		}

		extract( $vars );
		include $tmpl;

		return true;
	}

	abstract public function run( $atts=array(), $content='', $tag='' );
}
