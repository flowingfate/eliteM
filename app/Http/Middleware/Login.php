<?php

namespace App\Http\Middleware;

use Closure;

class Login
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
        // 出了验证是否已经登录，还要验证登录的身份是否相符
        // 否则学员登录之后也可以访问或通过作弊来访问admin和teacher
        // ！！！！！！！！！！！！！！！！
        // ！！！！！！！！！！！！！！！！
        // if(!session('user'))
        // {
        //     return response('未登陆');
        // }

        return $next($request);
    }
}
