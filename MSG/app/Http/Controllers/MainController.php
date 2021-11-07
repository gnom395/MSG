<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function EnterOk(Request $request) {

    //  $ip =  $request->ip();
      //$ip = "192.168.88.16";

    //  $User = User::where('ip', $ip)->first();

      //if(is_null($User)) {
      //  return 'error ip '.$ip;
      //} else {
    //    $response['success'] = $User;
        //  $response['success'] = array(
        //    'success' => '1',
        //    'id' => ''
        //  );
        //return (response(json_encode($response, JSON_UNESCAPED_UNICODE), 200)
        //              ->header('Content-Type', 'application/json; charset=utf-8'));

        ////session(['id' => $User->id]);
      //  session(['name' => $User->name]);
      //  session(['about' => $User->about]);

        return view('index', ['user' => Auth::user() ]);


    }
    public function EnterOk2(Request $request) {
      // Получить текущего аутентифицированного пользователя ...

      Auth::loginUsingId(2, $remember = true);
      $user = Auth::check();
      echo $user;
    }
}
