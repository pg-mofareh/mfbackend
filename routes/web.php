<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolesAndPermissionsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\FilesController;

# auth here
Route::prefix('/auth')->group(function(){
    Route::get('/login', [AuthController::class,'login'])->name('auth.login');
    Route::post('/login_func', [AuthController::class,'login_func'])->name('auth.login-func');
    Route::get('/register', [AuthController::class,'register'])->name('auth.register');
    Route::post('/register_func', [AuthController::class,'register_func'])->name('auth.register-func');
    Route::get('/logout', [AuthController::class,'logout'])->name('auth.logout');
});
Route::get('/auth/email/verify', [AuthController::class,'verify'])->middleware('auth')->name('auth.verify');
Route::post('/auth/email/verify/check', [AuthController::class,'verify_check'])->middleware(['auth'])->name('auth.verify_func');

Route::post('/auth/repasswordreq', [AuthController::class,'repassword_req'])->name('auth.repasswordreq');
Route::get('/auth/repassword', [AuthController::class,'repassword'])->name('auth.repassword');
Route::post('/auth/repassword/check', [AuthController::class,'repassword_func'])->name('auth.repassword_func');


# for test only
Route::get('/email/verify/remove', [AuthController::class,'verify_verify_remove'])->middleware(['auth']);
Route::get('/email/verify/send', [AuthController::class,'verify_verify_send']);





# dashboard
Route::prefix('/dashboard')->middleware(['dashboard'])->group(function(){
    # dashboard pages
    Route::prefix('')->group(function(){
        Route::get('/', [DashboardController::class,'home'])->name('dashboard.home');
        Route::get('/users', [DashboardController::class,'users'])->name('dashboard.users')->middleware(['can:users view']);
        Route::get('/roles', [DashboardController::class,'roles'])->name('dashboard.roles')->middleware(['can:roles view']);
        Route::get('/permissions', [DashboardController::class,'permissions'])->name('dashboard.permissions')->middleware(['can:permissions view']);
        Route::get('/payment', [DashboardController::class,'payment'])->name('dashboard.payment')->middleware(['can:payment view']);
        Route::get('/files/{directories?}', [DashboardController::class,'files'])->where('directories', '.*')->name('dashboard.files')->middleware(['can:files view']);
    });

    # users
    Route::prefix('/users')->group(function(){
        Route::get('/create', [UsersController::class,'create'])->name('dashboard.users.create')->middleware(['can:users create']);
        Route::post('/create_func', [UsersController::class,'create_func'])->name('dashboard.users.create-func')->middleware(['can:users create']);
        Route::prefix('/{id}')->group(function(){
            Route::get('/edit', [UsersController::class,'edit'])->name('dashboard.users.edit')->middleware(['can:users edit']);
            Route::post('/edit_func', [UsersController::class,'edit_func'])->name('dashboard.users.edit-func')->middleware(['can:users edit']);
            Route::get('/view', [UsersController::class,'view'])->name('dashboard.users.view')->middleware(['can:users view']);
            Route::get('/delete', [UsersController::class,'delete'])->name('dashboard.users.delete')->middleware(['can:users delete']);
            Route::post('/delete_func', [UsersController::class,'delete_func'])->name('dashboard.users.delete-func')->middleware(['can:users delete']);
            Route::get('/roles', [UsersController::class,'roles'])->name('dashboard.users.roles')->middleware(['can:users roles']);
            Route::post('/roles_func', [UsersController::class,'roles_func'])->name('dashboard.users.roles-func')->middleware(['can:users roles']);
            Route::get('/permissions', [UsersController::class,'permissions'])->name('dashboard.users.permissions')->middleware(['can:users permissions']);
            Route::post('/permissions_func', [UsersController::class,'permissions_func'])->name('dashboard.users.permissions-func')->middleware(['can:users permissions']);
        });  
    });

    # roles
    Route::prefix('/roles')->group(function(){
        Route::get('/create', [RolesAndPermissionsController::class,'roles_create'])->name('dashboard.roles.create')->middleware(['can:roles create']);
        Route::post('/create_func', [RolesAndPermissionsController::class,'roles_create_func'])->name('dashboard.roles.create-func')->middleware(['can:roles create']);
        Route::prefix('/{id}')->group(function(){
            Route::get('/delete', [RolesAndPermissionsController::class,'roles_delete'])->name('dashboard.roles.delete')->middleware(['can:roles delete']);
            Route::post('/delete_func', [RolesAndPermissionsController::class,'roles_delete_func'])->name('dashboard.roles.delete-func')->middleware(['can:roles delete']);
        });
    });

    # permissions
    Route::prefix('/permissions')->group(function(){
        Route::get('/create', [RolesAndPermissionsController::class,'permissions_create'])->name('dashboard.permissions.create')->middleware(['can:permissions create']);
        Route::post('/create_func', [RolesAndPermissionsController::class,'permissions_create_func'])->name('dashboard.permissions.create-func')->middleware(['can:permissions create']);
        Route::prefix('/{id}')->group(function(){
            Route::get('/delete', [RolesAndPermissionsController::class,'permissions_delete'])->name('dashboard.permissions.delete')->middleware(['can:permissions delete']);
            Route::post('/delete_func', [RolesAndPermissionsController::class,'permissions_delete_func'])->name('dashboard.permissions.delete-func')->middleware(['can:permissions delete']);
        });
    });

    # files
    Route::prefix('/files-system')->group(function(){
        Route::get('/create/{directories?}', [FilesController::class,'create'])->where('directories', '.*')->name('dashboard.files.create')->middleware(['can:files create']);
        Route::post('/create_func/{directories?}', [FilesController::class,'create_func'])->where('directories', '.*')->name('dashboard.files.create-func')->middleware(['can:files create']);
        Route::get('/delete/{directories?}', [FilesController::class,'delete'])->where('directories', '.*')->name('dashboard.files.delete')->middleware(['can:files delete']);
    });
});

Route::prefix('/documentation')->middleware(['documentation'])->group(function(){
    Route::prefix('')->group(function(){
        Route::get('/auth', function(){ return view('documentation.auth'); })->name('documentation.auth');
        Route::get('/users', function(){ return view('documentation.users'); })->name('documentation.users');
    });
});


Route::prefix('/')->group(function(){
    Route::prefix('')->group(function(){
        Route::get('/', [PublicController::class,'main'])->name('main');
        Route::get('/update-status', [PublicController::class,'update_status'])->name('update.status');
    });
});
