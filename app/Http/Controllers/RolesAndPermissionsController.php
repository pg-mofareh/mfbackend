<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
class RolesAndPermissionsController extends Controller
{
    
    function roles_create(){
        return view('dashboard.roles-services');
    }
    function roles_create_func(Request $req){
        # validation inputs
        $rules = [
            'name' => ['required','string','max:250']
        ];
        $messages = [];
        $attributes = ['name' => trans('auth.inputs.name')];
        $validator = Validator::make($req->only('name'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }
        if(!Role::where('name', $req->name)->exists()){
            $role = Role::create(['name' => $req->name]);
            return redirect()->route('dashboard.roles')->withSuccess(trans('permissions.msg.roles-create-success'));
        }
        return back()->withErrors([ 'name' => trans('permissions.msg.roles-create-failed')]);
    }

    function roles_delete(Request $req){
        $role = Role::where('id', $req->id)->first();
        return view('dashboard.roles-services',['role'=>$role]);
    }
    function roles_delete_func(Request $req){
        if(Role::where('id', $req->id)->delete()){
            return redirect()->route('dashboard.roles')->withSuccess(trans('permissions.msg.roles-delete-success'));
        }
        return back()->withErrors([ 'name' => trans('permissions.msg.roles-delete-failed')]);
    }


    function permissions_create(){
        return view('dashboard.permissions-services');
    }
    function permissions_create_func(Request $req){
        # validation inputs
        $rules = [
            'name' => ['required','string','max:72']
        ];
        $messages = [];
        $attributes = ['name' => trans('auth.inputs.name')];
        $validator = Validator::make($req->only('name'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }
        if(!Permission::where('name', $req->name)->exists()){
            $permission = Permission::create(['name' => $req->name]);
            return redirect()->route('dashboard.permissions')->withSuccess(trans('permissions.msg.roles-create-success'));
        }
        return back()->withErrors([ 'name' => trans('permissions.msg.roles-create-failed')]);
    }

    function permissions_delete(Request $req){
        $permission = Permission::where('id', $req->id)->first();
        return view('dashboard.permissions-services',['permission'=>$permission]);
    }
    function permissions_delete_func(Request $req){
        if(Permission::where('id', $req->id)->delete()){
            return redirect()->route('dashboard.permissions')->withSuccess(trans('permissions.msg.roles-delete-success'));
        }
        return back()->withErrors([ 'name' => trans('permissions.msg.roles-delete-failed')]);
    }



    function code_store(){
        //$role = Role::create(['name' => 'user']);
        /*$permissions = [
            ['name' => 'users create'],
            ['name' => 'users view'],
            ['name' => 'users edit'],
            ['name' => 'users delete']
        ];*/
        //$newPermissions = Permission::create(['name' => 'users delete']);
        //$role = Role::findByName('admin');
        //$role->syncPermissions(['users create', 'users view','users edit']);

                //$role = Role::findByName('super admin'); // Retrieve the role from the database
        //$permission = Permission::findByName('users create'); // Retrieve the permission from the database
        //$role->revokePermissionTo($permission);

        //$role = Role::findByName('super admin');

                    /*
            if($user->roles[0]->hasAnyRole('super admin', 'admin')){
                return 'success login';
                $req->session()->regenerate();
                return redirect()->route('/')
                    ->withSuccess('You have successfully logged in!');
            }
            */
        # $role = $user->assignRole('super admin');

        $user = User::where('id','=',1)->first();
        $user->roles[0]->assignRole('super admin');
        if($user){
            return 'create permissions success';
        }
        return 'create permissions faild';
    }
}
