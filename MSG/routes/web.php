<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\getUsersController;
use App\Http\Controllers\getMessageController;
use App\Http\Controllers\PostController;
use App\Events\PresenceChat;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});


//Route::post('/messages', [MainController::class, 'EnterOk2']);

Route::get('/getusers', [getUsersController::class, 'getUsers'])->name('getusers');
Route::get('/getmessages', [getMessageController ::class, 'getMessage'])->name('getmessages');
Route::post('/postmessage', [PostController::class, 'postMessage'])->name('postmessage');
//Route::post('/postmessage', [PresenceChat::class, 'postMessage'])->name('postmessage');


Route::get('/', [MainController::class, 'EnterToChat'])->name('enter');


Route::post('/messages', function(Request $request) {
    //PrivateChat::dispatch($request->input('body'));
    PresenceChat::dispatch($request->all());
    //return $request->input('body');
});
//Route::post('/messages', 'index2');


//Route::view('/', 'index')->middleware('auth');

Route::post('/editname', [MainController::class, 'EditName'])->name('editname');
Route::get('/changename', [MainController::class, 'ChangeName'])->name('changename');

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/{any}', [MainController::class, 'EnterToRouter'])->where('any', '.*');
