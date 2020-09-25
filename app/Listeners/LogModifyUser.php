<?php

namespace App\Listeners;

use Illuminate\Http\Request;
use App\Models\LoginHistory;
use App\Events\ModifyUser;

class LogModifyUser
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
     * @param  ModifyUser  $event
     * @return void
     */
    public function handle(ModifyUser $event)
    {
        $user = $event->user;
        Logs('authlog')->info('ユーザ情報変更', ['user:' . $user->id]);
        $login_his_db = new LoginHistory();
        $login_his_db->record($user->id, $this->request, "ユーザ情報変更");
    }
}
