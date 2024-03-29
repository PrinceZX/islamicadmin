<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; 
use App;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::guard('admin')->guest()) {
            if (!$request->ajax() || !$request->wantsJson()) {

                return redirect(route('admin.index'));
            }
        }
        if(session()->has('locale')){
            App::setLocale(session()->get('locale'));
        }
        
        $response = $next($request);
        return $response;
    }
}
