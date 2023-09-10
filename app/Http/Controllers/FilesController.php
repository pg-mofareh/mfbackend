<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Models\Files;
class FilesController extends Controller
{
    use GeneralTrait;
    
    function create(Request $req, $directories = null){
        $directoryPath = $directories ? '/' . $directories : '/';
        return view('dashboard.files-services',['directoryPath'=>$directoryPath]);
    }

    function create_func(Request $req, $directories = null){
        $directoryPath = $directories ? '/' . $directories : '/';
        if($req->type=="folder"){
            # validation inputs
            $rules = [
                'folder' => ['required','max:72']
            ];
            $messages = [];
            $attributes = ['folder' => 'المجلد'];
            $validator = Validator::make($req->only('folder'), $rules,[], $attributes);
            if($validator->fails()) {
                $errors = json_decode(json_encode($validator->messages()), true);
                return back()->withErrors($errors);
            }

            if(Storage::disk('public')->makeDirectory($directoryPath.'/'.$req->folder)){
                $route = 'dashboard/files' . $directoryPath;
                return redirect($route)->withSuccess('تم بنجاح إنشاء المجلد بنجاح');
            }

            return back()->withErrors([ 'is_last' => 'فشل إنشاء المجلد']);
        }elseif($req->type=="file"){
            # validation inputs
            $rules = [
                'file' => ['required','max:2048']
            ];
            $messages = [];
            $attributes = ['file' => 'الملف'];
            $validator = Validator::make($req->only('file'), $rules,[], $attributes);
            if($validator->fails()) {
                $errors = json_decode(json_encode($validator->messages()), true);
                return back()->withErrors($errors);
            }

            
            if ($req->hasFile('file')) {
                if($upload = $this->uploadFile($req->file('file'), $directoryPath)){
                    $file_parts = explode(".", $upload['file']);
                    Files::create(['name'=>$file_parts[0],'extension'=>$file_parts[1],'directories'=>$upload['dir']]);
                    $route = 'dashboard/files' . $directoryPath;
                    return redirect($route)->withSuccess('تم بنجاح إنشاء الملف بنجاح');
                }            
            }


            return back()->withErrors([ 'is_last' => 'فشل إنشاء الملف']);
        }

        return back()->withErrors([ 'is_last' => 'حدث خطأ ما']);
    }
    
    function delete(Request $req, $directories = null){
        $directoryPath = $directories ? '/' . $directories : '/';
        if (isset($req->folder)) {
            $deletedFolderPath = $directoryPath . '/' . $req->folder;
        
            // Use the listContents method to get the contents of the directory
            $contents = Storage::disk('public')->listContents($deletedFolderPath);
        
            // Collect the contents into a collection
            $contentsCollection = collect($contents);
        
            // Filter out any non-directory items (e.g., files)
            $directories = $contentsCollection->where('type', 'dir')->count();
            $files = $contentsCollection->where('type', 'file')->count();
        
            if ($directories > 0 || $files > 0) {
                // The directory has subdirectories or files, so it's not empty
                return back()->withErrors(['is_last' => 'فشل حذف المجلد: المجلد غير فارغ']);
            }
        
            if (Storage::disk('public')->deleteDirectory($deletedFolderPath)) {
                $route = 'dashboard/files' . $directoryPath;
                return redirect($route)->withSuccess('تم بنجاح حذف المجلد');
            }          
            return back()->withErrors(['is_last' => 'فشل حذف المجلد']);
        }elseif(isset($req->file)){
            $file_parts = explode(".", $req->file);
            if(Files::where([['name','=',$file_parts[0]],['extension','=',$file_parts[1]],['directories','=',$directoryPath]])->delete()){
                if(Storage::disk('public')->delete($directoryPath . '/' . $req->file)){
                    $route = 'dashboard/files' . $directoryPath;
                    return redirect($route)->withSuccess('تم بنجاح حذف الملف بنجاح');
                }
            }            
            return back()->withErrors([ 'is_last' => 'فشل حذف الملف']);
        }
        return back()->withErrors([ 'is_last' => 'حدث خطأ ما']);
    }
}
