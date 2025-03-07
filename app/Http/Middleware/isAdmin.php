<?php

namespace App\Http\Middleware;
use Closure;
use Auth;
use App\Mmodels\User;
use Illuminate\Http\Request;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user()&&Auth::user()->is_admin==1){
        return $next($request);
        }
        return redirect('/login');
        
        //return redirect()->intended('https://schooldrive.com.ng/educareprimary');
    }
}
