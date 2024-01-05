<?php

namespace App\Http\Controllers;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Stores;
use App\Models\Files;
use App\Models\Subscriptions;
use App\Models\StoreSubs;
use App\Models\Transfers;
use App\Models\User;
use App\Models\Templates;
use App\Models\Design;
use DB;
class StoresDashboardController extends Controller
{
    use GeneralTrait;

    function dashboard(Request $req){
        $store = Stores::where([['stores.id',$req->input('middleinfo.store')]])->leftJoin('files', 'files.id', '=', 'stores.logo')->first([
            'stores.id',
            'stores.user',
            'stores.subdomain',
            'stores.name',
            DB::raw("CASE WHEN stores.logo IS NULL THEN NULL ELSE CONCAT('/storage', files.directories, '/', files.name, '.', files.extension) END AS logo"),
            'stores.theme',
            'stores.location',
            'stores.is_active',
            'stores.is_verify'
        ]);
        $user = User::where('id', $req->input('middleinfo.user'))->first();
        return view('stores.dashboard',['store'=>$store,'user'=>$user,'store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
    }

    function categories(Request $req){
        $categories = Categories::where([['store',$req->input('middleinfo.store')]])->get();
        return view('stores.categories',['categories'=>$categories,'store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
    }

    function products(Request $req){
        $products = Products::where('products.store',$req->input('middleinfo.store'))->join('categories', 'products.category', '=', 'categories.id')->get(['products.id as id','products.name as name','categories.name as category','products.price','products.discount_price','products.is_active']);
        return view('stores.products',['products'=>$products,'store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
    }

    function files(Request $req){
        $files = Files::where([['store',$req->input('middleinfo.store')],['kind','!=','transfer']])->get(['id','store','title',DB::raw("CONCAT('/storage', directories, '/', name, '.', extension) AS image"),'name','extension','updated_at','created_at']);
        return view('stores.files', ['files' =>$files,'store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
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
        $designs = Design::where('store',$req->input('middleinfo.store'))->get();
        $qr_code = Files::where([['store','=',$req->input('middleinfo.store')],['kind','=','qrcode']])->first([
            DB::raw("CONCAT('/storage', files.directories, '/', files.name, '.', files.extension) AS image")
        ]);
        return view('stores.design',['store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname'),'templates'=>$templates,'designs'=>$designs,'qr_code'=>$qr_code]);
    }

    function subscriptions(Request $req){
        $data = $this->subcription_func($req->input('middleinfo.store'));
        $subscriptions = Subscriptions::where([['is_active','1']])->get();
        return view('stores.subscriptions',['subscriptions'=>$subscriptions,'mysubscriptions'=>$data['mysubscriptions'],'subscriptionable'=>$data['subscriptionable'],'store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
    }



    #functions tool
    function subcription_func($store){
        $subscriptionable = true;
        $data =[];
        $mysubs = [];
        $mysubscriptions = StoreSubs::where([['store',$store],['status','!=','deleted'],['status','!=','canceled']])->get();
        foreach($mysubscriptions as $mysubscription){
            $nmysubscription;
            if($mysubscription->status == "active" && $mysubscription->end_at > now()){
                $subscriptionable = false;
            }
            $bank_transfer;
            if($mysubscription->payment_method=="bank_transfer"){
                if($transfer = Transfers::where([['store',$store],['subscription',$mysubscription->id],['status','!=','rejected']])->first()){
                    $bank_transfer = $transfer->status;
                }else{
                    $bank_transfer = 'upload';
                } 
                
                $mysubs[]= json_decode(json_encode(array(
                    'id'=>$mysubscription->id,
                    'store'=>$mysubscription->store,
                    'subscription'=>$mysubscription->subscription,
                    'payment_method'=>$mysubscription->payment_method,
                    'price'=>$mysubscription->price,
                    'discount_price'=>$mysubscription->discount_price,
                    'tax'=>$mysubscription->tax,
                    'discount'=>$mysubscription->discount,
                    'total'=>$mysubscription->total,
                    'start_at'=>$mysubscription->start_at,
                    'end_at'=>$mysubscription->end_at,
                    'status'=>$mysubscription->status,
                    'note'=>$mysubscription->note,
                    'transfer_action'=>$bank_transfer ? $bank_transfer :'none'
                )));
            }
            
        }
        $data['mysubscriptions'] = $mysubs;
        $data['subscriptionable']= $subscriptionable;
        return $data;
    }
}
