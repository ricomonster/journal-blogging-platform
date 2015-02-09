<?php namespace Journal\Providers;

use Illuminate\Support\ServiceProvider;

class JournalServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		require __DIR__.'/../helpers.php';
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Journal\Repositories\Posts\PostRepositoryInterface',
			'Journal\Repositories\Posts\DbPostRepository'
		);

		$this->app->bind(
			'Journal\Repositories\Settings\SettingRepositoryInterface',
			'Journal\Repositories\Settings\DbSettingRepository'
		);

		$this->app->bind(
			'Journal\Repositories\Tags\TagRepositoryInterface',
			'Journal\Repositories\Tags\DbTagRepository'
		);

		$this->app->bind(
			'Journal\Repositories\Users\UserRepositoryInterface',
			'Journal\Repositories\Users\DbUserRepository'
		);
	}

}
