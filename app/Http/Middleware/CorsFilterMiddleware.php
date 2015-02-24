<?php namespace Journal\Http\Middleware;

use Closure, Request;

class CorsFilterMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if (Request::getMethod() == "OPTIONS") {
            $headers = array(
                'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
                'Access-Control-Allow-Headers'=> 'X-Requested-With, content-type, Authorization',);
            return Response::make('', 200, $headers);
        }

		return $next($request);
	}

}
