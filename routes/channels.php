<?php

use App\Tables;

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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/*Broadcast::channel('table.{id}', function ($user, $tableId) {

    $owner_id = Tables::where('id',$tableId)->get()->first()->restaurant->user->id;

    return (int) $user->id === (int) $owner_id;
});*/
