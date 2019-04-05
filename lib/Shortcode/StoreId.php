<?php
namespace Theme\Shortcode;

class StoreId extends AbstractShortcode
{
	protected $tag = 'store_id';

	public function run( $atts=array(), $content='', $tag=null )
	{
		return get_the_ID();
	}

}
