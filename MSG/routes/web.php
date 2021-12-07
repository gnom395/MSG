<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MainController;
use App\Http\Controllers\getUsersController;
use App\Http\Controllers\getMessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\uploadcontroller;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GetFileListController;
use App\Http\Controllers\getSmallWin;
use App\Http\Controllers\getName;

use App\Events\PresenceChat;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


use App\Models\User;
use App\Models\Group;
use App\Models\UsersInGroup;
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
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return view('auth/register');

})->name('register');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/login', function(Request $request) {

  $ip = $request->ip();
  $ip_parts = explode (".", $ip);
  $ip_node = $ip_parts[0].".".$ip_parts[1].".";
  $ip_node_full = $ip_parts[0].".".$ip_parts[1].".".$ip_parts[2].".";


  /// если из нашей сети то регестрируем авто вход
    if($ip_node == '192.168.') {
      // если ip есть в базе
      $User = User::where('ip', $ip)->first();
      if(!is_null($User)) {
        // есть в базе
        Auth::loginUsingId($User->id, $remember = true);
        return view('index', ['user' => Auth::user() ]);
      } else {
        /// если нет то регестрируем
        /// проверяем есть ли группа по ноде
        $Group = Group::where('node', $ip_node_full)->first();
                  //  dd($Group);
          if(is_null($Group)) {
            // если нет группы то выкидываем
            return redirect()->route('error');
            exit;
          }
          $User = new User;
          $User->name = '';
          $User->created_at = NOW();
          $User->updated_at = NOW();
          $User->ip = $ip;
          $User->dateOnline = NOW();
          $User->id_office = $Group->id;
          $User->role = 0;
          $User->save();

          /// добавили в группу все
          $Users_in_group = new UsersInGroup;
          $Users_in_group->id_group = 1;
          $Users_in_group->id_user = $User->id;
          $Users_in_group->save();

          /// добавили в группу подразделения
          $Users_in_group = new UsersInGroup;
          $Users_in_group->id_group = $Group->id;
          $Users_in_group->id_user = $User->id;
          $Users_in_group->save();

          Auth::loginUsingId($User->id, $remember = true);
          //return view('changename', ['user' => Auth::user() ]);
          return redirect()->route('changename');
      }
    }

  //if(Auth::check()) {
  ///  return redirect(route('home'));
  ///}
  return view('auth/login');
  //LoginController::login();

})->name('login');

Route::post('login', [LoginController::class, 'login']);

Route::view('error', 'error')->name('error');
Route::get('/getname', [getName::class, 'getName'])->name('getname');


Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/getsmallwin', [getSmallWin::class, 'getInfo'])->name('getsmallwin')->middleware('auth');

Route::post('/getnamefiles', [GetFileListController::class, 'GetFileList'])->name('getnamefiles')->middleware('auth');
Route::post('/upload', [uploadcontroller::class, 'UplodFiles'])->name('upload')->middleware('auth');

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
Route::get('/smallwin', [MainController::class, 'smallwin'])->name('smallwin')->middleware('auth');
//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

Route::get('/{any}', [MainController::class, 'EnterToChat'])->where('any', '.*')->middleware('auth');
// The user is logged in...
