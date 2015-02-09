<?php namespace Journal\Http\Middleware;

use Closure;
use Journal\Repositories\Settings\SettingRepositoryInterface;

class InstallationVerifier {
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
		if (!$this->settings->installation()) {
			// redirect to installer
			return redirect('installer');
		}

		return $next($request);
	}
}
