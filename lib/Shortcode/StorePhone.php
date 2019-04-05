<?php
namespace Theme\Shortcode;

class StorePhone extends AbstractShortcode
{
	protected $tag = 'store_phone';

	public function run( $atts=array(), $content='', $tag=null )
	{

		$atts = shortcode_atts([
			'class'                 => '',
		], $atts, $tag );

		$this->template([
			'content'       => $content,
			'class'         => $atts['class']
		]);

	}

}
