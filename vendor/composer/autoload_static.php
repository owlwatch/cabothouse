<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite2dc9da2308a8040177c7248e2fb41b3
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Theme\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Theme\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite2dc9da2308a8040177c7248e2fb41b3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite2dc9da2308a8040177c7248e2fb41b3::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
