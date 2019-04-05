<?php
namespace Theme\Shortcode;

class StoreName extends AbstractShortcode
{
	protected $tag = 'store_name';

	public function run( $atts=array(), $content='', $tag=null )
	{

		$atts = shortcode_atts([
			'class' => '',
			'link'  => false
		], $atts, $tag );

		$this->template([
			'link'          => $atts['link'],
			'content'       => $content,
			'class'         => $atts['class']
		]);

	}

}
