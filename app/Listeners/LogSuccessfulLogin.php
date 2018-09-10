<?php

namespace App\Listeners;

use App\Models\LoginHistory;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class LogSuccessfulLogin
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        Logs('authlog')->info('ログイン',['user:' . $user->id,'user_id:' . $user->name,'email:' . $user->email]);
        $login_his_db = new LoginHistory();
        $login_his_db->record($user->id, $this->request,"ログイン");

        $user = Auth::user();
        $user->last_login_at = Carbon::now();
        $user->save();
    }
}
