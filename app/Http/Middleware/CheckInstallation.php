<?php

namespace Journal\Http\Middleware;

use DB, Closure;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // check if its installed
        $installed = DB::table('settings')->where('setting', 'installed')->first();

        if (empty($installed)) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Endpoint not found.'], 404);
            }

            return redirect('/journal/#/installer');
        }

        return $next($request);
    }
}
