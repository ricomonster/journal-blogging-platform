<?php namespace Journal\Http\Middleware;

use Journal\Repositories\Settings\SettingRepositoryInterface;
use Closure;

class InstalledVerifier {
	/**
	 * The setting repository implementation
	 *
	 * @var SettingRepositoryInterface
	 */
	protected $settings;

	/**
	 * Creates a new filter instance
	 *
	 * @param SettingRepositoryInterface $settings
	 */
	public function __construct(SettingRepositoryInterface $settings)
	{
		$this->settings = $settings;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($this->settings->installation()) {
			// show 404 page
			echo '404 page';
			return;
		}

		return $next($request);
	}
}
