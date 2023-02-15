<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\AuthUserScope;

/**
 * App\Models\WkSendRssData
 *
 * @property int $id
 * @property int $user_id 登録ユーザID
 * @property int $rss_id RSS ID
 * @property string $title 配信済みRSSタイトル
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData query()
 * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData whereRssId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData whereUserId($value)
 * @mixin \Eloquent
 */
class WkSendRssData extends Model
{
    protected $table = 'wk_send_rss_datas';
    protected $fillable = ['user_id', 'rss_id', 'title'];
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
