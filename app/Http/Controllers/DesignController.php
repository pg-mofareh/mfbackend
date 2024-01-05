<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Templates;
use App\Models\Design;
use App\Models\Files;
use DB;
use PDF;
class DesignController extends Controller
{

    function create_template(){
        return view('dashboard.design-services');
    }

    function create_func_template(Request $req){
        $rules = [
            'name' => ['required','string'],
            'description' =>  ['required','string'],
            'css' =>  ['nullable','string'],
            'js' => ['nullable','string'],
            'json' =>  ['nullable','string']
        ];
        $messages = [];
        $attributes = ['name' => 'عنوان النموذج', 'description' => 'وصف النموذج', 'css' => 'CSS', 'js' => 'JavaScript', 'json' => 'JSON'];
        $validator = Validator::make($req->only('name','description','css','js','json'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['name','description','css','js','json']));
        }

        try{
            if($template = Templates::create([
                'name'=>$req->name,
                'description'=>$req->description,
                'css_styles'=>$req->css,
                'javascript_code'=>$req->js,
                'json_instructions'=>$req->json,
            ])){
                $template->update(['blade' => 'template-' . $template->id]);
                return redirect()->route('dashboard.design')->withSuccess('تم إنشاء النموذج بنجاح'); 
            }
        }catch(QueryException $e) { 
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        } 

        return back()->withErrors(['حدث خطأ ما']);
    }

    /*
    function delete_template(Request $req){
        return view('dashboard.design-services');
    }

    function delete_func_template(Request $req){
        $rules = [
            'id' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم النموذج'];
        $validator = Validator::make($req->only('id'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try{
            $template = Templates::where('id',$req->id)->delete();
            if (!$template) {
                return redirect()->route('dashboard.design')->withErrors(['فشل حذف النموذج']);
            }
            return redirect()->route('dashboard.design')->withSuccess('تم حذف النموذج '.$req->id.' بنجاح');
        }catch(QueryException $e) { 
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        } 

        return back()->withErrors(['حدث خطأ ما']);
    }
    */

    function active_template(Request $req){
        return view('dashboard.design-services');
    }

    function active_func_template(Request $req){
        $rules = [
            'id' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم النموذج'];
        $validator = Validator::make($req->only('id'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try{
            $template = Templates::find($req->id);
            if (!$template) {
                return redirect()->route('dashboard.design')->withErrors(['النموذج غير موجود']);
            }
            $template->is_active = ($template->is_active == 0) ? 1 : 0;
            $template->save();
            return redirect()->route('dashboard.design')->withSuccess('تم تحديث حالة النموذج '.$req->id.' بنجاح');
        }catch(QueryException $e) { 
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        } 

        return back()->withErrors(['حدث خطأ ما']);
    }

    function edit_template(Request $req){
        try{
            $template = Templates::find($req->id);
            if (!$template) {
                return redirect()->route('dashboard.design')->withErrors(['النموذج غير موجود']);
            }
            return view('dashboard.design-services',['template'=>$template]);
        }catch(QueryException $e) { 
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        }
        return back()->withErrors(['حدث خطأ ما']);
    }

    function edit_func_template(Request $req){
        $rules = [
            'id' => ['required'],
            'name' => ['required','string'],
            'description' =>  ['required','string'],
            'css' =>  ['nullable','string'],
            'js' => ['nullable','string'],
            'json' =>  ['nullable','string']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم النموذج','name' => 'عنوان النموذج', 'description' => 'وصف النموذج', 'css' => 'css', 'js' => 'JavaScript', 'json' => 'JSON'];
        $validator = Validator::make($req->only('id','name','description','css','js','json'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['name','description','css','js','json']));
        }

        try{
            $template = Templates::find($req->id);
            if (!$template) {
                return redirect()->route('dashboard.design')->withErrors(['النموذج غير موجود']);
            }
            $template->name = $req->name;
            $template->description = $req->description;
            $template->css_styles = $req->css;
            $template->javascript_code = $req->js;
            $template->json_instructions = $req->json;
            $template->save();
            return back()->withSuccess('تم تحديث النموذج بنجاح');
        }catch(QueryException $e) { 
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        } 

        return back()->withErrors(['حدث خطأ ما']);
    }

    function active_design_admin(Request $req){
        return view('dashboard.stores-design-services');
    }

    function active_design_admin_func(Request $req){
        $rules = [
            'design' => ['required']
        ];
        $messages = [];
        $attributes = ['design' => 'رقم التصميم'];
        $validator = Validator::make($req->only('design'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try{
            $design = Design::where([['id',$req->design]])->first();
            if (!$design) {
                return redirect()->route('dashboard.stores.design',['id'=>$req->id])->withErrors(['التصميم غير موجود']);
            }
            $design->is_active = ($design->is_active == 0) ? 1 : 0;
            $design->save();
            return redirect()->route('dashboard.stores.design',['id'=>$req->id])->withSuccess('تم تحديث حالة التصميم '.$req->design.' بنجاح');
        }catch(QueryException $e) { 
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        } 

        return back()->withErrors(['حدث خطأ ما']);
    }

    function verify_design_admin(Request $req){
        return view('dashboard.stores-design-services');
    }

    function verify_design_admin_func(Request $req){
        $rules = [
            'design' => ['required']
        ];
        $messages = [];
        $attributes = ['design' => 'رقم التصميم'];
        $validator = Validator::make($req->only('design'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try{
            $design = Design::where([['id',$req->design]])->first();
            if (!$design) {
                return redirect()->route('dashboard.stores.design',['id'=>$req->id])->withErrors(['التصميم غير موجود']);
            }
            $design->is_verify = ($design->is_verify == 0) ? 1 : 0;
            $design->save();
            return redirect()->route('dashboard.stores.design',['id'=>$req->id])->withSuccess('تم تحديث التحقق من التصميم '.$req->design.' بنجاح');
        }catch(QueryException $e) { 
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        } 

        return back()->withErrors(['حدث خطأ ما']);
    }

    function edit_design_admin(Request $req){
        try{
            $design = Design::where([['id',$req->design],['store',$req->id]])->first();
            if (!$design) {
                return back()->withErrors(['التصميم غير موجود']);
            }
            return view('dashboard.stores-design-services',['design'=>$design]);
        }catch(QueryException $e) { 
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        }
        return back()->withErrors(['حدث خطأ ما']);
    }

    function edit_design_admin_func(Request $req){
        $rules = [
            'design' => ['required'],
            'name' => ['required','string'],
            'css' =>  ['nullable','string'],
            'js' =>  ['nullable','string'],
            'json' =>  ['nullable','string'],
        ];
        $messages = [];
        $attributes = ['design' => 'رقم التصميم','name' => 'عنوان التصميم','css' => 'CSS','js' => 'JavaScript','json'=>'JSON'];
        $validator = Validator::make($req->only('design','name','css','js','json'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['name','css','js','json']));;
        }

        try{
            $design = Design::where([['id',$req->design],['store',$req->id]])->first();
            if (!$design) {
                return redirect()->route('dashboard.stores')->withErrors(['التصميم غير موجود']);
            }
            $design->name = $req->name;
            $design->css_styles = $req->css;
            $design->javascript_code = $req->js;
            $design->json_file = $req->json;
            $design->save();
            return back()->withSuccess('تم تحديث التصميم');
        }catch(QueryException $e) { 
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        }

        return back()->withErrors(['حدث خطأ ما']);
    }




    #for stores
    function add_design(Request $req){
        $rules = [
            'id' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم النموذج'];
        $validator = Validator::make($req->only('id'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try{
            $template = Templates::where('id',$req->template)->first();
            if($template && $design = Design::create([
                'store'=>$req->input('middleinfo.store'),
                'template'=>$req->template
            ])){
                return back()->withSuccess('تم إنشاء التصميم بنجاح'); 
            }
        }catch(QueryException $e) { 
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        } 

        return back()->withErrors(['حدث خطأ ما']);
    }

    function active_design_store(Request $req){
        return view('stores.design-services',['store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
    }

    function active_design_store_func(Request $req){
        $rules = [
            'id' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم التصميم'];
        $validator = Validator::make($req->only('id'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try{
            $design = Design::where([['id',$req->id],['store',$req->input('middleinfo.store')]])->first();
            if (!$design) {
                return redirect()->route('stores.dashboard.design',['store'=>$req->input('middleinfo.subdomain')])->withErrors(['التصميم غير موجود']);
            }
            $design->is_active = ($design->is_active == 0) ? 1 : 0;
            $design->save();
            return redirect()->route('stores.dashboard.design',['store'=>$req->input('middleinfo.subdomain')])->withSuccess('تم تحديث حالة النموذج '.$req->id.' بنجاح');
        }catch(QueryException $e) { 
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        } 

        return back()->withErrors(['حدث خطأ ما']);
    }

    function edit_design_store(Request $req){
        try{
            $design = Design::where([['id',$req->id],['store',$req->input('middleinfo.store')]])->first();
            if (!$design) {
                return back()->withErrors(['التصميم غير موجود']);
            }
            return view('stores.design-services',['store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname'),'design'=>$design]);
        }catch(QueryException $e) { 
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        }
        return back()->withErrors(['حدث خطأ ما']);
    }

    function edit_design_store_func(Request $req){
        $rules = [
            'id' => ['required'],
            'name' => ['required','string']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم النموذج','name' => 'عنوان التصميم'];
        $validator = Validator::make($req->only('id','name'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['name']));
        }

        try{
            $design = Design::where([['id',$req->id],['store',$req->input('middleinfo.store')]])->first();
            if (!$design) {
                return redirect()->route('stores.dashboard.design',['store'=>$req->input('middleinfo.subdomain')])->withErrors(['التصميم غير موجود']);
            }
            $design->name = $req->name;
            $design->save();
            return back()->withSuccess('تم تحديث التصميم '.$req->id.' بنجاح');
        }catch(QueryException $e) { 
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        } 

        return back()->withErrors(['حدث خطأ ما']);
    }

    function download_card(Request $req){
        $qr_code = Files::where([['store', '=', $req->input('middleinfo.store')],['kind', '=', 'qrcode']])->first([
            DB::raw("CONCAT(files.directories, '/', files.name, '.', files.extension) AS image")
        ]);
    
        if ($qr_code) {
            $image = asset('storage/'.$qr_code->image);
            
            $pdf = PDF::loadView('stores.cards.card-1', ['image' => $image]);
            return $pdf->download();
        }
    
        return back();
    }

}
