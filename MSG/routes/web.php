<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\getUsersController;
use App\Http\Controllers\getMessageController;
use App\Http\Controllers\PostController;
use App\Events\PresenceChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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



Route::get('/getusers', [getUsersController::class, 'getUsers'])->name('getusers')->middleware('auth');
Route::get('/getmessages', [getMessageController ::class, 'getMessage'])->name('getmessages')->middleware('auth');
Route::post('/postmessage', [PostController::class, 'postMessage'])->name('postmessage')->middleware('auth');
//Route::post('/postmessage', [PresenceChat::class, 'postMessage'])->name('postmessage');

//->middleware('auth')

//Route::get('/', [MainController::class, 'EnterToChat'])->name('enter');

Route::post('/messages', function(Request $request) {
    //PrivateChat::dispatch($request->input('body'));
    PresenceChat::dispatch($request->all());
    //return $request->input('body');
})->middleware('auth');
//Route::post('/messages', 'index2');
//Route::view('/', 'index')->middleware('auth');

Route::post('/editname', [MainController::class, 'EditName'])->name('editname')->middleware('auth');
Route::get('/changename', [MainController::class, 'ChangeName'])->name('changename')->middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

Route::get('/{any}', [MainController::class, 'EnterToChat'])->where('any', '.*')->middleware('auth');
// The user is logged in...
