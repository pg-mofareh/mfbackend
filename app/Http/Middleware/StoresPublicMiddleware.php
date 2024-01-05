<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Stores;
use App\Models\Design;
class StoresPublicMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($store = Stores::where([['subdomain',$request->store]])->first()){
            if($store->is_active=="1" && $store->is_verify=="1" && $design= Design::where([['store',$store->id],['is_active',1],['is_verify',1]])->first()){
                return $next($request);
            }else{
                return redirect()->route('storespublic.disactive',['store'=>$store->subdomain]);
            }
        }else{
            return redirect()->route('main');
        }
    }
}
