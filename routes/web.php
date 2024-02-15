<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\AdminDashboard;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TempImageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get("/login",[UserController::class,"login"])->name("login");
Route::post("/get-slug",[UserController::class,"getSlug"])->name("getSlug");
Route::post("/temp-images",[TempImageController::class,"save"])->name("temp-images.create");


Route::group(["prefix"=>"admin"],function(){
    Route::group(["middleware"=>"admin.auth"],function(){
        Route::get("/dashboard",[AdminDashboard::class,"index"])->name("admin.dashboard");
        Route::get("/categories",[CategoryController::class,"index"])->name("admin.categories");
        Route::get("/category/add",[CategoryController::class,"add"])->name("admin.category.add");
        Route::post("/category/create",[CategoryController::class,"create"])->name("admin.category.create");
        Route::get("/category/edit/{id}",[CategoryController::class,"edit"])->name("admin.category.edit");
        Route::post("/category/update/{id}",[CategoryController::class,"update"])->name("admin.category.update");
        Route::get("/category/delete/{id}",[CategoryController::class,"delete"])->name("admin.category.delete");


        Route::get("/sub-categories",[SubCategoryController::class,"index"])->name("admin.sub-categories");
        Route::get("/sub-category/add",[SubCategoryController::class,"add"])->name("admin.sub-category.add");
        Route::post("/sub-category/create",[SubCategoryController::class,"create"])->name("admin.sub-category.create");


        Route::get("/logout",[adminController::class,"login"])->name("admin.logout");
    });
    Route::group(["middleware"=>"admin.guest"],function(){
        Route::get("/login",[adminController::class,"login"])->name("admin.login");
        Route::post("/authenticate",[adminController::class,"authenticate"])->name("admin.authenticate");
    });
});
