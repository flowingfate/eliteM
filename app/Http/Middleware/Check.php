<?php

namespace App\Http\Middleware;

use Closure;

class Check
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
    **/
    public function handle($request, Closure $next, $idString, $msg)
    {
        if(!isTest())
        {
            $id = $request->input($idString,0);
            $user = session('user');
            $ID = $user['id'];
            if(intval($id)!=intval($ID)) return response($msg);
        }
        
        return $next($request);
    }
}
