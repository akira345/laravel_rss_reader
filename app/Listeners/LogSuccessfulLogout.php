<?php

namespace App\Listeners;

use App\Models\LoginHistory;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogout
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
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $user = $event->user;
        Logs('authlog')->info('ログアウト', ['user:' . $user->id]);
        $login_his_db = new LoginHistory();
        $login_his_db->record($user->id, $this->request, "ログアウト");
    }
}
