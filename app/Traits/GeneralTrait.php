<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use App\Models\History;
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
    # upload file
    public function uploadFile($file, $dir = "")
    {
        $random_name = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $fileName = substr(str_shuffle($random_name), 0, 10) . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->putFileAs($dir, $file, $fileName);
        return array('dir'=>$dir,'file'=>$fileName);      
    }

    #for files dashboard page
    public function filesdashboardpage($directories){
        $filesAndFolders = [];
        $files = Storage::disk('public')->files($directories);
        $subdirectories = Storage::disk('public')->directories($directories);

        foreach ($subdirectories as $subdirectory) {
            $directoryName = basename($subdirectory);
            $filesAndFolders[] = [
                'name' => $directoryName,
                'type' => 'folder',
                'extension' => null,
                'path' => route('dashboard.files', ['directory' => $directoryName]),
            ];
        }
        foreach ($files as $file) {
            $pathInfo = pathinfo($file);
            if ($pathInfo['filename']) {
                $filesAndFolders[] = [
                    'name' => $pathInfo['filename'],
                    'type' => 'file',
                    'extension' => $pathInfo['extension'],
                    'path' => env('APP_URL') . '/storage'.'/' . $file,
                ];
            }
        }

        
        $directories_line = [];
        $reply = "";
        $pathToArray = array_filter(explode('/', $directories));
        foreach ($pathToArray as $index => $value) {
            $directories_line[$index] = $value;
            if(count($pathToArray)!==$index){
                $reply .= $value.'/';
            }
        }

        return array('contents'=>$filesAndFolders,'directories_line'=>$directories_line,'reply'=>$reply);
    }

    function recordHistory($table,$description){
        $user = auth()->user();
        History::create([
            'by'=>$user->id,
            'table'=>$table,
            'description'=>$description,
        ]);
    }
}