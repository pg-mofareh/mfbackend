<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
class TestController extends Controller
{
    function index(){ 
        #$response = Curl::to('https://mofareh.net/api/apps/tabs/home/get')->asJson()->post();
        #$data = $response->data->random;
        $data = [
            array('sorah'=>'al fatiha','numbers'=>7,'image'=>'https://www.tajquran.com/upload/images/product_images/5293_item_22.jpg'),
            array('sorah'=>'al nas','numbers'=>5,'image'=>'https://www.tajquran.com/upload/images/product_images/5293_item_22.jpg'),
            array('sorah'=>'al masad','numbers'=>4,'image'=>'https://www.tajquran.com/upload/images/product_images/5293_item_22.jpg'),
            array('sorah'=>'al asr','numbers'=>3,'image'=>'https://www.tajquran.com/upload/images/product_images/5293_item_22.jpg'),
            array('sorah'=>'al qouther','numbers'=>3,'image'=>'https://www.tajquran.com/upload/images/product_images/5293_item_22.jpg'),
            array('sorah'=>'al baqarah','numbers'=>255,'image'=>'https://www.tajquran.com/upload/images/product_images/5293_item_22.jpg'),
            array('sorah'=>'al omran','numbers'=>178,'image'=>'https://www.tajquran.com/upload/images/product_images/5293_item_22.jpg'),
        ];
        return view('quran.main',['data'=>json_decode(json_encode($data))]); 
    }
}
