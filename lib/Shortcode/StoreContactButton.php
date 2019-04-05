<?php
namespace Theme\Shortcode;

class StoreContactButton extends AbstractShortcode
{
	protected $tag = 'store_contact_button';

	public function run( $atts=array(), $content='', $tag=null )
	{
		$this->template([
			'content'       => $content
		]);
	}

}
