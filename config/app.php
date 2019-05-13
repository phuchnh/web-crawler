<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Enabled Plugins
    |--------------------------------------------------------------------------
    |
    | The class names of the TypeRocket plugins you wish to enable.
    |
    */
    'plugins' => [
        '\TypeRocketSEO\Plugin',
        '\TypeRocketPageBuilder\Plugin',
        '\TypeRocketThemeOptions\Plugin',
        '\TypeRocketDev\Plugin',
    ],

    /*
    |--------------------------------------------------------------------------
    | Enabled Features
    |--------------------------------------------------------------------------
    |
    | Options to control what features you can use on the site.
    |
    */
    'features' => [
        'gutenberg' => true,
        'posts_menu' => true,
        'comments' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Debug
    |--------------------------------------------------------------------------
    |
    | Turn on Debugging for TypeRocket. Set to false to disable.
    |
    */
    'debug' => immutable('WP_DEBUG', true),

    /*
    |--------------------------------------------------------------------------
    | Seed
    |--------------------------------------------------------------------------
    |
    | A 'random' string of text to help with security from time to time.
    |
    */
    'seed' => 'seed_5cd8d5d024dc0',

    /*
    |--------------------------------------------------------------------------
    | Class Overrides
    |--------------------------------------------------------------------------
    |
    | Set the classes to use as the default for helper functions.
    |
    */
    'class' => [
        'icons' => '\TypeRocket\Elements\Icons',
        'user' => '\App\Models\User',
        'form' => '\TypeRocket\Elements\Form'
    ],


    /*
    |--------------------------------------------------------------------------
    | TypeRocket Rooting
    |--------------------------------------------------------------------------
    |
    | The templates to use for the TypeRocket theme. Set to false if using
    | a theme or `templates` if using core for templates. Must be using
    | TypeRocket as root.
    |
    */
    'root' => [
        'use_root' => false,
        'theme' => 'templates',
    ],

    /*
    |--------------------------------------------------------------------------
    | Assets Version
    |--------------------------------------------------------------------------
    |
    | The version of TypeRocket core assets. Changing this can help bust
    | browser caches.
    |
    */
    'assets' => '4.0.8'

];
