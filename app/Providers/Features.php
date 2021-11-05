<?php

namespace App\Providers;

class Features
{
    /**
     * Determine if the given feature is enabled.
     *
     * @param  string  $feature
     * @return bool
     */
    public static function enabled(string $feature)
    {
        return in_array($feature, config('app.features', []));
    }

    /**
     * Enable the authentication feature.
     *
     * @return string
     */
    public static function auth()
    {
        return 'auth';
    }

    /**
     * Enable the user management feature.
     *
     * @return string
     */
    public static function userManagement()
    {
        return 'user-management';
    }

    /**
     * Enable the posts feature.
     *
     * @return string
     */
    public static function posts()
    {
        return 'posts';
    }

    /**
     * Enable the quotes feature.
     *
     * @return string
     */
    public static function quotes()
    {
        return 'quotes';
    }
}