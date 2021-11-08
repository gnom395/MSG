<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Http\Request;

class MainController extends Controller
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function EnterToRouter(Request $request) {
        return view('index');
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function EnterToChat(Request $request) {

      /// проверяем пользователя зарегестрирован он или нет
      if(is_null(Auth::user())) {
        //return view('edituser', ['user' => Auth::user() ]);

        $ip_parts = explode (".", $request->ip());
        $ip_node = $ip_parts[0].".".$ip_parts[1];

        if($ip_node != '192.168') {
          /// выкидваем если не из нашей сети
          return 'ip error '. $request->ip();
          exit;
        }
          /// если за нашей сети

          $User = User::where('ip', $request->ip())->first();

            if(is_null($User)) {
              /// если нет в базе отправляем на регистрацию
              return redirect()->route('changename');

            } else {
              // есть в базе

              Auth::loginUsingId($User->id, $remember = true);

              return view('index', ['user' => Auth::user() ]);
            }


      } else {
        /// успешно заходим
        return view('index', ['user' => Auth::user() ]);
      }

    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function EditName(Request $request) {

        $Usermy = User::where('ip', $request->ip())->first();

        if(is_null($Usermy)) {
          $user = new User;
          $user->name = $request->name;
          $user->ip = $request->ip();
          $user->save();

          return '1';

        } else {
          User::where('ip', $request->ip())
            ->update(['name' => $request->name]);
            return '1';
        }



        Auth::loginUsingId($user->id, $remember = true);

        //return redirect()->route('enter');
        //return view('index', ['user' => Auth::user() ]);
      }



    public function ChangeName(Request $request) {

      return view('changename');

      //Auth::loginUsingId(2, $remember = true);
      //$user = Auth::check();
      //echo $user;
    }
}
