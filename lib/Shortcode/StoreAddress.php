<?php
namespace Theme\Shortcode;

class StoreAddress extends AbstractShortcode
{
	protected $tag = 'store_address';

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
