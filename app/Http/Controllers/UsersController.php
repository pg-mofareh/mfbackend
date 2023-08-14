<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Hash; use Auth;
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
        $attributes = ['name' => trans('auth.inputs.name'),'email' => trans('auth.inputs.email'),'password' => trans('auth.inputs.password')];
        $validator = Validator::make($req->only('name','email','password'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }
        User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password)
        ]);
        return redirect()->route('dashboard.users.create')->withSuccess(trans('auth.create-success'));
    }

    function edit(Request $req){
        $user = User::where('id','=',$req->id)->first(['id','name','email']);
        return view('dashboard.users-services',['user'=>$user]);
    }
    function edit_func(Request $req){
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
        if($user = User::where('id','=',$req->id)->update(['name'=>$req->name])){
            return back()->withSuccess(trans('auth.edit-user'));
        }
        return back()->withErrors([ 'name' => trans('auth.edit-user-failed')]);
    }

    function view(Request $req){
        $user = User::where('id','=',$req->id)->first(['id','name','email']);
        return view('dashboard.users-services',['user'=>$user]);
    }
    function view_func(Request $req){
        return 'view function';
    }

    function delete(Request $req){
        $user = User::where('id','=',$req->id)->first(['id','name','email']);
        return view('dashboard.users-services',['user'=>$user]);
    }
    function delete_func(Request $req){
        $user = User::where('id','=',$req->id)->delete();
        if($user){
            return redirect()->route('dashboard.users') ->withSuccess(trans('auth.delete-user'));
        }
        return back()->withErrors([ 'email' => trans('auth.delete-user-failed')]);
    }

    function roles(Request $req){
        $roles = Role::all()->pluck('name');
        $user = User::with('roles')->where('id', '=', $req->id)->first(['id', 'name', 'email']);
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
        return redirect()->back()->withSuccess(trans('auth.update-user-success-roles'));
    }

    function permissions(Request $req){
        $permissions = Permission::all()->pluck('name');
        $user = User::with('roles')->where('id', '=', $req->id)->first(['id', 'name', 'email']);
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
        return redirect()->back()->withSuccess(trans('auth.update-user-success-permissions'));
    }

    function lab(Request $req){
        $newPermissions = Permission::create(['name' => 'permissions delete']);
        $role = Role::findByName('super admin');
        $role->syncPermissions($newPermissions);

        return 'success';
    }

}
