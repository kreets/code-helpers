<?php

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
Route::get('/tags', 'App\Http\Controllers\SnippetController@tags')->name('tags');
Route::get('/snippets', 'App\Http\Controllers\SnippetController@snippets')->name('tags');

Route::middleware('vue.auth')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('vue.auth')->post('/create/snippet', 'App\Http\Controllers\SnippetController@saveSnippet');