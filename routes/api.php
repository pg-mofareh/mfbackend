<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::prefix('/auth')->group(function(){
    Route::get('/login', [AuthController::class,'api_login']);
    Route::get('/logout', [AuthController::class,'api_logout']);
});

