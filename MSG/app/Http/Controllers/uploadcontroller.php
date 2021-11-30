<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;


class uploadcontroller extends Controller
{
      public function UplodFiles(Request $request) {

      //  if(!is_null($request->file('file')) {
          $path = $request->file('file')->store("public/".Auth::id());
          $name = $request->file('file')->getClientOriginalName();
          $url = Storage::url($path);

          //return $request->attach;

          $Attachment = new Attachment;
          $Attachment->from_id = Auth::id();
          $Attachment->filename = $name;
          $Attachment->url = $url;
          $Attachment->date = NOW();

          $Attachment->save();

          return $Attachment->id;
      //  }
      }
}
