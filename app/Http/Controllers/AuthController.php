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
        $attributes = ['email' => trans('auth.inputs.email'),'password' => trans('auth.inputs.password')];
        $validator = Validator::make($req->only('email','password'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        # check from user
        if(auth()->attempt(['email'=>$req->email,'password'=>$req->password])){
            $user = auth()->user();
            $req->session()->regenerate();
            if($user->email_verified_at==null){
                return redirect()->route('auth.verify');
            }
            if($user->hasRole(['super admin','admin'])){
                return redirect()->route('dashboard.home');
            }else{
                return redirect()->route('main');
            }
        }
        return back()->withErrors([ 'email' => trans('auth.web.login.failed')])->onlyInput('email');
    }

    function register(){ #done
        return view('auth.main');
    }
    function register_func(Request $req){ #done
        # validation inputs
        $rules = [
            'name' => ['required','string','max:250'],
            'email' => ['required','email','max:250'],
            'password' => ['required','min:8','max:16']
        ];
        $messages = [];
        $attributes = ['name' => trans('auth.inputs.name'),'email' => trans('auth.inputs.email'),'password' => trans('auth.inputs.password')];
        $validator = Validator::make($req->only('name','email','password'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        $user = User::where('email','=',$req->email)->first();
        if(!$user){
            $otp = rand(10000,99999);
            $newuser = new User;
            $newuser->name = $req->name;
            $newuser->email = $req->email;
            $newuser->password = bcrypt($req->password);
            $newuser->otp_number = Crypt::encryptString($otp);
            $newuser->otp_expiry = date('Y-m-d h:i:s',strtotime("+2 minutes"));
            $newuser->save();
            if($newuser){
                $this->SendMailTrait('','',$otp,$req->email,'verify');
                $credentials = $req->only('email', 'password');
                Auth::attempt($credentials);
                $req->session()->regenerate();
                return redirect()->route('auth.verify') ->withSuccess(trans('auth.create-success'));
            }  
        }elseif($user){
            return back()->withErrors([trans('auth.web.register.found')]);
        }
        return back()->withErrors([trans('auth.web.register.failed')]);
    }

    function logout(){ #done
        Auth::logout();
        return redirect('auth/login');
    }

    
    #############################
    #### api
    #############################
    function api_login(Request $req){ # done
        $rules = [
            'email' => ['required','email'],
            'password' => ['required','max:21',Password::min(8)],
        ];
        $messages = [];
        $attributes = ['email' => trans('inputs.login.email'),'password' => trans('inputs.login.password')];
        $validator = Validator::make($req->only('email','password'), $rules,[], $attributes);
        if($validator->fails()) {
            $messages = json_decode(json_encode($validator->messages()), true);
            $errors =[];
            if(isset($messages['email'])){ foreach($messages['email'] as $error){ $errors[]=$error; } }
            if(isset($messages['password'])){ foreach($messages['password'] as $error){ $errors[]=$error; } }
            return $this->returnErrors($errors,false);
        }else{
            if($token = Auth()->guard('api')->attempt(['email'=>$req->email,'password'=>$req->password])){
                return $this->returnData(['token'=>$token],trans('auth.api.login.success'));
            }
        } 
        return $this->returnErrors([trans('auth.api.login.failed')],false);
    }

    function api_logout(Request $req){ # done
        $rules = [
            'token' => ['required','string']
        ];
        $messages = [];
        $attributes = ['token' => trans('inputs.login.email')];
        $validator = Validator::make($req->only('token'), $rules,[], $attributes);
        if($validator->fails()) {
            $messages = json_decode(json_encode($validator->messages()), true);
            $errors =[];
            if(isset($messages['token'])){ foreach($messages['token'] as $error){ $errors[]=$error; } }
            return $this->returnErrors($errors,false);
        }else{
            if(Auth()->guard('api')->user()){
                Auth()->guard('api')->logout();
                return $this->returnSuccessMessage(trans('auth.api.logout.success'));
            }
        }
        return $this->returnErrors([trans('auth.api.logout.failed')],false);
    }

    function verify(){
        $user = auth()->user();
        if($user->otp_number==null && $user->email_verified_at==null){
            $otp = rand(10000,99999);
            $this->SendMailTrait('subject','title',$otp,$user->email,'verify');
            $user->otp_number =  Crypt::encryptString($otp);
            $user->otp_expiry = date('Y-m-d h:i:s',strtotime("+2 minutes"));
            $user->save();
            return view('auth.verify');
        }elseif($user->otp_number!=null && $user->otp_expiry<date('Y-m-d h:i:s',strtotime("now")) && $user->email_verified_at==null){
            $otp = rand(10000,99999);
            $this->SendMailTrait('subject','title',$otp,$user->email,'verify');
            $user->otp_number =  Crypt::encryptString($otp);
            $user->otp_expiry = date('Y-m-d h:i:s',strtotime("+2 minutes"));
            $user->save();
            return view('auth.verify');
        }
        return redirect()->route('auth.login');
    }
    function verify_check(Request $req){
        # validation inputs
        $rules = [
            'number' => ['required']
        ];
        $messages = [];
        $attributes = ['number' => trans('auth.inputs.otp_number')];
        $validator = Validator::make($req->only('number'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }

        $user = auth()->user();
        if(Crypt::decryptString($user->otp_number)==$req->number && $user->otp_expiry<date('Y-m-d h:i:s',strtotime("now"))){
            return back()->withErrors([trans('auth.otp.expiry')]);
        }elseif(Crypt::decryptString($user->otp_number)!=$req->number){
            return back()->withErrors([trans('auth.otp.wrong')]);
        }elseif($user->otp_number!=null && Crypt::decryptString($user->otp_number)==$req->number && $user->otp_expiry!=null && $user->otp_expiry>date('Y-m-d h:i:s',strtotime("now"))){
            $user->otp_number = null;
            $user->otp_expiry = null;
            $user->email_verified_at = date('Y-m-d h:i:s',strtotime("now"));
            $user->save();
            if($user->hasRole(['super admin','admin'])){
                return redirect()->route('dashboard.home');
            }else{
                return redirect()->route('main');
            }
        }
        return back()->withErrors([trans('auth.otp.failed')]);
    }


    function repassword_req(Request $req){
        $rules = [
            'email' => ['required','email','max:250']
        ];
        $messages = [];
        $attributes = ['email' => trans('auth.inputs.email')];
        $validator = Validator::make($req->only('email'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }
        $user = User::where('email', $req->email)->first(['email','otp_number','otp_expiry']);
        if($user){
            if($user->otp_number==null){
                $otp = rand(10000,99999);
                $upuser = User::where('email', $req->email)->update(['otp_number'=>Crypt::encryptString($otp),'otp_expiry'=>date('Y-m-d h:i:s',strtotime("+2 minutes"))]);
                $this->SendMailTrait('','',$otp,$user->email,'verify');
            }elseif($user->otp_number!=null && $user->otp_expiry<date('Y-m-d h:i:s',strtotime("now"))){
                $otp = rand(10000,99999);
                $upuser = User::where('email', $req->email)->update(['otp_number'=>Crypt::encryptString($otp),'otp_expiry'=>date('Y-m-d h:i:s',strtotime("+2 minutes"))]);
                $this->SendMailTrait('','',$otp,$user->email,'verify');
            }
            return redirect()->route('auth.repassword');
        }
        return redirect()->route('auth.login');
    }
    function repassword(){
        return view('auth.repassword');
    }
    function repassword_func(Request $req){
        $rules = [
            'number' => ['required','numeric'],
            'email' => ['required','email','max:250'],
            'password' => ['required','max:21',Password::min(8)->mixedCase()->letters()->numbers()],
            'repassword' => ['required','same:password']
        ];
        $messages = [];
        $attributes = ['number' => trans('auth.inputs.otp_number'),'email' => trans('auth.inputs.email'),'password' => trans('auth.inputs.password'),'repassword' => trans('auth.inputs.repassword')];
        $validator = Validator::make($req->only('number','email','password','repassword'), $rules,[], $attributes);
        if($validator->fails()) {
            $errors = json_decode(json_encode($validator->messages()), true);
            return back()->withErrors($errors);
        }
        $user = User::where('email', $req->email)->first();
        if($user){
            if($user->otp_number!=null){
                if(Crypt::decryptString($user->otp_number)!=$req->number){
                    return back()->withErrors([trans('auth.otp.wrong')]);
                }elseif($user->otp_expiry!=null && $user->otp_expiry<date('Y-m-d h:i:s',strtotime("now"))){
                    return back()->withErrors([trans('auth.otp.expiry')]);
                }elseif($user->otp_number!=null && Crypt::decryptString($user->otp_number)==$req->number && $user->otp_expiry!=null && $user->otp_expiry>date('Y-m-d h:i:s',strtotime("now"))){
                    $upuser = User::where('email', $req->email)->update(['password'=>bcrypt($req->password),'otp_number'=>null,'otp_expiry'=>null]);
                    return redirect()->route('auth.login');
                }
            }else{
                return redirect()->route('auth.login');
            }            
        }
        return back()->withErrors([trans('auth.otp.failed')]);
    }







    function verify_verify_send(Request $req){
        $otp = rand(10000,99999);
        $this->SendMailTrait('subject','title',$otp,'al.gzwani01@gmail.com','verify');
        $user = auth()->user();
        $user->otp_number = Crypt::encryptString($otp);
        $user->email_verified_at = null;
        $user->save();
        return 'sent success';
    }

    function verify_verify_remove(Request $req){
        $user = auth()->user();
        $user->otp_number = null;
        $user->email_verified_at = null;
        $user->save();
        return 'remove success';
    }


}
