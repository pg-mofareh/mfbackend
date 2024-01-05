<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\User;
use Hash; 
use Auth;
class UsersController extends Controller
{

    function create(){
        return view('dashboard.users-services');
    }
    function create_func(Request $req){
        # validation inputs
        $rules = [
            'name' => ['required','string','max:250'],
            'email' => ['required','email','max:250'],
            'password' => ['required','min:8','max:16']
        ];
        $messages = [];
        $attributes = ['name' => 'الإسم','email' => 'الإيميل','password' => 'كلمة السر'];
        $validator = Validator::make($req->only('name','email','password'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }
        User::create([
            'first_name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password)
        ]);
        return redirect()->route('dashboard.users.create')->withSuccess('تم إنشاء العميل بنجاح');
    }

    function edit(Request $req){
        $user = User::where('id','=',$req->id)->first(['id','first_name','email']);
        return view('dashboard.users-services',['user'=>$user]);
    }
    function edit_func(Request $req){
        # validation inputs
        $rules = [
            'name' => ['required','string','max:250']
        ];
        $messages = [];
        $attributes = ['name' => 'الإسم'];
        $validator = Validator::make($req->only('name'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }
        if($user = User::where('id','=',$req->id)->update(['name'=>$req->name])){
            return back()->withSuccess('تم تحديث المستخدم');
        }
        return back()->withErrors(['فشل تحديث المستخدم']);
    }

    function view(Request $req){
        $user = User::where('id','=',$req->id)->first(['id','first_name','email']);
        return view('dashboard.users-services',['user'=>$user]);
    }
    function view_func(Request $req){
        return 'view function';
    }

    function delete(Request $req){
        $user = User::where('id','=',$req->id)->first(['id','first_name','email']);
        return view('dashboard.users-services',['user'=>$user]);
    }
    function delete_func(Request $req){
        try {
            $user = User::where('id','=',$req->id)->delete();
            if($user){
                return redirect()->route('dashboard.users') ->withSuccess('تم حذف العميل بنجاح');
            }
        } catch(QueryException $e) {  
            return back()->withErrors(['حدث خطأ أثناء التنفيذ']);
        } 
        
        return back()->withErrors(['فشل حذف العميل']);
    }

    function roles(Request $req){
        $roles = Role::all()->pluck('name');
        $user = User::with('roles')->where('id', '=', $req->id)->first(['id', 'first_name', 'email']);
        $roles = $roles->map(function ($role) use ($user) {
            return ['name' => $role, 'label' => '', 'status' => $user->hasRole($role)];
        });
        return view('dashboard.users-services', ['roles' => json_decode(json_encode($roles)), 'user' => $user]);
    }
    function roles_func(Request $req){
        if (!$req->has('roles')) {
            $req->merge(['roles' => []]);
        }
        $user = User::find($req->id);
        $roles = Role::all();
        foreach ($roles as $role) {
            if(in_array($role->name, $req->input('roles'))){
                $user->assignRole($role);
            } else {
                $user->removeRole($role);
            }
        }
        return redirect()->back()->withSuccess('تم تحديث أدوار المستخدم');
    }

    function permissions(Request $req){
        $permissions = Permission::all()->pluck('name');
        $user = User::with('roles')->where('id', '=', $req->id)->first(['id', 'first_name', 'email']);
        $permissions = $permissions->map(function ($permission) use ($user) {
            return ['name' => $permission,'label' => '', 'status' => $user->can($permission)];
        });
        return view('dashboard.users-services',['permissions'=>json_decode(json_encode($permissions)),'user'=>$user]);
    }
    function permissions_func(Request $req){
        if (!$req->has('permissions')) {
            $req->merge(['permissions' => []]);
        }
        $user = User::find($req->id);
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            if(in_array($permission->name, $req->input('permissions'))){
                $user->givePermissionTo($permission);
            } else {
                $user->revokePermissionTo($permission);
            }
        }
        return redirect()->back()->withSuccess('تم تحديث أذونات المستخدم');
    }

    function edit_admin(Request $req){
        $user = User::where('id',$req->id)->first();
        return view('dashboard.users-edit',['user'=>$user]);
    }

    function upemail_user_admin(Request $req){
        $rules = [
            'email' => ['required','email']
        ];
        $messages = [];
        $attributes = ['email'=>'إيميل العميل'];
        $validator = Validator::make($req->only('email'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['email']));
        }

        if(User::where([['id',$req->id],['email',$req->email]])->exists()){
            return back()->withErrors(['لم تقم بتحديث أي شيء'])->withInput($req->only(['email']));
        }elseif(User::where('email',$req->email)->exists()){
            return back()->withErrors(['هذا الإيميل مستخدم بالفعل'])->withInput($req->only(['email']));
        }else{
            if(User::where([['id',$req->id]])->update(['email'=>$req->email,'email_verified_at'=>null,'otp_number'=>null,'otp_expiry'=>null])){
                return back()->withSuccess('تم تحديث العميل بنجاح');
            }
        }
        
        return back()->withErrors(['فشل تحديث العميل']);
    }

    function edit_user_admin(Request $req){
        $rules = [
            'first_name' => ['required','string','min:2','max:14'],
            'last_name' => ['required','string','min:2','max:14'],
            'phone_number' => ['required','string','min:9','max:9']
        ];
        $messages = [];
        $attributes = ['first_name' => 'الإسم الأول','last_name' => 'الإسم الأخير','phone_number' => 'رقم الجوال'];
        $validator = Validator::make($req->only('first_name','last_name','phone_number'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['first_name','last_name','phone_number']));
        }

        if(User::where([['id',$req->id]])->update(['first_name'=>$req->first_name,'last_name'=>$req->last_name,'phone_number'=>$req->phone_number])){
            return back()->withSuccess('تم تحديث العميل بنجاح');
        }

        return back()->withErrors(['فشل تحديث العميل']);
    }

    function uppassword_user_admin(Request $req){
        $rules = [
            'password' => ['required','min:8','max:16','confirmed']
        ];
        $messages = [];
        $attributes = ['password' => 'كلمة السر الجديدة','password_confirmation' => 'إعادة كلمة السر'];
        $validator = Validator::make($req->only('password','password_confirmation'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['password','password_confirmation']));
        }

        if(User::where([['id',$req->id]])->update(['password'=>bcrypt($req->password)])){
            return back()->withSuccess('تم تحديث كلمة السر بنجاح');
        }

        return back()->withErrors(['فشل تحديث كلمة السر']);
    }

}
