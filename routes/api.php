<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ColorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\Api\ProductController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);


Route::middleware('auth:sanctum')->post('logout',[AuthController::class,'logout']);


Route::middleware(['auth:sanctum','isAPIAdmin'])->group(function(){
    Route::get('/checkingAuthenticated', function (){
        return response()->json(['message'=>'You are in','status'=>200],200);
    });
    // category
    Route::post('store-category',[CategoryController::class,'store']);
    Route::get('view-category',[CategoryController::class,'index']);
    Route::get('edit-category/{id}',[CategoryController::class,'edit']);
    Route::put('update-category/{id}',[CategoryController::class,'update']);
    Route::get('delete-category/{id}',[CategoryController::class,'destroy']);
    Route::get('all-category',[CategoryController::class,'all_category']);
    //product
    Route::post('store-product',[ProductController::class,'store']);
    Route::get('view-product',[ProductController::class,'index']);
    Route::get('delete-product/{id}',[ProductController::class,'destroy']);
    Route::get('edit-product/{id}',[ProductController::class,'edit']);
    Route::post('update-product/{id}',[ProductController::class,'update']);
    //brand
    Route::post('store-brand',[BrandController::class,'store']);
    Route::get('view-brand',[BrandController::class,'index']);
    Route::get('edit-brand/{id}',[BrandController::class,'edit']);
    Route::post('update-brand/{id}',[BrandController::class,'update']);
    Route::get('delete-brand/{id}',[BrandController::class,'destroy']);
    //Color
    Route::get('view-color',[ColorController::class,'index']);
    Route::post('store-color',[ColorController::class,'store']);
    Route::get('edit-color/{id}',[ColorController::class,'edit']);
    Route::post('update-color/{id}',[ColorController::class,'update']);
    Route::get('delete-color/{id}',[ColorController::class,'destroy']);


});
Route::get('view-product',[ProductController::class,'index']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});