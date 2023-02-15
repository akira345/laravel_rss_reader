<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\AuthUserScope;

/**
 * App\Models\RssViewAttribute
 *
 * @property int $id
 * @property int $rss_id RSS_ID
 * @property int $rss_contents_list_cnt RSS記事表示数
 * @property bool $hidden_flg 非表示フラグ
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RssData $rss_data
 * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute whereHiddenFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute whereRssContentsListCnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute whereRssId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RssViewAttribute extends Model
{
    protected $table = 'rss_view_attributes';
    protected $fillable = ['rss_id', 'rss_contents_list_cnt', 'hidden_flg'];
    /**
     * 表示対象RSSからRSS情報を取得
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
