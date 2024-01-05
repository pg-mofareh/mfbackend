<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Database\QueryException;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Files;
use App\Models\History;
use DB;
class ProductsController extends Controller
{
    use GeneralTrait;

    function create(Request $req){
        $categories = Categories::where([['store',$req->input('middleinfo.store')]])->get();
        if($categories->isNotEmpty()){
            return view('stores.products-services',['categories'=>$categories,'store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
        }
        return redirect()->route('stores.dashboard.products',['store'=>$req->store])->withErrors(['يرجى إنشاء تصنيف']);
    }

    function delete(Request $req){
        if($product = Products::where([['id', $req->id],['store',$req->input('middleinfo.store')]])->first()){
            return view('stores.products-services',['product'=>$product,'store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
        }
        return redirect()->route('stores.dashboard.products',['store'=>$req->store])->withErrors(['حدث خطأ ما']);
    }
    
    function edit(Request $req){
        $categories = Categories::where([['store',$req->input('middleinfo.store')]])->get();
        if($categories->isNotEmpty()){
            $product = Products::where([['products.id', $req->id],['products.store',$req->input('middleinfo.store')]])
            ->leftJoin('files', 'files.id', '=', 'products.image')
            ->first([
                'products.id',
                'products.store',
                'products.name',
                'products.description',
                'products.category',
                'products.price',
                'products.is_active',
                'products.image AS image_id',
                DB::raw("CASE WHEN products.image IS NULL THEN NULL ELSE CONCAT('/storage', files.directories, '/', files.name, '.', files.extension) END AS image"),
                'products.discount_price AS discount',
                'products.calories'
            ]);
            if($product->exists()){
                return view('stores.products-services',['categories'=>$categories,'product'=>$product,'store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
            }
        }
        return redirect()->route('stores.dashboard.products',['store'=>$req->store])->withErrors(['يرجى إنشاء تصنيف']);
    }

    function active(Request $req){
        $product = Products::where([['id', $req->id],['store',$req->input('middleinfo.store')]])->first();
        if($product->exists()){
            return view('stores.products-services',['product'=>$product,'store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
        }
        return redirect()->route('stores.dashboard.products',['store'=>$req->store])->withErrors(['حدث خطأ ما']);
    }


    # functions here

    function create_func(Request $req){
        $rules = [
            'category' => ['required'],
            'name' => ['required','string','max:148'],
            'price' => ['required','max:11'],
            'discount' => ['required','max:11'],
            'calories' => ['required','max:11']
        ];
        $messages = [];
        $attributes = ['category'=>'التصنيف','name' => 'الإسم','price' => 'السعر','discount' => 'الخصم','calories'=>'السعرات الحرارية'];
        $validator = Validator::make($req->only('category','name','price','discount','calories'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['name','description', 'price','discount','calories']));
        }

        try {
            $product = Products::create([
                'store'=>$req->input('middleinfo.store'),
                'name'=>$req->name,
                'description'=>$req->description,
                'category'=>$req->category,
                'price'=>$req->price,
                'discount_price'=>$req->discount,
                'calories'=>$req->calories
            ]);
            if($product){
                $this->record_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $product->id, 'create', "created new product" ); 
            }
            return redirect()->route('stores.dashboard.products',['store'=>$req->store])->withSuccess('تم إنشاء المنتج بنجاح'); 
        } catch (Exception $e) {
            return back()->withErrors(['حدث خطأ أثناء التنفيذ'])->withInput($req->only(['name','description', 'price','discount','calories']));
        }    

        return back()->withErrors(['حدث خطأ ما'])->withInput($req->only(['name','description', 'price','discount','calories']));
    }

    function delete_func(Request $req){
        $rules = [
            'id' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم المنتج'];
        $validator = Validator::make($req->only('id'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try {
            if(Products::where([['id',$req->id],['store',$req->input('middleinfo.store')]])->delete()){
                $this->record_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $req->id, 'delete', "deleted product" ); 
                return redirect()->route('stores.dashboard.products',['store'=>$req->store])->withSuccess('تم حذف المنتج بنجاح'); 
            }
        } catch(QueryException $e) {
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        }    

        return back()->withErrors(['حدث خطأ ما']);
    }

    function edit_func(Request $req){
        $rules = [
            'category' => ['required'],
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string','max:255'],
            'price' => ['required','max:11'],
            'discount' => ['nullable','max:11'],
            'calories' => ['required','max:11']
        ];
        $messages = [];
        $attributes = ['category'=>'التصنيف','name' => 'الإسم','description' => 'الوصف','price' => 'السعر','discount'=>'الخصم','calories'=>'السعرات الحرارية'];
        $validator = Validator::make($req->only('category','name','description','price','discount','calories'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['name','description', 'price','discount','calories']));
        }

        try {
            if(Products::where([['id',$req->id],['store',$req->input('middleinfo.store')]])->update([
                'name'=>$req->name,
                'description'=>$req->description,
                'category'=>$req->category,
                'price'=>$req->price,
                'discount_price'=>$req->discount,
                'calories'=>$req->calories
            ])){
                $this->record_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $req->id, 'update', "update product" ); 
                return back()->withSuccess('تم تعديل المنتج بنجاح'); 
            }
        } catch (Exception $e) {
            return back()->withErrors(['حدث خطأ أثناء التنفيذ'])->withInput($req->only(['name','description', 'price','discount','calories']));
        }    

        return back()->withErrors(['حدث خطأ ما'])->withInput($req->only(['name','description', 'price','discount','calories']));
    }

    function active_func(Request $req){
        $rules = [
            'id' => ['required'],
            'status' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم المنتج','status' => 'حالة المنتح'];
        $validator = Validator::make($req->only('id','status'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try {
            if(Products::where([['id', $req->id],['is_active','!=',$req->status],['store',$req->input('middleinfo.store')]])->update(['is_active'=>$req->status])){
                $this->record_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $req->id, 'active', "update product active" ); 
                return redirect()->route('stores.dashboard.products',['store'=>$req->store])->withSuccess('تم تحديث حالة المنتج بنجاح'); 
            }            
        } catch (Exception $e) {
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        }    

        return back()->withErrors(['حدث خطأ ما']);
    }


    #tools
    function record_process($by,$store,$item,$process,$description){
        History::create([
            'by' => $by,
            'store' => $store,
            'table' => 'products',
            'item' => $item,
            'process'=> $process,
            'description' => $description
        ]);
    }
}
