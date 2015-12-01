<?php

namespace Journal\Http\Middleware;

use DB, Closure, Schema;

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
        // check first if the settings table exists
        $tableExists = Schema::hasTable('settings');

        if ($tableExists) {
            // check the settings if journal is installed
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

        // tables of Journal is not yet installed
        // check first if the request came from AJAX
        if ($request->ajax()) {
            return response()->json([
                'message' => 'Journal is not yet installed properly.'], 500);
        }

        return redirect('/journal/#/installer');
    }
}
