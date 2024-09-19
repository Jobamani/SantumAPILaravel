<?php
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/test', function () {
    return "return value";
});
Route::post('signup',[AuthController::class,'signup']);
Route::post('login',[AuthController::class,'login']);


//logout will happen if user login first, then for authentication we use middlewarewith sanctum method

Route::middleware('auth::sanctum')->group(function(){
    Route::post('logout',[AuthController::class,'logout']);
    Route::apiResource('posts',PostController::class);
})


?>