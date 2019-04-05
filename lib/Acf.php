<?php

namespace Theme;

class Acf extends Singleton
{

	protected function __construct()
	{
		add_action('acf/init', [$this, 'setGoogleApiKey'] );
	}

	public function setGoogleApiKey()
	{
		acf_update_setting('google_api_key', 'AIzaSyBBgyrqE1jR37r3rstdik3cpsZyAptMDA0');
	}

}
