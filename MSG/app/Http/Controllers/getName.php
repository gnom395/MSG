<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class getName extends Controller
{
  public function getName(Request $request) {

    return '111';

    $User = User::where('id',Auth::id())->first();

    // если не прошел каунт то пусто
    //  if(!count($User)) {
    //    return 0;
    //  } else {
        return $User;
    //  }

  }
}
