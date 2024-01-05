<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Traits\GeneralTrait;
use App\Models\Files;
use App\Models\FilesPublic;
use App\Models\Products;
use App\Models\Stores;
use App\Models\History;
use DB;
class FilesController extends Controller
{
    use GeneralTrait;
    
    function create(Request $req){
        $rules = [
            'title' => ['nullable','string','max:48'],
            'file' => ['required','max:2048']
        ];
        $messages = [];
        $attributes = ['title'=>'عنوان الصورة','file' => 'الملف'];
        $validator = Validator::make($req->only('title','file'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        if ($req->hasFile('file')) { 
            if($upload = $this->uploadFile($req->file('file'), '/stores'.'/'.$req->input('middleinfo.store'))){
                $file_parts = explode(".", $upload['file']);
                $file = Files::create([
                    'store'=>$req->input('middleinfo.store'),
                    'title'=>$req->title,
                    'name'=>$file_parts[0],
                    'extension'=>$file_parts[1],
                    'directories'=>$upload['dir']
                ]);
                if($file){
                    $this->record_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $file->id, 'create', "created file");
                }
                return back()->withSuccess('تم رفع الملف بنجاح');
            }            
        }

        return back()->withErrors([ 'is_last' => 'حدث خطأ ما']);
    }

    function delete(Request $req){
        $rules = [
            'id' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'الملف'];
        $validator = Validator::make($req->only('id'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try{
            if ($file = Files::where([['id', $req->id],['store',$req->input('middleinfo.store')]])->first(['id', 'directories', 'name', 'extension'])) {
                $filePath = $file->directories . '/' . $file->name . '.' . $file->extension;
                if (Storage::disk('public')->exists($filePath)) {
                    if (Files::where('id', $req->id)->delete()) {
                        if (Storage::disk('public')->delete($filePath)) {
                            $this->record_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $req->id, 'deleted', "deleted file");
                            return back()->withSuccess('تم حذف الملف بنجاح');
                        }
                    }
                }
            }
        } catch(QueryException $e) {
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        }    

        return back()->withErrors(['حدث خطأ ما']);
    }

    function searchproducts(Request $req){
        $rules = [
            'file' => ['required'],
            'search' => ['required'],
        ];
        $messages = [];
        $attributes = ['file'=>'الملف','search' => 'البحث'];
        $validator = Validator::make($req->only('file','search'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return response()->json(['errors' => $errors]);
        }

        if($req->search === '@') {
            $products = Products::where('store', $req->input('middleinfo.store'))->get(['id', 'name', 'image']);
            $response = [];
            foreach ($products as $product) {
                $row = [
                    'id' => $product->id,
                    'file' => $req->file,
                    'name' => $product->name,
                    'file_status' =>$product->image ? ($product->image == $req->file ? 'remove' : 'exchange') : 'set'
                ];

                $response[] = $row;
            }
            return $this->returnData($response);
        } else {
            $products = Products::where([['id','=', $req->search],['store', $req->input('middleinfo.store')]])->orWhere([['name', 'LIKE', '%' . $req->search . '%'],['store', $req->input('middleinfo.store')]])->get(['id', 'name', 'image']);
            $response = [];
            foreach ($products as $product) {
                $row = [
                    'id' => $product->id,
                    'file' => $req->file,
                    'name' => $product->name,
                    'file_status' =>$product->image ? ($product->image == $req->file ? 'remove' : 'exchange') : 'set'
                ];

                $response[] = $row;
            }
            return $this->returnData($response);
        }

        return $this->returnData([]);  
    }

    function updateproductimage(Request $req){
        $rules = [
            'file' => ['required'],
            'product' => ['required'],
            'order' => ['required'],
            'search' => ['required']
        ];
        $messages = [];
        $attributes = ['product'=>'المنتج','order'=>'الأمر','file'=>'الملف','search' => 'البحث'];
        $validator = Validator::make($req->only('file','product','order','search'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return response()->json(['errors' => $errors]);
        }

        if($req->order=='set' || $req->order=='exchange'){
            Products::where([['id','=',$req->product],['store',$req->input('middleinfo.store')]])->update(['image'=>$req->file]);
            $this->record_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $req->file, 'update', "updated product file");
        }elseif($req->order=='remove'){
            Products::where([['id','=',$req->product],['store',$req->input('middleinfo.store')]])->update(['image'=>null]);
            $this->record_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $req->file, 'update', "updated product file");
        }

        if($req->search === '@') {
            $products = Products::where('store', $req->input('middleinfo.store'))->get(['id', 'name', 'image']);
            $response = [];
            foreach ($products as $product) {
                $row = [
                    'id' => $product->id,
                    'file' => $req->file,
                    'name' => $product->name,
                    'file_status' =>$product->image ? ($product->image == $req->file ? 'remove' : 'exchange') : 'set'
                ];

                $response[] = $row;
            }
            return $this->returnData($response);
        } else {
            $products = Products::where([['id','=', $req->search],['store', $req->input('middleinfo.store')]])->orWhere([['name', 'LIKE', '%' . $req->search . '%'],['store', $req->input('middleinfo.store')]])->get(['id', 'name', 'image']);
            $response = [];
            foreach ($products as $product) {
                $row = [
                    'id' => $product->id,
                    'file' => $req->file,
                    'name' => $product->name,
                    'file_status' =>$product->image ? ($product->image == $req->file ? 'remove' : 'exchange') : 'set'
                ];

                $response[] = $row;
            }
            return $this->returnData($response);
        }

        return $this->returnData([]);         
    } 

    function aslogo(Request $req){
        $rules = [
            'file' => ['required']
        ];
        $messages = [];
        $attributes = ['file' => 'الملف'];
        $validator = Validator::make($req->only('file'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try {
            $store = Stores::where('id',$req->input('middleinfo.store'))->update([
                'logo'=>$req->file
            ]);
            if($store){
                $this->record_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $req->file, 'update', "make file as store logo");
            }
            return back()->withSuccess('تم تعيين الصورة كأيقونة للمتجر'); 
        } catch (Exception $e) {
            return back()->withErrors([ 'is_last' => 'حدث خطأ أثناء التنفيذ']);
        }

        return back()->withErrors([ 'is_last' => 'حدث خطأ ما']);
    }
    
    #tools
    function record_process($by,$store,$item,$process,$description){
        History::create([
            'by' => $by,
            'store' => $store,
            'table' => 'files',
            'item' => $item,
            'process'=> $process,
            'description' => $description
        ]);
    }




    #for public files

    function create_pfile(Request $req){
        $rules = [
            'title' => ['nullable','string','max:48'],
            'file' => ['required','max:2048']
        ];
        $messages = [];
        $attributes = ['title'=>'عنوان الصورة','file' => 'الملف'];
        $validator = Validator::make($req->only('title','file'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        if ($req->hasFile('file')) { 
            if($upload = $this->uploadFile($req->file('file'), '/public')){
                $file_parts = explode(".", $upload['file']);
                $file = FilesPublic::create([
                    'title'=>$req->title,
                    'name'=>$file_parts[0],
                    'extension'=>$file_parts[1],
                    'directories'=>$upload['dir']
                ]);
                return back()->withSuccess('تم رفع الملف بنجاح');
            }            
        }

        return back()->withErrors([ 'is_last' => 'حدث خطأ ما']);
    }

    function delete_pfile(Request $req){
        $rules = [
            'id' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'الملف'];
        $validator = Validator::make($req->only('id'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try{
            if ($file = FilesPublic::where([['id', $req->id]])->first(['id', 'directories', 'name', 'extension'])) {
                $filePath = $file->directories . '/' . $file->name . '.' . $file->extension;
                if (Storage::disk('public')->exists($filePath)) {
                    if (FilesPublic::where('id', $req->id)->delete()) {
                        if (Storage::disk('public')->delete($filePath)) {
                            return back()->withSuccess('تم حذف الملف بنجاح');
                        }
                    }
                }
            }
        } catch(QueryException $e) {
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        }    

        return back()->withErrors(['حدث خطأ ما']);
    }

    function create_qrcode(Request $req){
        $rules = [
            'store' => ['required'],
            'file' => ['required','max:2048']
        ];
        $messages = [];
        $attributes = ['store' => 'المتجر','file' => 'الملف'];
        $validator = Validator::make($req->only('store','file'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        if ($req->hasFile('file') && Stores::where('id',$req->store)->exists() && !Files::where([['store',$req->store],['kind','qrcode']])->exists()) { 
            if($upload = $this->uploadFile($req->file('file'), '/stores'.'/'.$req->store)){
                $file_parts = explode(".", $upload['file']);
                $file = Files::create([
                    'store'=>$req->store,
                    'title'=>'باركود المتجر',
                    'kind'=>'qrcode',
                    'name'=>$file_parts[0],
                    'extension'=>$file_parts[1],
                    'directories'=>$upload['dir']
                ]);
                return back()->withSuccess('تم إنشاء الباركود بنجاح');
            }            
        }

        return back()->withErrors([ 'is_last' => 'حدث خطأ ما']);
    }

    function delete_qrcode(Request $req){
        $rules = [
            'store' => ['required']
        ];
        $messages = [];
        $attributes = ['store' => 'المتجر'];
        $validator = Validator::make($req->only('store'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try{
            if ($file = Files::where([['kind', 'qrcode'],['store',$req->store]])->first(['id', 'directories', 'name', 'extension'])) {
                $filePath = $file->directories . '/' . $file->name . '.' . $file->extension;
                if (Storage::disk('public')->exists($filePath)) {
                    if (Files::where([['kind', 'qrcode'],['store',$req->store]])->delete()) {
                        if (Storage::disk('public')->delete($filePath)) {
                            return back()->withSuccess('تم حذف الملف بنجاح');
                        }
                    }
                }
            }
        } catch(QueryException $e) {
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        }  

        return back()->withErrors([ 'is_last' => 'حدث خطأ ما']);
    }

}
