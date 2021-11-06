<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function EnterOk(Request $request) {
      return view('index');
    }
    public function EnterOk2(Request $request) {
      return '1234';
    }
}
