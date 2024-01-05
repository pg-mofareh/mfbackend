<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Categories;
use App\Models\Files;
use App\Models\History;
class CategoriesController extends Controller
{

    function delete(Request $req){
        $category = Categories::where([['id', $req->id],['store',$req->input('middleinfo.store')]])->first();
        if($category){
            return view('stores.categories-services',['category'=>$category,'store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
        }else{
            return redirect()->route('stores.dashboard.categories',['store'=>$req->store])->withErrors(['حدث خطأ ما']);
        } 
    }

    function edit(Request $req){
        $category = Categories::where([['id', $req->id],['store',$req->input('middleinfo.store')]])->first();
        if($category){
            return view('stores.categories-services',['category'=>$category,'store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
        }else{
            return redirect()->route('stores.dashboard.categories',['store'=>$req->store])->withErrors(['حدث خطأ ما']);
        }
    }

    function active(Request $req){
        $category = Categories::where([['id', $req->id],['store',$req->input('middleinfo.store')]])->first();
        if($category){
            return view('stores.categories-services',['category'=>$category,'store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
        }else{
            return redirect()->route('stores.dashboard.categories',['store'=>$req->store])->withErrors(['حدث خطأ ما']);
        }
    }

    # functions here

    function create(Request $req){
        $rules = [
            'name' => ['required','string','max:72']
        ];
        $messages = [];
        $attributes = ['name' => 'الإسم'];
        $validator = Validator::make($req->only('name'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['name']));
        }

        try {
            $category = Categories::create([
                'store'=>$req->input('middleinfo.store'),
                'name'=>$req->name
            ]);
            if($category){
                $this->record_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $category->id, 'create', "created new category");
            }
            return redirect()->route('stores.dashboard.categories',['store'=>$req->store])->withSuccess('تم إنشاء التصنيف بنجاح'); 
        } catch (QueryException $e) { 
            return back()->withErrors(['حدث خطأ أثناء التنفيذ'])->withInput($req->only(['name']));
        }    

        return back()->withErrors(['حدث خطأ ما'])->withInput($req->only(['name']));
    }

    function delete_func(Request $req){
        $rules = [
            'id' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم التصنيف'];
        $validator = Validator::make($req->only('id'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try {
            if(Categories::where([['id',$req->id],['store',$req->input('middleinfo.store')]])->delete()){
                $this->record_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $req->id, 'delete', "deleted category");
                return redirect()->route('stores.dashboard.categories',['store'=>$req->store])->withSuccess('تم حذف التصنيف بنجاح'); 
            }
        } catch (QueryException $e) {
            return back()->withErrors([ 'حدث خطأ أثناء التنفيذ']);
        }    

        return back()->withErrors(['حدث خطأ ما']);
    }

    function edit_func(Request $req){
        $rules = [
            'id' => ['required'],
            'name' => ['required','string','max:72']
        ];
        $messages = [];
        $attributes = ['id'=>'رقم التصنيف','name' => 'الإسم'];
        $validator = Validator::make($req->only('id','name'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['name']));
        }

        try {
            if(Categories::where([['id',$req->id],['store',$req->input('middleinfo.store')]])->update([
                'name'=>$req->name
            ])){
                $this->record_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $req->id, 'update', "updated category");
                return redirect()->route('stores.dashboard.categories',['store'=>$req->store])->withSuccess('تم تعديل التصنيف بنجاح'); 
            }            
        } catch (QueryException $e) {  
            return back()->withErrors(['حدث خطأ أثناء التنفيذ'])->withInput($req->only(['name']));
        }    

        return back()->withErrors(['حدث خطأ ما'])->withInput($req->only(['name']));
    }

    function active_func(Request $req){
        $rules = [
            'id' => ['required'],
            'status' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم التصنيف','status' => 'حالة التصنيف'];
        $validator = Validator::make($req->only('id','status'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try {
            if(Categories::where([['id', $req->id],['is_active','!=',$req->status],['store',$req->input('middleinfo.store')]])->update(['is_active'=>$req->status])){
                $this->record_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $req->id, 'active', "updated active");
                return redirect()->route('stores.dashboard.categories',['store'=>$req->store])->withSuccess('تم تحديث حالة التصنيف بنجاح'); 
            }else{
                return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
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
            'table' => 'categories',
            'item' => $item,
            'process'=> $process,
            'description' => $description
        ]);
    }
}
