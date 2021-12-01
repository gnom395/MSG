<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;

use Illuminate\Http\Request;

class MainController extends Controller
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //  public function EnterToRouter(Request $request) {
      //  EnterToChat(Request $request);
  //      return view('index');
  //  }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function EnterToChat(Request $request) {

      //$ip = "192.168.88.16";
      $ip = $request->ip();
      /// проверяем пользователя зарегестрирован он или нет
      if(is_null(Auth::user())) {
        //return view('edituser', ['user' => Auth::user() ]);

        $ip_parts = explode (".", $ip);
        $ip_node = $ip_parts[0].".".$ip_parts[1];

        if($ip_node != '192.168' and $ip_node != '172.25') {
          /// выкидваем если не из нашей сети
          return 'ip error '. $ip;
          exit;
        }
          /// если за нашей сети

          $User = User::where('ip', $ip)->first();

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
    public function smallwin(Request $request) {
      /// проверяем пользователя зарегестрирован он или нет
      if(is_null(Auth::user())) {
        return 'no reg';
      } else {
        return view('smallwin', ['user' => Auth::user() ]);
      }
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function EditName(Request $request) {

      //$ip = "192.168.88.16";
      $ip = $request->ip();

        $Usermy = User::where('ip', $ip)->first();

        if(is_null($Usermy)) {
          $user = new User;
          $user->name = $request->name;
          $user->ip = $ip;
          $user->save();

          return '1';

        } else {
          User::where('ip', $ip)
            ->update(['name' => $request->name]);
            return '1';
        }



        Auth::loginUsingId($user->id, $remember = true);


        //return redirect()->route('enter');
        //return view('index', ['user' => Auth::user() ]);
      }

      public function DelMes(Request $request) {

        //$res=Message::where('id',$id)->delete();

        if(!is_null($request->idmes)) {
           if($request->delall == 0) {
             Message::where('id', $request->idmes)
                     ->where('fromUser', Auth::id())
                     ->update(['deleteMesFrom' => 1]);
             Message::where('id', $request->idmes)
                    ->where('toUser', Auth::id())
                    ->update(['deleteMesTo' => 1]);
                    return 1;
           } else {
             /// для админа
             Message::where('id', $request->idmes)
                    ->update(['deleteMesTo' => 1,'deleteMesFrom' => 1]);
             return 1;
           }
        }
      }


    public function ChangeName(Request $request) {

      return view('changename');

      //Auth::loginUsingId(2, $remember = true);
      //$user = Auth::check();
      //echo $user;
    }
}
