<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LoginHistory extends Model
{
    /**
     * @param Int $user_id
     * @param Request $request
     * @param String $memo
     */
    public function record(int $user_id,Request $request,string $memo){
        $login_his_db = new LoginHistory();
        $login_his_db->user_id = $user_id;
        $login_his_db->memo = $memo;
        $login_his_db->ipaddr = $request->ip();
        $login_his_db->user_agent = $request->userAgent();
        $login_his_db->save();
    }
}
