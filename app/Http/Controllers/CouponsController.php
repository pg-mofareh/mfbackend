<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Files;
use App\Models\Coupons;
use App\Models\User;
class CouponsController extends Controller
{
    use GeneralTrait;

    function create(){
        return view('dashboard.coupons-services');
    }

    function delete(Request $req){
        $coupon = Coupons::where('id',$req->id)->first();
        return view('dashboard.coupons-services',['coupon'=>$coupon]);
    }

    function edit(Request $req){
        $coupon = Coupons::where('id',$req->id)->first();
        return view('dashboard.coupons-services',['coupon'=>$coupon]);
    }

    function active(Request $req){
        $coupon = Coupons::where('id',$req->id)->first();
        return view('dashboard.coupons-services',['coupon'=>$coupon]);
    }


    # func here
    function create_func(Request $req){
        $rules = [
            'user' => ['required'],
            'code' => ['required','string','max:48'],
            'started' => ['required'],
            'expiry' => ['required']
        ];
        $messages = [];
        $attributes = ['user'=>'العميل','code' => 'كود الخصم','started' => 'بداية فعالة الكود','expiry' => 'نهاية الكود'];
        $validator = Validator::make($req->only('user','code','started','expiry'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try {
            if($user = User::where('id',$req->user)->orWhere('email',$req->user)->first())
            {
                if(Coupons::create([
                    'user'=>$user->id,
                    'code'=>$req->code,
                    'started'=>$req->started,
                    'expiry'=>$req->expiry
                ])){
                    return redirect()->route('dashboard.coupons')->withSuccess('تم إنشاء كود الخصم بنجاح'); 
                }
            }
            
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'message' => $e->getMessage(),
            ]);
        }    

        return back()->withErrors([ 'is_last' => 'حدث خطأ ما']);
    }

    function delete_func(Request $req){
        $rules = [
            'id' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم الكوبون'];
        $validator = Validator::make($req->only('id'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try {
            Coupons::where('id',$req->id)->delete();
            return redirect()->route('dashboard.coupons')->withSuccess('تم حذف الكوبون بنجاح'); 
        } catch (Exception $e) {
            # if there error run here only
        }    

        return back()->withErrors([ 'is_last' => 'حدث خطأ ما']);
    }

    function edit_func(Request $req){
        $rules = [
            'id' => ['required'],
            'code' => ['required','string','max:48'],
            'started' => ['required'],
            'expiry' => ['required']
        ];
        $messages = [];
        $attributes = ['id'=>'رقم الكوبون','code' => 'كود الخصم','started' => 'بداية فعالة الكود','expiry' => 'نهاية الكود'];
        $validator = Validator::make($req->only('id','code','started','expiry'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try {
            Coupons::where('id',$req->id)->update([
                'code'=>$req->code,
                'started'=>$req->started,
                'expiry'=>$req->expiry
            ]);
            return back()->withSuccess('تم تعديل الكوبون بنجاح'); 
        } catch (Exception $e) {
            return 'error';
        }    

        return back()->withErrors([ 'is_last' => 'حدث خطأ ما']);
    }

    function active_func(Request $req){
        $rules = [
            'id' => ['required'],
            'status' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم الكوبون','status' => 'حالة الكوبون'];
        $validator = Validator::make($req->only('id','status'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        try {
            if(Coupons::where([['id', $req->id],['is_active','!=',$req->status]])->update(['is_active'=>$req->status])){
                return redirect()->route('dashboard.coupons')->withSuccess('تم تحديث حالة الكوبون بنجاح'); 
            }            
        } catch (Exception $e) {
            # if there error run here only
        }    

        return back()->withErrors([ 'is_last' => 'حدث خطأ ما']);
    }

}
