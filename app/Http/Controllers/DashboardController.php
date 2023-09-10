<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Traits\MailTrait;
use App\Traits\GeneralTrait;
use App\Models\User;
class DashboardController extends Controller
{
    use MailTrait;
    use GeneralTrait;

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

    function payment(Request $req){
        return view('dashboard.payment');
    }

    function files(Request $req, $directories = null){
        $directoryPath = $directories ? '/' . $directories : '/';
        $contents = $this->filesdashboardpage($directoryPath);
        return view('dashboard.files', ['contents' =>$contents['contents'],'directories_line'=>$contents['directories_line'],'reply'=>$contents['reply'],'directoryPath'=>$directoryPath]);
    }
}
