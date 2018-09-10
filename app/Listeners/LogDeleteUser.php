<?php

namespace App\Listeners;

use Illuminate\Http\Request;
use App\Models\LoginHistory;
use App\Events\DeleteUser;
class LogDeleteUser
{
    /**
     * Create the event listener.
     *
     * @param Request request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  DeleteUser  $event
     * @return void
     */
    public function handle(DeleteUser $event)
    {
        $user = $event->user;
        Logs('authlog')->info('ユーザ削除',['user:' . $user->id]);
        $login_his_db = new LoginHistory();
        $login_his_db->record($user->id, $this->request,"ユーザ削除");
    }
}
