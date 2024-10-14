<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Socialite;

class Logincontroller extends Controller
{
    public function login_process(Request $request)
    {
        // echoPrint($request->all());
        $result=DB::table('customers')
            ->where(['email'=>$request->str_login_email])
            ->get();

        $login_type = $request->input('login_type');
        if($login_type == 'google'){
            // echo $url =  Socialite::driver('google')->redirect(); //redirect
            $redirectUrl = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

            // return $redirectUrl;
            return response()->json(['status'=>'success','url' => $redirectUrl]);


        }else{//default
            if(isset($result[0])){
                $db_pwd = $result[0]->password;
                $status=$result[0]->status;
                $is_verify=$result[0]->is_verify;

                if($is_verify==0){
                    return response()->json(['status'=>"error",'msg'=>'Please verify your email id']);
                }
                if($status==0){
                    return response()->json(['status'=>"error",'msg'=>'Your account has been deactivated']);
                }
                if(Hash::check($request->post('str_login_password'),$db_pwd)){
                    if($request->rememberme === null){
                        setcookie('login_email',$request->str_login_email,100);
                        setcookie('login_pwd',$request->str_login_password,100);
                    }else{
                       setcookie('login_email',$request->str_login_email,time()+60*60*24*100);
                       setcookie('login_pwd',$request->str_login_password,time()+60*60*24*100);
                    }

                    $request->session()->put('FRONT_USER_LOGIN',true);
                    $request->session()->put('FRONT_USER_ID',$result[0]->id);
                    $request->session()->put('FRONT_USER_NAME',$result[0]->name);
                    $status="success";
                    $msg="";

                    $getUserTempId=getUserTempId();
                    DB::table('cart')
                        ->where(['user_id'=>$getUserTempId,'user_type'=>'Not-Reg'])
                        ->update(['user_id'=>$result[0]->id,'user_type'=>'Reg']);
                }else{
                    $status="error";
                    $msg="Please enter valid password";
                }
            }else{
                $status="error";
                $msg="Please enter valid email id";
            }
        }
       return response()->json(['status'=>$status,'msg'=>$msg]);
    }
    public function handleGoogleCallback(Request $request)
        {
            // try {
                $googleUser = Socialite::driver('google')->stateless()->user();
                // echoPrint($googleUser);
                // echo $googleUser->getEmail();
                // die('stop');
                 // Access the individual properties, like email or name
                $googleUserId = $googleUser->getId(); // Get the user's Google ID
                $googleUserName = $googleUser->getName(); // Get the user's name
                $googleUserEmail = $googleUser->getEmail(); // Get the user's email
                $googleUserAvatar = $googleUser->getAvatar(); // Get the user's profile picture
                // echo $googleUser->getEmail();
                $result=DB::table('customers')
                    ->where(['email' => $googleUser->getEmail()])
                    ->get();

                if(isset($result[0])){
                    $db_pwd = $result[0]->password;
                    $status=$result[0]->status;
                    $is_verify=$result[0]->is_verify;
                    $email = $googleUser->getEmail();

                    if($is_verify==0){
                        return response()->json(['status'=>"error",'msg'=>'Please verify your email id']);
                    }
                    if($status==0){
                        return response()->json(['status'=>"error",'msg'=>'Your account has been deactivated']);
                    }

                        Session::put('FRONT_USER_LOGIN',true);
                        Session::put('FRONT_USER_ID',$result[0]->id);
                        Session::put('FRONT_USER_NAME',$result[0]->name);
                        $status="success";
                        $msg="";

                        $getUserTempId=getUserTempId();
                        DB::table('cart')
                            ->where(['user_id'=>$getUserTempId,'user_type'=>'Not-Reg'])
                            ->update(['user_id'=>$result[0]->id,'user_type'=>'Reg']);
                }else{
                    $status="error";
                    $msg="Please enter valid email id";
                }
                // return response()->json(['status'=>$status,'msg'=>$msg]);
                // return view('/');
                return redirect('/');



                // return redirect()->intended('/');
            // } catch (\Exception $e) {
            //     return redirect('/login')->withErrors('Failed to login with Google, please try again.');
            // }
        }

}
