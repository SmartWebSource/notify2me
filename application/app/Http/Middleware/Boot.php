<?php

namespace App\Http\Middleware;

use Closure;
use App\Classes\SMS;
use Auth;

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

        $themeAssets = $assets.'/themes/'.env('APP_THEME','simplex');

        $smsCredit = 0;//SMS::getCredit();

        view()->share(['assets' => $assets, 'themeAssets' => $themeAssets, 'smsCredit' => formatAmount($smsCredit)]);

        return $next($request);
    }
}
