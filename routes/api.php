<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;



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
// public routes


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

// protected routes

Route::group (['middleware' => ['auth:sanctum']], function() {

    //user
    
    Route::get('/user',[AuthController::class,'user']);
    Route::put('/user',[AuthController::class,'update']);
    Route::post('/logout',[AuthController::class,'logout']);


    // Post 
    Route::get('/posts',[PostController::class, 'index']); // all posts
    Route::post('/posts',[PostController::class, 'store']); // create post
    Route::get('/posts/{id}',[PostController::class, 'show']); //get single post
    Route::put('/posts/{id}',[PostController::class, 'update']); // update post
    Route::delete('/posts/{id}',[PostController::class, 'destroy']); // delete post


     // Comment
     Route::get('/posts/{id}/comments',[CommentController::class, 'index']); // all comments of apost
     Route::post('/posts/{id}/comments',[CommentController::class, 'store']); // create comment on a post
     Route::put('/comments/{id}',[CommentController::class, 'update']); // update comment
     Route::delete('/comments/{id}',[CommentController::class, 'destroy']); // delete delete a comment
 

      // Post 
    Route::post('/posts/{id}/likes',[LikeController::class, 'likerOrUnlike']); // like or dislike back a post
   
 

});