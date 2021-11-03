<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
//use App\Events\Message;
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

Route::post('/messages', function(Request $request) {
    App\Events\Message::dispatch($request->input('body'));
});

Route::view('/', 'index');

//Route::get('/', [MainController::class, 'EnterOk']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
