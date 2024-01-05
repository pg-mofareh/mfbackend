<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Stores;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Templates;
use App\Models\Design;
use DB;
class StorePublicController extends Controller
{

    function main(Request $req)
    {
        if(Route::is('dashboard.stores.design.view-store')){
            $store = Stores::leftJoin('files', 'files.id', '=', 'stores.logo')->where('stores.id', $req->id)->first([
                'stores.id',
                'stores.subdomain',
                'stores.name',
                DB::raw("CASE WHEN stores.logo IS NULL THEN NULL ELSE CONCAT('/storage', files.directories, '/', files.name, '.', files.extension) END AS logo"),
            ]);
        }else{
            $store = Stores::leftJoin('files', 'files.id', '=', 'stores.logo')->where('subdomain', $req->store)->first([
                'stores.id',
                'stores.subdomain',
                'stores.name',
                DB::raw("CASE WHEN stores.logo IS NULL THEN NULL ELSE CONCAT('/storage', files.directories, '/', files.name, '.', files.extension) END AS logo"),
            ]);
        }
        

        if (!$store) {
            return abort(404);
        }

        if(Route::is('stores.dashboard.design.view-store')){
            $design = Design::leftJoin('templates', 'templates.id', '=', 'design.template')->where([['design.store',$store->id],['design.id',$req->id]])->first([
                'design.id',
                'templates.blade',
                'templates.css_styles',
                'templates.javascript_code',
                'design.css_styles AS css_own',
                'design.javascript_code AS js_own',
                'design.json_file'
            ]);
            if(!$design){ return abort(500); }
        }elseif(Route::is('dashboard.stores.design.view-store')){
            $design = Design::leftJoin('templates', 'templates.id', '=', 'design.template')->where([['design.store',$req->id],['design.id',$req->design]])->first([
                'design.id',
                'templates.blade',
                'templates.css_styles',
                'templates.javascript_code',
                'design.css_styles AS css_own',
                'design.javascript_code AS js_own',
                'design.json_file'
            ]);
            if(!$design){ return abort(500); }
        }else {
            $design = Design::leftJoin('templates', 'templates.id', '=', 'design.template')->where([['design.store',$store->id],['design.is_active','1'],['design.is_verify','1']])->first([
                'templates.blade',
                'templates.css_styles',
                'templates.javascript_code',
                'design.css_styles AS css_own',
                'design.javascript_code AS js_own',
                'design.json_file'
            ]);
            if(!$design){ return abort(500); }
        }

        $categories_products = [];
        $categories = Categories::where([['store',$store->id]])->get();
        foreach($categories as $category){
            $products = Products::where([['category',$category->id]])->get();
            $categories_products[]= array('id'=>$category->id,'name'=>$category->name,'products'=>$products);
        }

        if(isset($req->product)){
            $mainImage = $store->logo !== null ? '/storage'.$store->logo : '/storage'.env('APP_ICON');
            $product = Products::leftJoin('files', 'files.id', '=', 'products.image')->where([['products.id',$req->product],['products.store',$store->id]])->first([
                'products.id',
                'products.store',
                'products.name',
                DB::raw("CASE WHEN products.image IS NULL THEN '$mainImage' ELSE CONCAT('/storage', files.directories, '/', files.name, '.', files.extension) END AS image"),
            ]);
        }else{ $product = null; }
        
        if(view()->exists('storespublic.templates.'.$design->blade)){
            return view('storespublic.templates.'.$design->blade, ['store' => $store, 'design' => $design, 'categories_products' => json_decode(json_encode($categories_products)), 'product'=>$product]);  
        }else{ return abort(500); }
              
    }

    function disactive(Request $req){
        return view('storespublic.disactive',['store'=>$req->input('middleinfo.store')]);
    }

}
