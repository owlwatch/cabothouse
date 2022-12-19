<?php
namespace Theme\Plugin;

use Theme\Singleton;

class Facebook extends Singleton
{
	protected function __construct()
	{
		add_filter('gform_confirmation',[$this, 'filterConfirmationBefore'],9, 4);
		add_filter('gform_confirmation',[$this, 'filterConfirmationAfter'],11, 4);
	}

	public function filterConfirmationBefore($confirmation, $form, $entry, $ajax)
	{
		if( !$ajax ){
			return $confirmation;
		}
		$this->confirmation = $confirmation;
	}
	public function filterConfirmationAfter($confirmation, $form, $entry, $ajax)
	{
		if( !$ajax ){
			return $confirmation;
		}
		return $ajax ? $this->confirmation : $confirmation;
	}
}
