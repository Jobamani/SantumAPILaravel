<?php
use APP\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('signup',[AuthController::class,'signup']);
Route::post('login',[AuthController::class,'login']);

//logout will happen if user login first, then for authentication we use middlewarewith sanctum method
Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');