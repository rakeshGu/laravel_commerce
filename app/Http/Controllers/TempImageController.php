<?php

namespace App\Http\Controllers;

use App\Models\TempImage;
use Illuminate\Http\Request;

class TempImageController extends Controller
{
    function save(Request $request){
        $image =$request->image;
        if($image){
            $ext = $image->getClientOriginalExtension();
            $newImageName = time().'.'.$ext;
            $tempImage = new TempImage();
            $tempImage->image = $newImageName;
            $tempImage->save();
            $image->move(public_path()."/temp", $newImageName);
            return response()->json([
                'status'=> true,
                'image_id' => $tempImage->id,
                'message' => 'Image uploaded successfully'
            ]);
        }
        return response()->json([
            'status'=> false,
            'message' => 'Some error occurs'
        ]);
    }
}
