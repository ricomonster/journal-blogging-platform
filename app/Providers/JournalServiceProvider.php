<?php

namespace Journal\Providers;

use Illuminate\Support\ServiceProvider;

class JournalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__.'/../functions.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Journal\Repositories\Post\PostRepositoryInterface',
            'Journal\Repositories\Post\DbPostRepository');

        $this->app->bind(
            'Journal\Repositories\Setting\SettingRepositoryInterface',
            'Journal\Repositories\Setting\DbSettingRepository');

        $this->app->bind(
            'Journal\Repositories\Tag\TagRepositoryInterface',
            'Journal\Repositories\Tag\DbTagRepository');

        $this->app->bind(
            'Journal\Repositories\User\UserRepositoryInterface',
            'Journal\Repositories\User\DbUserRepository');
    }
}
