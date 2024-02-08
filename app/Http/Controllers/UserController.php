<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    function login(){
        return "login page";
    }
    function getSlug(Request $request){
        $name =$request->title;
        if($name){
            $slug =  Str::slug($name);
            return response()->json([
                'status'=> true,
                'slug' => $slug
            ]);
        }
        return response()->json([
            'status'=> false
        ]);
    }

}
