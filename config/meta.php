<?php
/**
 * Here you can define the metatags that every head html document will have.
 * You should ONLY define here the fixed metatags (tags that are static for
 * you entire site). Do not use this config to define dynamic metatags such as
 * usernames for authors, titles or descriptions because they are usually
 * set by the context (ex: Posts) and shouldn't be cached.
 */
return [
    /**
     * Basic metatags
     */
    'robots' => 'index, follow',

    'viewport' => 'width=device-width, initial-scale=1',

    'favicon' => [
        'type' => 'image/png', // image/x-icon
        'url' => 'favicon.png', // Name or path to the asset. It must be located in public dir.
    ],

    // The canonical URL usually is the same as app URL.
    // Change it if your site should point to another URL.
    'canonical-url' => config('app.url'),

    // If your site have a Facebook page.
    'facebook-domamin-verification' => null,


    /**
     * Social Networks fixed metatags
     * Facebook uses og tags.
     * Twitter uses twitter tags.
     */

    'og' => [
        // URL to the app.
        'url' => config('app.url'),
        'type' => 'website',
        // Banner image that should be displayed when a crawler visit the app
        'image' => null, // asset('url-to-your-img')        
    ],

    'twitter' => [
        // Set enabled as false if you website doesn't have any twitter account.
        'enabled' => true,
        'card' => 'summary',
        'site' => '@nuwebsco',
    ]

];
