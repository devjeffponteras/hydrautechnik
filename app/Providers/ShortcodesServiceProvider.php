<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Shortcode;

class ShortcodesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Shortcode::register('latest news', 'App\Shortcodes\ArticlesShortcodes@latest');
        Shortcode::register('latest homepage', 'App\Shortcodes\ArticlesShortcodes@latest_homepage');
        Shortcode::register('related videos', 'App\Shortcodes\VideosShortcodes@related_videos');
    }
}
