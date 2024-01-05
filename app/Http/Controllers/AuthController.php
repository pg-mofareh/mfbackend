<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Auth\Events\Registered;
use App\Traits\GeneralTrait;
use App\Traits\MailTrait;
use App\Models\User;
use App\Models\History;
use App\Models\Stores;
use Carbon\Carbon;
class AuthController extends Controller
{
    use GeneralTrait;
    use MailTrait;

    ########################################################
    ### register , login , logout , repassword -, validation -
    ########################################################


    #############################
    #### web
    #############################
    function login(){ #done
        return view('auth.main');
    }
    function login_func(Request $req){ #done
        # validation inputs
        $rules = [
            'email' => ['required','email'],
            'password' => ['required']
        ];
        $messages = [];
        $attributes = ['email' => 'الإيميل','password' => 'كلمة السر'];
        $validator = Validator::make($req->only('email','password'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['email','password']));
        }

        # check from user
        if(auth()->attempt(['email'=>$req->email,'password'=>$req->password])){
            $user = auth()->user();
            $req->session()->regenerate();
            if($user->email_verified_at==null){

                if ($user->otp_number !== null && $user->otp_expiry >= Carbon::now()) {
                    return redirect()->route('auth.verify');
                }

                $otp = rand(10000, 99999);
                $this->SendMailTrait('subject', 'title', $otp, $user->email, 'verify');
                $user->otp_number = Crypt::encryptString($otp);
                $user->otp_expiry = Carbon::now()->addMinutes(2);
                $user->save();

                return redirect()->route('auth.verify');
            }elseif($user->hasRole(['super admin','admin'])){
                return redirect()->route('dashboard.home');
            }else{
                if($store = Stores::where([['user',$user->id]])->first()){
                    return redirect()->route('stores.dashboard',['store'=>$store->subdomain]);
                }else{
                    return redirect()->route('main');
                }
                
            }
        }
        return back()->withErrors([ 'email' => trans('auth.web.login.failed')])->withInput($req->only(['email','password']));
    }

    function register(){ #done
        return view('auth.main');
    }
    function register_func(Request $req){ #done
        # validation inputs
        $rules = [
            'first_name' => ['required','string','min:2','max:14'],
            'last_name' => ['required','string','min:2','max:14'],
            'phone_number' => ['nullable','string','min:9','max:10'],
            'email' => ['required','email','max:250'],
            'password' => ['required','min:8','max:16']
        ];
        $messages = [];
        $attributes = ['first_name' => 'الإسم الأول','last_name' => 'الإسم الأخير','phone_number' => 'رقم الجوال','email' => 'الإيميل','password' => 'كلمة السر'];
        $validator = Validator::make($req->only('first_name','last_name','phone_number','email','password'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['first_name','last_name','phone_number','email','password']));
        }
        
        $user = User::where('email','=',$req->email)->first();
        if(!$user){
            $newuser = new User;
            $newuser->first_name = $req->first_name;
            $newuser->last_name = $req->last_name;
            $newuser->phone_number = $req->phone_number;
            $newuser->email = $req->email;
            $newuser->password = bcrypt($req->password);
            $newuser->otp_number = null;
            $newuser->otp_expiry = null;
            $newuser->save();
            if($newuser){
                return redirect()->route('auth.login') ->withSuccess('تم إنشاء مستخدم جديد, قم بتسجيل الدخول');
            }  
        }elseif($user){
            return back()->withErrors(['هذا المستخدم موجود'])->withInput($req->only(['first_name','last_name','phone_number','email','password']));
        }
        return back()->withErrors(['فشل التسجيل يرجى المحاولة لاحقا'])->withInput($req->only(['first_name','last_name','phone_number','email','password']));
    }

    function logout(){ #done
        Auth::logout();
        return redirect('auth/login');
    }
    

    function verify(){
        return view('auth.main');
    }

    function verify_check(Request $req){
        $rules = [
            'number' => ['required']
        ];
        $messages = [];
        $attributes = ['number' => 'رقم التحقق'];
        $validator = Validator::make($req->only('number'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['number']));;
        }

        $user = auth()->user();

        if ($user->otp_number !== null && Crypt::decryptString($user->otp_number) == $req->number && $user->otp_expiry >= Carbon::now()) {
            $user->email_verified_at = Carbon::now()->toDateTimeString();
            $user->otp_number = null;
            $user->otp_expiry = null;
            $user->save();
            return redirect()->route('auth.login')->withSuccess('تم التحقق بنجاح، قم بتسجيل الدخول');
        } elseif ($user->otp_number !== null && Crypt::decryptString($user->otp_number) != $req->number) {
            return back()->withErrors(['رقم التحقق خاطئ']);
        } elseif ($user->otp_number !== null && $user->otp_expiry < Carbon::now()) {
            return back()->withErrors(['رقم التحقق منتهي']);
        }
    
        return redirect()->route('auth.login')->withErrors(['حدث خطأ ما']);
    }


    function repassword_req(Request $req){
        $rules = [
            'email' => ['required','email','max:250']
        ];
        $messages = [];
        $attributes = ['email' => 'الإيميل'];
        $validator = Validator::make($req->only('email'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['email']));
        }

        $user = User::where('email', $req->email)->first(['email','otp_number','otp_expiry']);
        if($user){

            if ($user->otp_number !== null && $user->otp_expiry >= Carbon::now()) {
                return redirect()->route('auth.repassword')->withInput($req->only(['email']));
            }

            $otp = rand(10000,99999);
            $upuser = User::where('email', $req->email)->update(['otp_number'=>Crypt::encryptString($otp),'otp_expiry'=>Carbon::now()->addMinutes(2)]);
            $this->SendMailTrait('','',$otp,$user->email,'verify');
            
            return redirect()->route('auth.repassword')->withInput($req->only(['email']));
        }
        return redirect()->route('auth.login');
    }

    function repassword(Request $req){
        if(old('email')){
            return view('auth.main');
        }
        return redirect()->route('auth.login');
    }

    function repassword_func(Request $req){
        $rules = [
            'number' => ['required','numeric'],
            'email' => ['required','email','max:250'],
            'password' => ['required','max:21',Password::min(8)->mixedCase()->letters()->numbers()],
            'repassword' => ['required','same:password']
        ];
        $messages = [];
        $attributes = ['number' => 'رمزالتحقق','email' =>'الإيميل','password' => 'كلمة السر','repassword' => 'إعادة كلمة السر'];
        $validator = Validator::make($req->only('number','email','password','repassword'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors)->withInput($req->only(['number','email','password','repassword']));
        }
        
        $user = User::where([['email', $req->email],['otp_number','!=',null],['otp_expiry','!=',null]])->first();

        if($user){
            if (Crypt::decryptString($user->otp_number) == $req->number && $user->otp_expiry >= Carbon::now()) {
                User::where('email', $req->email)->update([
                    'password'=>bcrypt($req->password),
                    'otp_number'=>null,
                    'otp_expiry'=>null
                ]);
                return redirect()->route('auth.login')->withSuccess('تم تحديث كلمة السر , قم بتسجيل الدخول')->withInput($req->only(['email']));
            } elseif (Crypt::decryptString($user->otp_number) != $req->number) {
                return back()->withErrors(['رقم التحقق خاطئ'])->withInput($req->only(['number','email','password','repassword']));
            } elseif ($user->otp_expiry < Carbon::now()) {
                return back()->withErrors(['رقم التحقق منتهي'])->withInput($req->only(['number','email','password','repassword']));
            }         
        }

        return redirect()->route('auth.login')->withErrors(['حدث خطأ ما'])->withInput($req->only(['email']));
    }

}
