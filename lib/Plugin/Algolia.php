<?php
namespace Theme\Plugin;

use Theme\Singleton;

class Algolia extends Singleton
{
	protected function __construct()
	{
		add_action( 'woocommerce_before_shop_loop', [$this, 'openTag'], 31 );
		add_action( 'woocommerce_after_shop_loop', [$this, 'closeTag'], 1 );
	}

	public function openTag()
	{
		?>
		<div id="algolia-wrapper"></div>
		<div class="woocommerce-hidden">
		<?php
	}

	public function closeTag()
	{
		?>
		</div>
		<?php
	}
}
