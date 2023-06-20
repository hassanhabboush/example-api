<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' =>'guest:user-api'],function(){

    Route::get('users',[UserController::class,'getUser']);
    Route::post('users/store',[UserController::class,'store']);
    Route::post('users/edit/{id}',[UserController::class,'update']);
    Route::post('users/destroy',[UserController::class,'destroy']);
    Route::post('users/login',[UserController::class,'login']);
});

Route::group(['middleware'=>'auth:user-api'],function(){
    Route::post('logout',[UserController::class,'logout']);
    
});