<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class Admin
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
        if(Auth::check()) {
            $user = Auth::user();
            $user_role = $user->id;
            if ($user_role == 0) {
                return $next($request);
            } elseif ($user_role==1) {
                return $next($request);
            }
        }else{
            return redirect('login');
        }
        return $next($request);
    }
}
