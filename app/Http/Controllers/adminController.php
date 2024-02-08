<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class adminController extends Controller
{
    function login(Request $request){
        return view("admin/login");
    }
    function authenticate(Request $request){
        $validate = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required"
        ]);
        if($validate->passes()){
            if (Auth::guard("admin")->attempt(['email'=>$request->email, 'password'=>$request->password], $request->get('remember'))) {
                if(Auth::guard("admin")->user()->role ==2){
                    return redirect()->route('admin.dashboard');
                } else{
                    Auth::guard("admin")->logout();
                    return redirect()->route("admin.login")->with("error","You are not authorized user")->withInput($request->only("email"));
                }
            } else{
                return redirect()->route("admin.login")->with("error","Email or Password wrong")->withInput($request->only("email"));
            }
        } else{
            return redirect()->route("admin.login")->withErrors($validate)->withInput($request->only("email"));
        }

        function logout(){
            Auth::guard("admin")->logout();
            return redirect()->route("admin.login");
        }
    }

}
