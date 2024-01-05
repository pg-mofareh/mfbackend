<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Stores;
class StoresDashboardMiddleware
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentRouteName = $request->route()->getName();
        $user = Auth::user();
        if(Auth::check() && $user->hasRole(['super admin', 'admin'])){
            if ($currentRouteName === 'stores.dashboard.files.searchproducts' || $currentRouteName === 'stores.dashboard.files.updateproductimage') {
                $this->returnErrors(['حدث خطأ ما']);
            }else{
                return redirect()->route('dashboard.home');
            }
        }elseif(Auth::check() && !$user->hasRole(['super admin', 'admin'])){
            if($store = Stores::where('user',$user->id)->first()){
                $requestedStore = $request->route('store');
                if ($requestedStore !== $store->subdomain) {
                    if ($currentRouteName === 'stores.dashboard.files.searchproducts' || $currentRouteName === 'stores.dashboard.files.updateproductimage') {
                        $this->returnErrors(['حدث خطأ ما']);
                    }else{
                        return redirect()->route($currentRouteName, ['store' => $store->subdomain]);
                    }
                }
                $request->merge(['middleinfo'=>[
                    'store' => $store->id,
                    'user'=>$user->id,
                    'user_fistname' => $user->first_name,
                    'user_lastname'=>$user->last_name,
                    'name' => $store->name,
                    'email'=>$user->email,
                    'subdomain' => $store->subdomain,
                    'logo'=>$store->logo,
                    'location'=>$store->location,
                ]]);
                return $next($request);              
            }else{
                if ($currentRouteName === 'stores.dashboard.files.searchproducts' || $currentRouteName === 'stores.dashboard.files.updateproductimage') {
                    $this->returnErrors(['حدث خطأ ما']);
                }else{
                    return redirect()->route('main.join-us');
                }                
            }
        }    
        
        if($currentRouteName === 'stores.dashboard.files.searchproducts' || $currentRouteName === 'stores.dashboard.files.updateproductimage') {
            $this->returnNeedLogin();
        }else{
            return redirect()->route('auth.login');
        }     
    }
}
