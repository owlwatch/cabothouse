<?php
namespace Theme\Shortcode;

class DesignersGrid extends AbstractShortcode
{
	protected $tag = 'designers-grid';

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
