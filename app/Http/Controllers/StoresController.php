<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;
use OpenAI\Laravel\Facades\OpenAI;
use App\Services\OpenAIService;
use App\Models\Stores;
use App\Models\User;
use App\Models\History;
use App\Models\Templates;
use App\Models\Design;
use App\Models\Files;
use DB;
class StoresController extends Controller
{

    function openai(){
        try{
            $result = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => 'PHP is',
            ]);
            echo $result['choices'][0]['text'];
        } catch (\Exception $e) {
            //echo 'Error: ' . $e->getMessage();
            
        }
        
    }
    
    function join_us(){
        return view('stores.join-us');
    }

    function create(Request $req){
        $rules = [
            'name' => ['required','string','min:2','max:72'],
            'subdomain' => ['required','string','min:2','max:72'],
            'location' => ['required','string'],
        ];
        $messages = [];
        $attributes = ['name' => 'الإسم','subdomain'=>'مجال المتجر','location'=>'موقع المتجر'];
        $validator = Validator::make($req->only('name','subdomain','location'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['name', 'subdomain', 'location']));
        }

        try{
            $user = auth()->user();
            if(Stores::where('subdomain',$req->subdomain)->exists()){
                return back()->withErrors(['هذا الدومين مستخدم بالفعل'])->withInput($req->only(['name', 'subdomain', 'location']));
            }elseif(Stores::where('user',$user->id)->exists()){
                return back()->withErrors(['لديك متجر فعال'])->withInput($req->only(['name', 'subdomain', 'location']));
            }else{
                Stores::create([
                    'user'=>$req->input('middleinfo.user'),
                    'name'=>$req->name,
                    'subdomain'=>$req->subdomain,
                    'location'=>$req->location
                ]);
            }
            
        }catch (Exception $e) {
            return back()->withErrors(['فشل إنشاء المتجر']);
        } 

        return back()->withSuccess('تم إنشاء المتجر بنجاح'); 
    }

    function active(Request $req){
        $store = Stores::where([['id',$req->input('middleinfo.store')]])->first();
        if($store->is_active==0){
            Stores::where([['id',$req->input('middleinfo.store')]])->update(['is_active'=>'1']);
        }else{
            Stores::where([['id',$req->input('middleinfo.store')]])->update(['is_active'=>'0']);
        }
        $this->record_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $req->input('middleinfo.store'), 'active', "active store");
        return back()->withSuccess('تم تحديث حالة المتجر بنجاح');
    }

    #for admin
    function viewer(Request $req){
        $store = Stores::where([['stores.id',$req->id]])->leftJoin('files', 'files.id', '=', 'stores.logo')->first([
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
        if($store){
            $user = User::where([['id',$store->user]])->first();
        }else{ $user=null; }

        
        $qr_code = Files::where([['store','=',$req->id],['kind','=','qrcode']])->first([
            DB::raw("CONCAT('/storage', files.directories, '/', files.name, '.', files.extension) AS image")
        ]);
        return view('dashboard.stores-viewer',['store'=>$store,'user'=>$user,'qr_code'=>$qr_code]);
    }

    function edit(Request $req){
        $store = Stores::where([['stores.id',$req->id]])->leftJoin('files', 'files.id', '=', 'stores.logo')->first([
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
        return view('dashboard.stores-services',['store'=>$store]);
    }

    function design(Request $req){
        $designs = Design::join('templates', 'design.template', '=', 'templates.id')->where('store',$req->id)->get([
            'design.id','design.store','design.template','design.name','templates.name as tname','design.is_active','design.is_verify'
        ]);
        return view('dashboard.stores-design',['designs'=>$designs]);
    }

    function verify_store(Request $req){
        $store = Stores::where([['id',$req->id]])->first();
        if($store->is_verify==0){
            Stores::where([['id',$req->id]])->update(['is_verify'=>'1']);
        }else{
            Stores::where([['id',$req->id]])->update(['is_verify'=>'0']);
        }
        return back()->withSuccess('تم تحديث التحقق من المتجر بنجاح');
    }

    function active_store(Request $req){
        $store = Stores::where([['id',$req->id]])->first();
        if($store->is_active==0){
            Stores::where([['id',$req->id]])->update(['is_active'=>'1']);
        }else{
            Stores::where([['id',$req->id]])->update(['is_active'=>'0']);
        }
        return back()->withSuccess('تم تحديث  حالة المتجر بنجاح');
    }

    function edit_store(Request $req){
        $rules = [
            'name' => ['required','string','min:2','max:72'],
            'location' => ['required','string']
        ];
        $messages = [];
        $attributes = ['name' => 'الإسم','location'=>'موقع المتجر'];
        $validator = Validator::make($req->only('name','location'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['name', 'location']));
        }

        if($model = Stores::where('id',$req->id)->update(['name'=>$req->name,'location'=>$req->location])){
            return back()->withSuccess('تم تحديث المتجر بنجاح');
        }

        return back()->withErrors(['فشل تحديث المتجر']);
    }

    function edit_subdomain_store(Request $req){
        $rules = [
            'subdomain' => ['required','string','min:2','max:72']
        ];
        $messages = [];
        $attributes = ['subdomain'=>'مجال المتجر'];
        $validator = Validator::make($req->only('subdomain'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['subdomain']));
        }

        if(Stores::where([['id',$req->id],['subdomain',$req->subdomain]])->exists()){
            return back()->withErrors(['لم تقم بتحديث أي شيء'])->withInput($req->only(['subdomain']));
        }elseif(Stores::where('subdomain',$req->subdomain)->exists()){
            return back()->withErrors(['هذا الدومين مستخدم بالفعل'])->withInput($req->only(['subdomain']));
        }else{
            if(Stores::where([['id',$req->id]])->update(['subdomain'=>$req->subdomain])){
                return back()->withSuccess('تم تحديث المتجر بنجاح');
            }
        }
        
        return back()->withErrors(['فشل تحديث المتجر']);
    }

    #tools
    function record_process($by,$store,$item,$process,$description){
        History::create([
            'by' => $by,
            'store' => $store,
            'table' => 'stores',
            'item' => $item,
            'process'=> $process,
            'description' => $description
        ]);
    }

}
