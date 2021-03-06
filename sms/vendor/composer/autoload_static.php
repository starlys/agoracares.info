<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit06cf2403e3ee5c248bf777e744391561
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/Twilio',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit06cf2403e3ee5c248bf777e744391561::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit06cf2403e3ee5c248bf777e744391561::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
