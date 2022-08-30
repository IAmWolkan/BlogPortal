<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7237d96893013974e335e2c80d619bf5
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Wollbro\\Blogportal\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Wollbro\\Blogportal\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7237d96893013974e335e2c80d619bf5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7237d96893013974e335e2c80d619bf5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7237d96893013974e335e2c80d619bf5::$classMap;

        }, null, ClassLoader::class);
    }
}
