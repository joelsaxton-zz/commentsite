<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/rootcomments', 'CommentController@getCommentRootNodes');
Route::get('/commentree/{id}', 'CommentController@getCommentTree');
Route::post('/rootcomment', 'CommentController@createRootComment');
Route::post('/response', 'CommentController@createCommentResponse');