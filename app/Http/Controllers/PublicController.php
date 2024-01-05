<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Files;
use App\Models\Coupons;
use App\Models\User;
use DB;
class PublicController extends Controller
{
    function main(){
        return view('main.home');
    }
}
