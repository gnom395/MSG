<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\MessagesNew;
use App\Models\Group;
use App\Models\User;
use App\Models\UsersInGroup;
use Illuminate\Support\Facades\Auth;
use App\Events\PresenceChat;

class PostController extends Controller
{

  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function postMessage(Request $request) {

    if(is_null($request->from)) {
      //$from = $request->session()->get('id');
      $from = Auth::id();
    } else {
      $from = $request->from;
    }
  //  $newPrivarMes = array(
  //      //'id' => $message->id(),
  //      'to' => $request->to,
  //      'from' => $request->from,
//        'room_id' => $request->room_id,
    //    'message' => $request->message,
    //    'ug' => $request->ug,
    //    'attach' => $request->attach,
  //      'datesend' => $request->datesend,
    //    'read' => $request->read,
    //  );
    //  PresenceChat::dispatch($request->all());


    /// шлем на вебсокет
    $username = User::where('id',$request->from)->first();
    $newMes = array(
        'room_id' => 999,
        'status' => 1,
        'to' => $request->to,
        'from' => $request->from,
        'name' => $username->name,
        'newmess' => 1
      );
    PresenceChat::dispatch($newMes);

    $to = $request->to;
    $mess = $request->message;

    if(is_null($request->attach)) {
      $attachFile = 0;
    } else {
      $attachFile = $request->attach;
    }

    //$grp = 0;


    /// Пишем в таблицу
    //// если null grp то пишем от пользователя
       if($request->ug == 'user') {

         $message = new Message;
         $message->fromUser = $from;
         $message->toUser = $to;
         $message->toGroup = 0;
         $message->id_master = 0;
         $message->message = $mess;
         $message->attachFile = $attachFile;
         $message->dateIn = NOW();
          if($request->online == 1) {
            $message->readMes = 1;
          } else {
            $message->readMes = 0;
          }
         $message->deleteMesTo = 0;
         $message->deleteMesFrom = 0;

         $message->save();

         $messageNew = new MessagesNew;
         $messageNew->fromUser = $from;
         $messageNew->toUser = $to;
         $messageNew->toGroup = 0;

         $messageNew->save();

        $newPrivarMes = array(
             'id' => $message->id,
             'to' => $request->to,
             'from' => $request->from,
             'room_id' => $request->room_id,
             'message' => $request->message,
             'ug' => $request->ug,
             'attach' => $request->attach,
             'datesend' => $request->datesend,
             'read' => $request->read
           );
          PresenceChat::dispatch($newPrivarMes);

         //return MainController::answerJson('response','1','111');
         $successAr = array(
             'success' => '1',
             'post_id' => $message->id,
             'to' => $message->toUser,
             'from' => $message->fromUser,
             'attach' => $attachFile
           );
            return json_encode($successAr);



    /// это отправляем группе
       }  else {
         /// проверяем есть ли группа
         $group = Group::where('id',$request->to)->first();
         if(is_null($group)) {
           //return MainController::answerJson('error','4','нет группы');
           return 'error 4';
           exit;
         }

         $message = new Message;
         $message->fromUser = $from;
         $message->toUser = $to;
         $message->toGroup = 1;
         $message->id_master = 0;
         $message->message = $mess;
         $message->attachFile = $attachFile;
         $message->dateIn = NOW();
         $message->readMes = 0;
         $message->deleteMesTo = 0;
         $message->deleteMesFrom = 0;

         $message->save();

         $messageNew = new MessagesNew;
         $messageNew->fromUser = $from;
         $messageNew->toUser = $to;
         $messageNew->toGroup = 1;

         $messageNew->save();
        /// добавляем сообщения



         $messageGrp = UsersInGroup::where('id_group',$to)->get();

      //  dd($request->all());

    // если не прошел каунт то пусто
         if(!count($messageGrp)) {

           //$postCon = new PostController();
           //return $postCon->answerJson('error','5','пустая группа');
           return 'error 5';
             exit;
           }



           foreach ($messageGrp as $row) {

             $messageFor = new Message;
             $messageFor->fromUser = $from;
             $messageFor->toUser = $row->id_user;
              $messageFor->toGroup = 0;
              $messageFor->id_master = $message->id;
              $messageFor->message = $mess;
              $messageFor->attachFile = $attachFile;
              $messageFor->dateIn = NOW();
              $messageFor->readMes = 0;
              $messageFor->deleteMesTo = 0;
              $messageFor->deleteMesFrom = 0;

              $messageFor->save();

              $messageNewFor = new MessagesNew;
              $messageNewFor->fromUser = $from;
              $messageNewFor->toUser = $row->id_user;
              $messageNewFor->toGroup = 0;

              $messageNewFor->save();

              $successAr = array(
                  'success' => '1',
                  'post_id' => $message->id,
                  'to' => $message->toUser,
                  'from' => $message->fromUser,
                  'attach' => $attachFile
                );
              return json_encode($successAr);

            }

              //return MainController::answerJson('response','4','add');
          }



  }
}
