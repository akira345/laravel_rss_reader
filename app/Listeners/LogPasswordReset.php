<?php

namespace App\Listeners;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use App\Models\LoginHistory;
class LogPasswordReset
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
     * @param  PasswordReset  $event
     * @return void
     */
    public function handle(PasswordReset $event)
    {
        $user = $event->user;
        Logs('authlog')->info('パスワードリセット',['user:' . $user->id]);
        $login_his_db = new LoginHistory();
        $login_his_db->record($user->id, $this->request,"パスワードリセット");
    }
}
