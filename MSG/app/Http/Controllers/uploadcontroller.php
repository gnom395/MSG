<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class uploadcontroller extends Controller
{
      public function UplodFiles(Request $request) {
        $path = $request->file('file')->store('avatars');

        return $path;
      }
}
