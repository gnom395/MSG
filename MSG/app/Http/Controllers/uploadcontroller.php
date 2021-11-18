<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attachment;

class uploadcontroller extends Controller
{
      public function UplodFiles(Request $request) {

      //  if(!is_null($request->file('file')) {
          $path = $request->file('file')->store(Auth::id());
          $name = $request->file('file')->getClientOriginalName();
          //return $request->attach;

          $Attachment = new Attachment;
          $Attachment->from_id = Auth::id();
          $Attachment->filename = $name;
          $Attachment->url = $path;
          $Attachment->date = NOW();

          $Attachment->save();

          return $Attachment->id;
      //  }
      }
}
