<?php
namespace Theme;

/**
 * We will use the singleton pattern for classes that
 * implement wordpress hooks to prevent duplication
 *
 */
class Singleton
{
  /**
   * @var Base the instance of this singleton class
   */
  protected static $_instance=[];

  /**
   * @return Base the singleton instance
   */
  public static function getInstance()
  {
    $class = get_called_class();
    if( !isset( self::$_instance[$class] ) ){
      static::$_instance[$class] = new static();
    }
    return static::$_instance[$class];
  }

  /**
   * Alias for get_instance
   */
  public static function init()
  {
    return self::getInstance();
  }
}
