<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Stores;
class JoinUsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if(Auth::check() && $user->hasRole(['super admin', 'admin'])){
            return redirect()->route('dashboard.home');
        }elseif(Auth::check() && !$user->hasRole(['super admin', 'admin'])){
            if($store = Stores::where('user',$user->id)->first()){
                return redirect()->route('stores.dashboard',['store'=>$store->subdomain]);
            }else{
                $request->merge(['middleinfo'=>['user'=>$user->id]]);
                return $next($request);
            }
        }        
        return redirect()->route('auth.login'); 
    }
}
