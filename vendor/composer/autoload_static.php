<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd1ea42ae4fc32ca7f4154aa00e71921d
{
    public static $prefixLengthsPsr4 = array (
        'N' => 
        array (
            'Notconnected\\Testing\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Notconnected\\Testing\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Notconnected\\Testing\\Test' => __DIR__ . '/../..' . '/src/Test.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd1ea42ae4fc32ca7f4154aa00e71921d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd1ea42ae4fc32ca7f4154aa00e71921d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd1ea42ae4fc32ca7f4154aa00e71921d::$classMap;

        }, null, ClassLoader::class);
    }
}