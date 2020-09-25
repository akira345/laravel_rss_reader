<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use App\Models\LoginHistory;

class LogFailedLogin
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
     * @param  Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
        $user = $event->user;
        if (is_object($user)) {
            Logs('authlog')->info('ログイン失敗', ['user:' . $user->id]);
            $login_his_db = new LoginHistory();
            $login_his_db->record($user->id, $this->request, "ログイン失敗");
        } else {
            Logs('authlog')->info('ログイン失敗');
        }
    }
}
