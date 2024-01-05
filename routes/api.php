<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/main', function(){
    $data = ['message' => 'test api'];
    return response()->json($data);
});