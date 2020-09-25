<?php

namespace App\Models;

use App\Scopes\AuthUserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoginHistory extends Model
{
    protected $table = 'login_histories';
    protected $fillable = ['user_id', 'memo', 'ipaddr', 'user_agent'];
    /**
     * @param Int $user_id
     * @param Request $request
     * @param String $memo
     */
    public function record(int $user_id, Request $request, string $memo)
    {
        DB::beginTransaction();
        try {
            LoginHistory::create([
                'user_id' => $user_id,
                'memo' => $memo,
                'ipaddr' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            Log::error('ログインログ記録エラー', ['user:' => $user_id, 'exception' => $e->getMessage()]);
        }
    }
    /**
     * モデルの「初期起動」メソッド
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new AuthUserScope());
    }
}
