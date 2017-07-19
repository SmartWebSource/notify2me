<?php

namespace App\Http\Middleware;

use Closure;

class Boot
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
        $assets = url('assets');

        $themeAssets = $assets.'/superhero';

        view()->share(['assets' => $assets, 'themeAssets' => $themeAssets]);

        return $next($request);
    }
}
