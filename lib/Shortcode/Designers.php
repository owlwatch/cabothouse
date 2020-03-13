<?php
namespace Theme\Shortcode;

class Designers extends AbstractShortcode
{
	protected $tag = 'designers';

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
