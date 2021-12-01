<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MessagesNew;
use Illuminate\Support\Facades\Auth;

class getSmallWin extends Controller
{

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getInfo(Request $request) {

    $MessagesNew = MessagesNew::where('toUser',Auth::id())->get();

    // если не прошел каунт то пусто
      if(!count($MessagesNew)) {
        return 0;
      } else {
        return count($MessagesNew);
      }


  }
}
