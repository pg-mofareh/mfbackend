<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Stores;
use App\Models\Files;
use App\Models\Subscriptions;
use App\Models\StoreSubs;
use App\Models\Transfers;
use App\Models\History;
use DB;
use Carbon\Carbon;
class SubscriptionsController extends Controller
{
    use GeneralTrait;
    function create(Request $req){
        $subscription = Subscriptions::where([['id',$req->id]])->first();
        return view('stores.subscriptions-services',['subscription'=>$subscription,'store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
    }

    function create_func(Request $req){
        $rules = [
            'subscription' => ['required'],
            'payment_method' => ['required','string'],
            'price' => ['required'],
            'discount_price' => ['nullable'],
            'discount' => ['nullable'],
            'tax' => ['required'],
            'months' => ['required'],
            'start_at' => ['required'],
            'total' => ['required']
        ];
        $messages = [];
        $attributes = ['subscription' => 'الباقة','payment_method'=>'طريقة الدفع','price'=>'السعر','discount_price'=>'سعر الخصم','discount'=>'الخصم','tax'=>'الضريبة','total'=>'المجموع','months'=>'المدة','start_at'=>'يبدأ من'];
        $validator = Validator::make($req->only('subscription','payment_method','price','discount_price','discount','tax','total','months','start_at'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        $end_at = $this->endat_func($req->start_at,$req->months);
        try{
            $subscription = StoreSubs::create([
                'store'=>$req->input('middleinfo.store'),
                'subscription'=>$req->subscription,
                'payment_method'=>$req->payment_method,
                'price'=>$req->price,
                'discount_price'=>$req->discount_price,
                'discount'=>$req->discount,
                'tax'=>$req->total*0.15,
                'total'=>$req->total,
                'start_at'=>$req->start_at,
                'end_at'=> $end_at
            ]);
            if($subscription){
                $this->record_stsubs_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $subscription->id, 'create', "created subscription");
            }
        }catch (Exception $e) {
            return back()->withErrors(['is_last'=>'فشل الإشتراك']);
        } 

        return redirect()->route('stores.dashboard.subscriptions',['store'=>$req->store])->withSuccess('تم إنشاء الإشتراك'); 
    }

    function uploadtransfer(Request $req){
        $subscription = StoreSubs::join('subscriptions', 'st_subs.subscription', '=', 'subscriptions.id')
        ->where('st_subs.id', $req->id)
        ->first(['st_subs.id as id', 'subscriptions.title as title','st_subs.payment_method','st_subs.price','st_subs.discount_price','st_subs.tax','st_subs.discount','st_subs.total']);
        return view('stores.subscriptions-services',['subscription'=>$subscription,'store_name' => $req->input('middleinfo.name'),'user_name' => $req->input('middleinfo.user_fistname').' '.$req->input('middleinfo.user_lastname')]);
    }

    function uploadtransfer_func(Request $req){
        $rules = [
            'transfer' => ['required','max:2048']
        ];
        $messages = [];
        $attributes = ['transfer' => 'الحوالة'];
        $validator = Validator::make($req->only('transfer'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        if ($req->hasFile('transfer')) {
            if($upload = $this->uploadFile($req->file('transfer'), '/stores'.'/'.$req->input('middleinfo.store').'/transfers')){
                $file_parts = explode(".", $upload['file']);
                $file = Files::create(['store'=>$req->input('middleinfo.store'),'kind'=>'transfer','title'=>null,'name'=>$file_parts[0],'extension'=>$file_parts[1],'directories'=>$upload['dir']]);
                if($file){
                    $transfer = Transfers::create([
                        'store'=>$req->input('middleinfo.store'),
                        'subscription'=>$req->subscription,
                        'file'=>$file->id,
                        'total'=>$req->total
                    ]);
                    if($transfer){
                        $this->record_transfer_process($req->input('middleinfo.user'),$req->input('middleinfo.store'), $transfer->id, 'create', "created transfer");
                    }
                }
                return redirect()->route('stores.dashboard.subscriptions',['store'=>$req->store])->withSuccess('تم رفع الحوالة بنجاح'); 
            }            
        }

        return back()->withErrors([ 'is_last' => 'حدث خطأ ما']);
    }


    
    #admin 
    function viewer_admin(Request $req){
        $subscription = StoreSubs::join('subscriptions', 'st_subs.subscription', '=', 'subscriptions.id')
        ->where('st_subs.id', $req->id)
        ->first(['st_subs.id as id','st_subs.subscription as subscription_id', 'subscriptions.title as title','st_subs.payment_method','st_subs.price','st_subs.discount_price','st_subs.tax','st_subs.discount','st_subs.total','st_subs.start_at','st_subs.end_at','st_subs.status','st_subs.note']);
        if($subscription){
            $package = Subscriptions::where('id',$subscription->subscription_id)->first();
            $transfers = Transfers::leftJoin('files', 'files.id', '=', 'transfers.file')->where([['subscription',$subscription->id]])->get([
                'transfers.id',
                'transfers.status',
                'transfers.subscription',
                DB::raw("CASE WHEN transfers.file IS NULL THEN NULL ELSE CONCAT('/storage', files.directories, '/', files.name, '.', files.extension) END AS image"),
                'transfers.created_at',
            ]);
        }else{
            return redirect()->route('dashboard.subscriptions')->withErrors(['الإشتراك غير موجود']);
        }
        return view('dashboard.subscriptions-services',['package'=>$package,'subscription'=>$subscription,'transfers'=>$transfers]);
    }

    function editor_admin(Request $req){
        $subscription = StoreSubs::join('subscriptions', 'st_subs.subscription', '=', 'subscriptions.id')
        ->where('st_subs.id', $req->id)
        ->first(['st_subs.id as id','st_subs.subscription as subscription_id', 'subscriptions.title as title','st_subs.payment_method','st_subs.price','st_subs.discount_price','st_subs.tax','st_subs.discount','st_subs.total','st_subs.start_at','st_subs.end_at','st_subs.status','st_subs.note']);
        return view('dashboard.subscriptions-services',['subscription'=>$subscription]);
    }

    function edit_transfer_admin(Request $req){
        $transfer = Transfers::leftJoin('files', 'files.id', '=', 'transfers.file')
        ->where([['transfers.id', $req->id]])
        ->first([
            'transfers.id',
            'transfers.status',
            'transfers.subscription',
            DB::raw("CASE WHEN transfers.file IS NULL THEN NULL ELSE CONCAT('/storage', files.directories, '/', files.name, '.', files.extension) END AS image"),
            'transfers.total',
            'transfers.note',
            'transfers.created_at',
        ]);
        return view('dashboard.subscriptions-services',['transfer'=>$transfer]);
    }

    function update_transfer_admin(Request $req){
        $rules = [
            'transfer' => ['required'],
            'response' => ['required']
        ];
        $messages = [];
        $attributes = ['transfer' => 'الحوالة','response'=> 'نوع الرد'];
        $validator = Validator::make($req->only('transfer','response'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        if($req->response =="accepted"){
            $transfer = Transfers::where([['id',$req->id]])->first();
            if($transfer && $subscription = StoreSubs::where([['id',$transfer->subscription]])->first()){
                if($subscription->start_at < now()){
                    $subscription->update(['start_at'=>now(),'end_at'=>$this->endat_func(now(),$this->calc_months($subscription->start_at,$subscription->end_at))]);
                    $transfer->update(['status'=>$req->response,'note'=>$req->note]);
                    return back()->withSuccess('تم تحديث الحوالة بنجاح');
                }else{
                    $transfer->update(['status'=>$req->response,'note'=>$req->note]);
                    return back()->withSuccess('تم تحديث الحوالة بنجاح');
                }
            }
        }elseif(Transfers::where([['id',$req->id]])->update(['status'=>$req->response,'note'=>$req->note])){
            return back()->withSuccess('تم تحديث الحوالة بنجاح');
        }

        return back()->withErrors([ 'حدث خطأ ما' ]);
    }

    function edit_transfer_admin_func(Request $req){
        $rules = [
            'transfer' => ['required'],
            'response' => ['required']
        ];
        $messages = [];
        $attributes = ['transfer' => 'الحوالة','response'=> 'نوع الرد'];
        $validator = Validator::make($req->only('transfer','response'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        if($req->response=="update_note" && Transfers::where([['id',$req->id]])->update(['note'=>$req->note])){
            return back()->withSuccess('تم تحديث الحوالة بنجاح');
        }else{
            if($req->response =="accepted"){
                $transfer = Transfers::where([['id',$req->id]])->first();
                if($transfer && $subscription = StoreSubs::where([['id',$transfer->subscription]])->first()){
                    if($subscription->start_at < now()){
                        $subscription->update(['start_at'=>now(),'end_at'=>$this->endat_func(now(),$this->calc_months($subscription->start_at,$subscription->end_at))]);
                        $transfer->update(['status'=>$req->response,'note'=>$req->note]);
                        return back()->withSuccess('تم تحديث الحوالة بنجاح');
                    }else{
                        $transfer->update(['status'=>$req->response,'note'=>$req->note]);
                        return back()->withSuccess('تم تحديث الحوالة بنجاح');
                    }
                }
            }elseif(Transfers::where([['id',$req->id]])->update(['status'=>$req->response])){
                return back()->withSuccess('تم تحديث الحوالة بنجاح');
            }
        }

        return back()->withErrors([ 'حدث خطأ ما' ]);
    }

    function update_subscription_date_admin(Request $req){
        $rules = [
            'id' => ['required'],
            'start_at' => ['required'],
            'end_at' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم الإشتراك', 'start_at' => 'تاريخ البداية', 'end_at' => 'تاريخ الإنتهاء'];
        $validator = Validator::make($req->only('id','start_at','end_at'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        if(StoreSubs::where('id',$req->id)->update(['start_at'=>$req->start_at,'end_at'=>$req->end_at])){
            $this->recordHistory('st_subs','has been changed start_at and end_at');
            return back()->withSuccess('تم تحديث تاريخ الإشتراك بنجاح');
        }

        return back()->withErrors(['فشل تحديث تاريخ الإشتراك']);
    }

    function update_subscription_payment_admin(Request $req){
        $rules = [
            'id' => ['required'],
            'price' => ['required'],
            'discount_price' => ['required'],
            'tax' => ['required'],
            'discount' => ['required'],
            'total' => ['required'],
        ];
        $messages = [];
        $attributes = ['id' => 'رقم الإشتراك', 'price' => 'سعر الباقة', 'discount_price' => 'خصم الباقة', 'tax' => 'الضريبة', 'discount' => 'الخصم الخاص', 'total' => 'المجموع'];
        $validator = Validator::make($req->only('id','price','discount_price','tax','discount','total'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        if(StoreSubs::where('id',$req->id)->update(['price'=>$req->price,'discount_price'=>$req->discount_price,'tax'=>$req->tax,'discount'=>$req->discount,'total'=>$req->total])){
            $this->recordHistory('st_subs','has been changed start_at and end_at');
            return back()->withSuccess('تم تحديث بيانات الدفع للإشتراك بنجاح');
        }

        return back()->withErrors(['فشل تحديث بيانات الدفع للإشتراك']);
    }

    function update_subscription_status_admin(Request $req){
        $rules = [
            'id' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم الإشتراك'];
        $validator = Validator::make($req->only('id'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        if($subscription = StoreSubs::where('id',$req->id)->first()){
            if($subscription->status=="active" && StoreSubs::where('id',$req->id)->update(['status'=>'disactive'])){
                $this->recordHistory('st_subs','has been changed start_at and end_at');
                return back()->withSuccess('تم تحديث حالة الإشتراك بنجاح');
            }elseif($subscription->status=="disactive" && StoreSubs::where('id',$req->id)->update(['status'=>'active'])){
                $this->recordHistory('st_subs','has been changed start_at and end_at');
                return back()->withSuccess('تم تحديث حالة الإشتراك بنجاح');
            }         
        }

        return back()->withErrors(['فشل تحديث حالة الإشتراك']);
    }

    function update_subscription_note_admin(Request $req){
        $rules = [
            'id' => ['required']
        ];
        $messages = [];
        $attributes = ['id' => 'رقم الإشتراك'];
        $validator = Validator::make($req->only('id'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        if(StoreSubs::where('id',$req->id)->update(['note'=>$req->note])){
            $this->recordHistory('st_subs','has been changed start_at and end_at');
                return back()->withSuccess('تم تحديث ملاحظة الإشتراك بنجاح');      
        }

        return back()->withErrors(['فشل تحديث ملاحظة الإشتراك']);
    }





    # functions tools
    function endat_func($start_at,$months){
        $startAt = Carbon::parse($start_at);
        $months = (int)$months;
        // Calculate the end date by adding months to the start date
        $endAt = $startAt->copy()->addMonths($months);
        // Format the end date as a string (adjust the format as needed)
        return $endAtFormatted = $endAt->format('Y-m-d H:i:s');
    }

    function calc_months($start_at,$end_at){
        $date1 = \Carbon\Carbon::parse($start_at);
        $date2 = \Carbon\Carbon::parse($end_at);
        return $date1->diffInMonths($date2);
    }

    function record_stsubs_process($by,$store,$item,$process,$description){
        History::create([
            'by' => $by,
            'store' => $store,
            'table' => 'st_subs',
            'item' => $item,
            'process'=> $process,
            'description' => $description
        ]);
    }
    function record_transfer_process($by,$store,$item,$process,$description){
        History::create([
            'by' => $by,
            'store' => $store,
            'table' => 'transfers',
            'item' => $item,
            'process'=> $process,
            'description' => $description
        ]);
    }


}
