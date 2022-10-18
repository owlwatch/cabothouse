<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit77e1850b1ee62786a53ab2d461a92f46
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

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit77e1850b1ee62786a53ab2d461a92f46::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit77e1850b1ee62786a53ab2d461a92f46::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit77e1850b1ee62786a53ab2d461a92f46::$classMap;

        }, null, ClassLoader::class);
    }
}
