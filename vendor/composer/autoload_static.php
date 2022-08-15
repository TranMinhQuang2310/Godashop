<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9dda4dca112a322a3bbfffa050d7b234
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9dda4dca112a322a3bbfffa050d7b234::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9dda4dca112a322a3bbfffa050d7b234::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9dda4dca112a322a3bbfffa050d7b234::$classMap;

        }, null, ClassLoader::class);
    }
}