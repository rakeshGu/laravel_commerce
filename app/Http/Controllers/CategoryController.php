<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use File;
class CategoryController extends Controller
{
    function index(Request $request){
        $categories = Category::latest();
        if ($request->has('search')) {
            $search = $request->get('search');
            $categories->where('title', 'like', '%' . $search . '%');
        }
        $categories = $categories->paginate(10);
        return view('admin.categories', compact('categories'));
    }
    function add(Request $request){
        return view('admin.create-category');
    }
    function create(Request $request){
        $validator = Validator::make($request->all(),[
            "name" => "required",
            "slug" => "required|unique:categories,slug"
        ]);
        if($validator->passes()){
            $category = new Category();
            $category->title = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();
            if($request->image_id){
                $tempImage = TempImage::find($request->image_id);

                if($tempImage->image){
                    $expImage = explode('.', $tempImage->image);
                    $imageExt = last($expImage);
                    $newNameImage = $category->id.'.'.$imageExt;
                    $category->image = $newNameImage;
                    $category->save();
                    $sPath = public_path()."/temp/".$tempImage->image;
                    $dPath = public_path()."/upload/categories/".$newNameImage;
                    File::copy($sPath,$dPath);
                    File::delete($sPath);
                }
            }

            session()->flash("success", "Category added successfully");
            return response()->json([
                "status" => true,
                "message" => "category added successfully"
            ]);
        } else{
            return response()->json([
                "status" => false,
                "message" => $validator->errors()
            ]);
        }
    }

    function edit(Request $request){
        $category = Category::find($request->id);
        return view('admin.edit-category', compact('category'));
    }
    function update(Request $request){
        $validator = Validator::make($request->all(),[
            "name" => "required"
        ]);
        if($validator->passes()){
            $category = Category::find($request->id);
            $category->title = $request->name;
            $category->status = $request->status;
            $category->save();
            if($request->image_id){
                $tempImage = TempImage::find($request->image_id);

                if($tempImage->image){
                    $expImage = explode('.', $tempImage->image);
                    $imageExt = last($expImage);
                    $newNameImage = $request->id.'.'.$imageExt;
                    $category->image = $newNameImage;
                    $category->save();
                    $sPath = public_path()."/temp/".$tempImage->image;
                    $dPath = public_path()."/upload/categories/".$newNameImage;
                    File::copy($sPath,$dPath);
                    File::delete($sPath);
                }
            }

            session()->flash("success", "Category updated successfully");
            return response()->json([
                "status" => true,
                "message" => "category updated successfully"
            ]);
        } else{
            return response()->json([
                "status" => false,
                "message" => $validator->errors()
            ]);
        }
    }

    function delete(Request $request){
        $category = Category::find($request->id);
        if($category){
            $category->delete();
            return response()->json([
                "status" => true,
                "message" => "Category removed successfully"
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "category not found"
        ]);
    }
}
