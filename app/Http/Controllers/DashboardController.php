<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Traits\MailTrait;
use App\Models\User;
class DashboardController extends Controller
{
    use MailTrait;

    function home(Request $req){
        
        return view('dashboard.home');
    }

    function users(Request $req){
        $users = User::with('roles')->get(['id','name','email']);
        return view('dashboard.users',['users'=>$users]);
    }

    function roles(Request $req){
        $roles = Role::all();
        return view('dashboard.roles',['roles'=>$roles]);
    }

    function permissions(Request $req){
        $permissions = Permission::all();
        return view('dashboard.permissions',['permissions'=>$permissions]);
    }
}
