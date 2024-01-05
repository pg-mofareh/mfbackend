<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Traits\MailTrait;
use App\Traits\GeneralTrait;
use App\Models\User;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Coupons;
use App\Models\Stores;
use App\Models\StoreSubs;
use App\Models\Templates;
use App\Models\FilesPublic;
use DB;
class DashboardController extends Controller
{
    use MailTrait;
    use GeneralTrait;

    function home(Request $req){
        return view('dashboard.home');
    }

    function users(Request $req){
        $users = User::with('roles')->get(['id','first_name','email']);
        return view('dashboard.users',['users'=>$users]);
    }

    function roles(Request $req){
        $roles = Role::all();
        return view('dashboard.roles',['roles'=>$roles]);
    }

    function permissions(Request $req){
        $permissions = Permission::all();
        return view('dashboard.permissions',['permissions'=>$permissions]);
    }

    function stores(Request $req){
        $stores = Stores::get();
        return view('dashboard.stores',['stores'=>$stores]);
    }

    function subscriptions(Request $req){
        $queryWhere = null;
        if (isset($req->type) && isset($req->value)) {
            if ($req->type == "search") {
                $queryWhere = function ($query) use ($req) {
                    $query->where('name', 'LIKE', '%' . $req->value . '%');
                };
            }
        }

        $subscriptions = StoreSubs::join('stores', 'st_subs.store', '=', 'stores.id')
        ->where($queryWhere)
        ->get([
            'st_subs.id',
            'stores.name AS store_name',
            'st_subs.start_at',
            'st_subs.end_at',
            'st_subs.payment_method'
        ]);
        return view('dashboard.subscriptions',['subscriptions'=>$subscriptions]);
    }

    function payment(Request $req){
        return view('dashboard.payment');
    }

    function categories(Request $req){
        $categories = Categories::get();
        return view('dashboard.categories',['categories'=>$categories]);
    }

    function products(Request $req){
        $products = Products::leftJoin('files', function ($join) {
            $join->on(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(products.images, '$[0]'))"), '=', 'files.id');
        })
        ->select(
            'products.id AS id',
            'products.name AS name',
            DB::raw("CASE WHEN JSON_UNQUOTE(JSON_EXTRACT(products.images, '$[0]')) IS NOT NULL THEN CONCAT('/storage', files.directories, '/', files.name, '.', files.extension) ELSE NULL END AS image")
        )
        ->get();        
        return view('dashboard.products',['products'=>$products]);
    }

    function coupons(Request $req){
        $coupons = Coupons::get();
        return view('dashboard.coupons',['coupons'=>$coupons]);
    }

    function design(Request $req){
        $templates = Templates::leftJoin('files_public', 'files_public.id', '=', 'templates.image')->get([
            'templates.id',
            'templates.blade',
            'templates.name',
            'templates.description',
            'templates.is_active',
            DB::raw("CASE WHEN templates.image IS NULL THEN NULL ELSE CONCAT('/storage', files_public.directories, '/', files_public.name, '.', files_public.extension) END AS image"),
        ]);
        return view('dashboard.design',['templates' => $templates]);
    }

    function files(Request $req, $directories = null){
        $files = FilesPublic::get(['id','title',DB::raw("CONCAT('/storage', directories, '/', name, '.', extension) AS image"),'name','extension','updated_at','created_at']);
        return view('dashboard.files', ['files' =>$files]);
    }
}
