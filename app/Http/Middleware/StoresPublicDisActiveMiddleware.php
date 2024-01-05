<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Stores;
use App\Models\Design;
class StoresPublicDisActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($store = Stores::where([['subdomain',$request->store]])->first()){
            if($store->is_active=="0" || $store->is_verify=="0" || !$design= Design::where([['store',$store->id],['is_active',1],['is_verify',1]])->first()){
                $request->merge(['middleinfo'=>['store'=>$store->name]]);
                return $next($request);
            }else{
                return redirect()->route('storespublic.main',['store'=>$store->subdomain]);
            }
        }else{
            return redirect()->route('main');
        }
    }
}
