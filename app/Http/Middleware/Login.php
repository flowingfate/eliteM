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
        /**
         * 除了了验证是否已经登录，还要验证登录的身份是否相符
         * 否则不同身份之间登录之后可以互相访问了
         */
        $user = session('user');
        
        if(!$user)
        {
            // 理论上应该跳转到登录页面，但是如果是异步请求就不行
            return response('未登陆');
        }

        $role = $user['role'];
        $arr = explode('/',$request->path());
        if(!in_array($role,$arr)) return response('无权限访问');

        return $next($request);
    }
}
