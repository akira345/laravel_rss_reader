<?php

namespace App\Listeners;

use App\Models\LoginHistory;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;

class LogRegisteredUser
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
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;
        Logs('authlog')->info('ユーザ登録完了',['user:' . $user->id]);
        $login_his_db = new LoginHistory();
        $login_his_db->record($user->id, $this->request,"ユーザ登録完了");
    }
}
