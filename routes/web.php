<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolesAndPermissionsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CategoriesController; 
use App\Http\Controllers\CouponsController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\StoresDashboardController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\StorePublicController;
use App\Http\Controllers\DesignController;


//Route::get('/openai', [StoresController::class,'openai'])->name('openai');

# auth here
Route::prefix('/auth')->group(function(){
    Route::get('/login', [AuthController::class,'login'])->name('auth.login');
    Route::post('/login_func', [AuthController::class,'login_func'])->name('auth.login-func');
    Route::get('/register', [AuthController::class,'register'])->name('auth.register');
    Route::post('/register_func', [AuthController::class,'register_func'])->name('auth.register-func');
    Route::get('/logout', [AuthController::class,'logout'])->name('auth.logout');
});
Route::get('/auth/email/verify', [AuthController::class,'verify'])->middleware(['auth'])->name('auth.verify');
Route::post('/auth/email/verify/check', [AuthController::class,'verify_check'])->middleware(['auth'])->name('auth.verify_func');

Route::post('/auth/repasswordreq', [AuthController::class,'repassword_req'])->name('auth.repasswordreq');
Route::get('/auth/repassword', [AuthController::class,'repassword'])->name('auth.repassword');
Route::post('/auth/repassword/check', [AuthController::class,'repassword_func'])->name('auth.repassword_func');


# for test only
Route::get('/email/verify/remove', [AuthController::class,'verify_verify_remove'])->middleware(['auth']);
Route::get('/email/verify/send', [AuthController::class,'verify_verify_send']);





# dashboard
Route::prefix('/dashboard')->middleware(['auth','dashboard'])->group(function(){
    # dashboard pages
    Route::prefix('')->group(function(){
        Route::get('/', [DashboardController::class,'home'])->name('dashboard.home');
        Route::get('/users', [DashboardController::class,'users'])->name('dashboard.users')->middleware(['can:users view']);
        Route::get('/roles', [DashboardController::class,'roles'])->name('dashboard.roles')->middleware(['can:roles view']);
        Route::get('/permissions', [DashboardController::class,'permissions'])->name('dashboard.permissions')->middleware(['can:permissions view']);
        Route::get('/stores', [DashboardController::class,'stores'])->name('dashboard.stores')->middleware(['can:stores view']);
        Route::get('/subscriptions', [DashboardController::class,'subscriptions'])->name('dashboard.subscriptions')->middleware(['can:subscriptions view']);
        Route::get('/design', [DashboardController::class,'design'])->name('dashboard.design')->middleware(['can:design view']);
        Route::get('/coupons', [DashboardController::class,'coupons'])->name('dashboard.coupons')->middleware(['can:coupons view']);
        Route::get('/payment', [DashboardController::class,'payment'])->name('dashboard.payment')->middleware(['can:payment view']);
        Route::get('/files', [DashboardController::class,'files'])->name('dashboard.files')->middleware(['can:files view']);
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
    Route::prefix('/coupons')->group(function(){
        Route::get('/create', [CouponsController::class,'create'])->name('dashboard.coupons.create')->middleware(['can:coupons create']);
        Route::post('/create_func', [CouponsController::class,'create_func'])->name('dashboard.coupons.create-func')->middleware(['can:coupons create']);
        Route::prefix('/{id}')->group(function(){
            Route::get('/edit', [CouponsController::class,'edit'])->name('dashboard.coupons.edit')->middleware(['can:coupons edit']);
            Route::post('/edit_func', [CouponsController::class,'edit_func'])->name('dashboard.coupons.edit-func')->middleware(['can:coupons edit']);
            Route::get('/active', [CouponsController::class,'active'])->name('dashboard.coupons.active')->middleware(['can:coupons active']);
            Route::post('/active_func', [CouponsController::class,'active_func'])->name('dashboard.coupons.active-func')->middleware(['can:coupons active']);
            Route::get('/delete', [CouponsController::class,'delete'])->name('dashboard.coupons.delete')->middleware(['can:coupons delete']);
            Route::post('/delete_func', [CouponsController::class,'delete_func'])->name('dashboard.coupons.delete-func')->middleware(['can:coupons delete']);
        });
    });
    # stores
    Route::prefix('/stores/{id}')->group(function(){
        Route::get('/viewer', [StoresController::class,'viewer'])->name('dashboard.stores.viewer')->middleware(['can:stores view']);
        Route::get('/edit', [StoresController::class,'edit'])->name('dashboard.stores.edit')->middleware(['can:stores view']);
        Route::post('/verify_store', [StoresController::class,'verify_store'])->name('dashboard.stores.viewer.verify-store')->middleware(['can:stores edit']);
        Route::post('/active_store', [StoresController::class,'active_store'])->name('dashboard.stores.viewer.active-store')->middleware(['can:stores edit']);
        Route::post('/edit_store', [StoresController::class,'edit_store'])->name('dashboard.stores.edit-func')->middleware(['can:stores edit']);
        Route::post('/edit_subdomain_store', [StoresController::class,'edit_subdomain_store'])->name('dashboard.stores.edit-subdomain-func')->middleware(['can:stores edit']);
        Route::get('/design', [StoresController::class,'design'])->name('dashboard.stores.design')->middleware(['can:stores edit']);
        Route::prefix('/design/{design}')->group(function(){
            Route::get('/edit', [DesignController::class,'edit_design_admin'])->name('dashboard.stores.design.edit');
            Route::post('/edit_func', [DesignController::class,'edit_design_admin_func'])->name('dashboard.stores.design.edit-func');
            Route::get('/active', [DesignController::class,'active_design_admin'])->name('dashboard.stores.design.active');
            Route::post('/active_func', [DesignController::class,'active_design_admin_func'])->name('dashboard.stores.design.active-func');
            Route::get('/verify', [DesignController::class,'verify_design_admin'])->name('dashboard.stores.design.verify');
            Route::post('/verify_func', [DesignController::class,'verify_design_admin_func'])->name('dashboard.stores.design.verify-func');
            Route::get('/view-store', [StorePublicController::class,'main'])->name('dashboard.stores.design.view-store');
        });
    });
    Route::prefix('/users/{id}')->group(function(){
        Route::get('/edit', [UsersController::class,'edit_admin'])->name('dashboard.users.edit')->middleware(['can:users view']);
        Route::post('/verify_user', [UsersController::class,'verify_user_admin'])->name('dashboard.users.verify-user')->middleware(['can:users edit']);
        Route::post('/active_user', [UsersController::class,'active_user_admin'])->name('dashboard.users.active-user')->middleware(['can:users edit']);
        Route::post('/upemail_user', [UsersController::class,'upemail_user_admin'])->name('dashboard.users.upemail-user')->middleware(['can:users edit']);
        Route::post('/uppassword_user', [UsersController::class,'uppassword_user_admin'])->name('dashboard.users.uppassword-user')->middleware(['can:users edit']);
        Route::post('/edit_user', [UsersController::class,'edit_user_admin'])->name('dashboard.users.edit-func')->middleware(['can:users edit']);
    });
    Route::prefix('/subscriptions/{id}')->group(function(){
        Route::get('/viewer', [SubscriptionsController::class,'viewer_admin'])->name('dashboard.subscriptions.viewer')->middleware(['can:subscriptions view']);
        Route::get('/editor', [SubscriptionsController::class,'editor_admin'])->name('dashboard.subscriptions.editor')->middleware(['can:subscriptions view']);
        Route::post('/update_transfer', [SubscriptionsController::class,'update_transfer_admin'])->name('dashboard.subscriptions.update-transfer-func')->middleware(['can:subscriptions edit']);
        Route::post('/update_date', [SubscriptionsController::class,'update_subscription_date_admin'])->name('dashboard.subscriptions.update-date')->middleware(['can:subscriptions edit']);
        Route::post('/update_payment', [SubscriptionsController::class,'update_subscription_payment_admin'])->name('dashboard.subscriptions.update-payment')->middleware(['can:subscriptions edit']);
        Route::post('/update_status', [SubscriptionsController::class,'update_subscription_status_admin'])->name('dashboard.subscriptions.update-status')->middleware(['can:subscriptions edit']);
        Route::post('/update_note', [SubscriptionsController::class,'update_subscription_note_admin'])->name('dashboard.subscriptions.update-note')->middleware(['can:subscriptions edit']);
    });
    Route::prefix('/transfer/{id}')->group(function(){
        Route::get('/', [SubscriptionsController::class,'edit_transfer_admin'])->name('dashboard.subscriptions.transfer.edit')->middleware(['can:subscriptions edit']);
        Route::post('/edit_transfer_func', [SubscriptionsController::class,'edit_transfer_admin_func'])->name('dashboard.subscriptions.transfer.edit-func')->middleware(['can:subscriptions edit']);
    });

    Route::prefix('/templates')->group(function(){
        Route::get('/create', [DesignController::class,'create_template'])->name('dashboard.design.template.create')->middleware(['can:design create']);
        Route::post('/create_func', [DesignController::class,'create_func_template'])->name('dashboard.design.template.create-func')->middleware(['can:design create']);
        Route::prefix('/{id}')->group(function(){
            Route::get('/active', [DesignController::class,'active_template'])->name('dashboard.design.template.active')->middleware(['can:design active']);
            Route::post('/active_func', [DesignController::class,'active_func_template'])->name('dashboard.design.template.active-func')->middleware(['can:design active']);
            Route::get('/edit', [DesignController::class,'edit_template'])->name('dashboard.design.template.edit')->middleware(['can:design edit']);
            Route::post('/edit_func', [DesignController::class,'edit_func_template'])->name('dashboard.design.template.edit-func')->middleware(['can:design edit']);
            //Route::get('/delete', [DesignController::class,'delete_template'])->name('dashboard.design.template.delete')->middleware(['can:design delete']);
            //Route::post('/delete_func', [DesignController::class,'delete_func_template'])->name('dashboard.design.template.delete-func')->middleware(['can:design delete']);
        });
    });

    Route::prefix('/files')->group(function(){
        Route::post('/create', [FilesController::class,'create_pfile'])->name('dashboard.files.create-pfile');
        Route::post('/delete', [FilesController::class,'delete_pfile'])->name('dashboard.files.delete-pfile'); 
        Route::post('/create_qrcode', [FilesController::class,'create_qrcode'])->name('dashboard.files.create-qrcode');
        Route::post('/delete_qrcode', [FilesController::class,'delete_qrcode'])->name('dashboard.files.delete-qrcode');
    });

   
});

#stores
Route::prefix('/{store}/dashboard')->middleware(['auth','storesdashboard'])->group(function(){
    Route::get('/', [StoresDashboardController::class,'dashboard'])->name('stores.dashboard');
    Route::get('/categories', [StoresDashboardController::class,'categories'])->name('stores.dashboard.categories');
    Route::get('/products', [StoresDashboardController::class,'products'])->name('stores.dashboard.products');
    Route::get('/files', [StoresDashboardController::class,'files'])->name('stores.dashboard.files');
    Route::get('/design', [StoresDashboardController::class,'design'])->name('stores.dashboard.design');
    Route::get('/subscriptions', [StoresDashboardController::class,'subscriptions'])->name('stores.dashboard.subscriptions');

    Route::prefix('/categories')->group(function(){
        Route::post('/create', [CategoriesController::class,'create'])->name('stores.dashboard.categories.create');
        Route::prefix('/{id}')->group(function(){
            Route::get('/edit', [CategoriesController::class,'edit'])->name('stores.dashboard.categories.edit');
            Route::post('/edit-func', [CategoriesController::class,'edit_func'])->name('stores.dashboard.categories.edit-func');
            Route::get('/active', [CategoriesController::class,'active'])->name('stores.dashboard.categories.active');
            Route::post('/active-func', [CategoriesController::class,'active_func'])->name('stores.dashboard.categories.active-func');
            Route::get('/delete', [CategoriesController::class,'delete'])->name('stores.dashboard.categories.delete');
            Route::post('/delete-func', [CategoriesController::class,'delete_func'])->name('stores.dashboard.categories.delete-func');
        });
    });
    Route::prefix('/products')->group(function(){
        Route::get('/create', [ProductsController::class,'create'])->name('stores.dashboard.products.create');
        Route::post('/create-func', [ProductsController::class,'create_func'])->name('stores.dashboard.products.create-func');
        Route::prefix('/{id}')->group(function(){
            Route::get('/edit', [ProductsController::class,'edit'])->name('stores.dashboard.products.edit');
            Route::post('/edit-func', [ProductsController::class,'edit_func'])->name('stores.dashboard.products.edit-func');
            Route::post('/edit-removeimage', [ProductsController::class,'edit_removeimage'])->name('stores.dashboard.products.edit-removeimage');
            Route::get('/active', [ProductsController::class,'active'])->name('stores.dashboard.products.active');
            Route::post('/active-func', [ProductsController::class,'active_func'])->name('stores.dashboard.products.active-func');
            Route::get('/delete', [ProductsController::class,'delete'])->name('stores.dashboard.products.delete');
            Route::post('/delete-func', [ProductsController::class,'delete_func'])->name('stores.dashboard.products.delete-func');
        }); 
    });
    Route::prefix('/files')->group(function(){
        Route::post('/create', [FilesController::class,'create'])->name('stores.dashboard.files.create');
        Route::post('/delete', [FilesController::class,'delete'])->name('stores.dashboard.files.delete'); 
        Route::get('/searchproducts', [FilesController::class,'searchproducts'])->name('stores.dashboard.files.searchproducts'); 
        Route::get('/updateproductimage', [FilesController::class,'updateproductimage'])->name('stores.dashboard.files.updateproductimage');
        Route::post('/aslogo', [FilesController::class,'aslogo'])->name('stores.dashboard.files.aslogo');
    });

    Route::prefix('/subscriptions')->group(function(){
        Route::prefix('/{id}')->group(function(){
            Route::get('/create', [SubscriptionsController::class,'create'])->name('stores.dashboard.subscriptions.create');
            Route::post('/create_func', [SubscriptionsController::class,'create_func'])->name('stores.dashboard.subscriptions.create-func');
        }); 
    });
    Route::prefix('/st-subs')->group(function(){
        Route::prefix('/{id}')->group(function(){
            Route::get('/uploadtransfer', [SubscriptionsController::class,'uploadtransfer'])->name('stores.dashboard.subscriptions.uploadtransfer');
            Route::post('/uploadtransfer_func', [SubscriptionsController::class,'uploadtransfer_func'])->name('stores.dashboard.subscriptions.uploadtransfer-func');
        }); 
    });
    Route::prefix('/stores')->group(function(){
        Route::post('/active', [StoresController::class,'active'])->name('stores.dashboard.stores.active');
    });

    Route::prefix('/design')->group(function(){
        Route::post('/add', [DesignController::class,'add_design'])->name('stores.dashboard.design.add');
        Route::prefix('/{id}')->group(function(){
            Route::get('/active', [DesignController::class,'active_design_store'])->name('stores.dashboard.design.active');
            Route::post('/active_func', [DesignController::class,'active_design_store_func'])->name('stores.dashboard.design.active-func');
            Route::get('/edit', [DesignController::class,'edit_design_store'])->name('stores.dashboard.design.edit');
            Route::post('/edit_func', [DesignController::class,'edit_design_store_func'])->name('stores.dashboard.design.edit-func');
            Route::get('/view-store', [StorePublicController::class,'main'])->name('stores.dashboard.design.view-store');
        });
    });
    Route::prefix('/cards')->group(function(){
        Route::prefix('/{id}')->group(function(){
            Route::get('', [DesignController::class,'download_card'])->name('stores.dashboard.design.card-download');
        });
    });
});

#main
Route::prefix('/')->group(function(){
    Route::prefix('')->group(function(){
        Route::get('/', [PublicController::class,'main'])->name('main');
    });


    #join-us 
    Route::get('/join-us', [StoresController::class,'join_us'])->middleware(['auth','joinus'])->name('main.join-us');
    Route::prefix('/join-us')->middleware(['auth','joinus'])->group(function(){
        Route::post('/create', [StoresController::class,'create'])->name('main.join-us.create');
    });
    Route::prefix('{store}')->group(function(){
        Route::get('/', [StorePublicController::class,'main'])->middleware(['storespublic'])->name('storespublic.main'); 
        Route::get('/disactive', [StorePublicController::class,'disactive'])->middleware(['storespublicdisactive'])->name('storespublic.disactive');
    });
});


/*
    #to create storage link
    Route::get('/symlink', function () {
        Artisan::call('storage:link');
    });


*/