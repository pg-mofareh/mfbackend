<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use App\Models\Posts;
use App\Models\Categories;
trait GeneralTrait
{
    # api need login
    public function returnNeedLogin()
    {
        return response()->json([
            'status' => false,
            'login' => false
        ]);
    }
    # errors
    public function returnErrors($errors, $login = true)
    {
        $response = [
            'status' => false,
            'errors' => $errors
        ];
        if ($login == true) {
            $response['login'] = true;
        }
        return response()->json($response);
    }
    # success
    public function returnSuccessMessage($msg = "")
    {
        return response()->json([
            'status' => true,
            'msg' => $msg
        ]);
    }
    # success with data
    public function returnData($value, $msg = "")
    {
        return response()->json([
            'status' => true,
            'msg' => $msg,
            'data' => $value
        ]);
    }
}