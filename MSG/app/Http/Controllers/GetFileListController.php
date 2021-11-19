<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Attachment;

class GetFileListController extends Controller
{
    public function GetFileList(Request $request) {

    //  $AttachFile = DB::select('select * from attachments where id in (?)', $request->idfiles);

     $AttachFile = Attachment::whereIn('id',explode(',', $request->idfiles))->get();

     return $AttachFile;
  }
}
