<?php //-->

namespace Journal\Providers;

use Illuminate\Support\ServiceProvider;
use Journal\Repositories\User\DbUserRepository;

class JournalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__.'/../Http/helpers.php';
        // add your file here...
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Journal\Repositories\Settings\SettingsRepositoryInterface',
            'Journal\Repositories\Settings\DbSettingsRepository');

        $this->app->bind(
            'Journal\Repositories\Post\PostRepositoryInterface',
            'Journal\Repositories\Post\DbPostRepository');

        $this->app->bind(
            'Journal\Repositories\Tag\TagRepositoryInterface',
            'Journal\Repositories\Tag\DbTagRepository');

        $this->app->bind(
            'Journal\Repositories\User\UserRepositoryInterface',
            'Journal\Repositories\User\DbUserRepository');
    }
}
