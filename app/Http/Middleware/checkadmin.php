<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; 
use App\Models\AdminModel;
use App;

class checkadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard =null)
    {
        if(Auth::guard('admin')->user()->type != 1){
            return back()->with('error',__('label.you_have_no_right_to_add_edit_and_delete'));
        }
        return $next($request);
    }
}
