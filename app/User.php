<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\CustomPasswordReset;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * ログイン履歴を取得
     */
    public function login_histories()
    {
        return $this->hasMany('App\Models\LoginHistory', 'user_id');
    }
    /**
     * RSSデータを取得
     */
    public function rss_datas()
    {
        return $this->hasMany('App\Models\RssData', 'user_id');
    }
    /**
     * RSSカテゴリを取得
     */
    public function category_datas()
    {
        return $this->hasMany('App\Models\Category', 'user_id');
    }
    /**
     * パスワードリセット通知の送信
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPasswordReset($token));
    }
}
