<?php

namespace App\Http\Middleware;

use Closure;

class WechatLoign
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
        if ($request->session()->has('wechat'))
            return $next($request);
        return redirect('/wechat/login');
    }
}
