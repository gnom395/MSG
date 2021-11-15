<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\getUsersController;
use App\Http\Controllers\getMessageController;
use App\Http\Controllers\PostController;
use App\Events\PresenceChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
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
//$ip = "192.168.88.16";


Route::get('/register', function(Request $request) {
  if(Auth::check()) {
    return redirect(route('home'));
  }
  return view('auth/register');
})->name('register');

Route::get('/login', function(Request $request) {

  $ip = $request->ip();
  $ip_parts = explode (".", $ip);
  $ip_node = $ip_parts[0].".".$ip_parts[1];

    if($ip_node == '192.168') {
      $User = User::where('ip', $ip)->first();
      if(!is_null($User)) {
        // есть в базе
        Auth::loginUsingId($User->id, $remember = true);
        return view('index', ['user' => Auth::user() ]);
      }
    }

  //if(Auth::check()) {
  ///  return redirect(route('home'));
  ///}
  return view('auth/login');
})->name('login');
 Route::post('login', [LoginController::class, 'login']);
 Route::post('logout', [LoginController::class, 'logout'])->name('logout');



Route::get('/getusers', [getUsersController::class, 'getUsers'])->name('getusers')->middleware('auth');
Route::get('/getmessages', [getMessageController ::class, 'getMessage'])->name('getmessages')->middleware('auth');
Route::post('/postmessage', [PostController::class, 'postMessage'])->name('postmessage')->middleware('auth');

Route::post('/delmes', [MainController::class, 'DelMes'])->name('delmes')->middleware('auth');


Route::post('/messages', function(Request $request) {
    //PrivateChat::dispatch($request->input('body'));
    PresenceChat::dispatch($request->all());
    //return $request->input('body');
})->middleware('auth');
//Route::post('/messages', 'index2');
//Route::view('/', 'index')->middleware('auth');

Route::post('/editname', [MainController::class, 'EditName'])->name('editname')->middleware('auth');
Route::get('/changename', [MainController::class, 'ChangeName'])->name('changename')->middleware('auth');

//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

Route::get('/{any}', [MainController::class, 'EnterToChat'])->where('any', '.*')->middleware('auth');
// The user is logged in...
