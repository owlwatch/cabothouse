<?php

namespace Theme;
/**
 * Shortcodes Factory
 */
class Shortcodes
{

	/**
	 * collection of all instantiated models
	 */
	protected static $instances=[];


	/**
	 * Initiate all models
	 */
	public static function init()
	{

		$dir = dir(__DIR__.'/Shortcode');
		while( false !== ($file = $dir->read()) ){
			if( !preg_match('#^(\.|Abstract)#i', $file) ){
				$name = substr($file,0,strlen($file)-4);
				$class = "\\Theme\\Shortcode\\$name";
				$instance = $class::getInstance();
				self::$instances[$instance->getTag()] = $class::getInstance();
			}
		}

		add_filter( 'the_content', [__CLASS__, 'cleanup'] );
		add_filter( 'acf_the_content', [__CLASS__, 'cleanup'] );
	}

	public static function get( $name )
	{
		return self::$instances[$name];
	}

	public static function getAll()
	{
		return self::$instances;
	}

	public static function cleanup( $content )
	{
		// array of custom shortcodes requiring the fix
		$block = join( "|", array_keys(self::$instances) );

		// opening tag
		$rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);

		// closing tag
		$rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);

		return $rep;
	}
}
