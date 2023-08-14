<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (Auth::check() && $user->hasRole(['super admin', 'admin']) && $user->email_verified_at!=null) {
            return $next($request);
        }elseif(Auth::check() && $user->hasRole(['super admin', 'admin']) && $user->email_verified_at==null){
            return redirect()->route('auth.verify');
        }
        
        return redirect('/auth/login');
    }
}
