<?php

use Illuminate\Support\Facades\Broadcast;
//use App\Models\Order;


/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

//Broadcast::channel('orders.{orderId}', function ($user, $orderId) {
//    return $user->id === Order::findOrNew($orderId)->user_id;
//});
//Broadcast::channel('chat', function ($message) {
//    return true;
//});
//Broadcast::channel('chat2', function ($user,$message) {
//    return true;
//});
//Broadcast::channel('chat', OrderChannel::class);

Broadcast::channel('room.{room_id}', function ($user, $room_id) {
    //return $room_id === $user->room_id;
    return true;
});
