<?php

namespace App\Models;

use App\Scopes\AuthUserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\LoginHistory
 *
 * @property int $id
 * @property int $user_id 登録ユーザID
 * @property string $memo 備考
 * @property string $ipaddr アクセス元IPアドレス
 * @property string $user_agent ユーザエージェント
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereIpaddr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereMemo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereUserId($value)
 * @mixin \Eloquent
 */
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
