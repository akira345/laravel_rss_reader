<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\AuthUserScope;

/**
 * App\Models\RssDeliveryAttribute
 *
 * @property int $id
 * @property int $rss_id RSS_ID
 * @property bool $deliv_flg メール配信フラグ
 * @property bool $repeat_deliv_deny_flg 再配送拒否フラグ
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RssData $rss_data
 * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute whereDelivFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute whereRepeatDelivDenyFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute whereRssId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RssDeliveryAttribute extends Model
{
    protected $table = 'rss_delivery_attributes';
    protected $fillable = ['rss_id', 'deliv_flg', 'repeat_deliv_deny_flg'];
    /**
     * メール配信対象RSSからRSS情報を取得
     */
    public function rss_data()
    {
        return $this->belongsTo('App\Models\RssData', 'rss_id')->withoutGlobalScopes();
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
