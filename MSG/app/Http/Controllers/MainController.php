<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function EnterOk(Request $request) {



// Получить текущего аутентифицированного пользователя ...

      Auth::loginUsingId(1, $remember = true);
      $user = Auth::check();
      echo $user;
    }
    public function EnterOk2(Request $request) {
      return '1234';
    }
}
