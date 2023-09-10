<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
class PublicController extends Controller
{
    function main(){ 
        #$response = Curl::to('https://mofareh.net/api/apps/tabs/home/get')->asJson()->post();
        #$data = $response->data->random;
        
        return view('main.home',['status'=>Config::get('site.status')]);
    }

    function update_status(){
        $status = false;
        Config::set('site.status', $status);
        return "Site status updated to false new";
    }
}
