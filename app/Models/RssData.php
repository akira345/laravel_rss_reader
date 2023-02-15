<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\AuthUserScope;

/**
 * App\Models\RssData
 *
 * @property int $id
 * @property int $user_id 登録ユーザID
 * @property string $rss_url RSS URL
 * @property string $comment コメント
 * @property int|null $category_id カテゴリID
 * @property string $keywords 配信キーワード
 * @property bool $ad_deny_flg 広告拒否フラグ
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\RssDeliveryAttribute|null $rss_delivery_attribute
 * @property-read \App\Models\RssViewAttribute|null $rss_view_attribute
 * @method static \Illuminate\Database\Eloquent\Builder|RssData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RssData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RssData query()
 * @method static \Illuminate\Database\Eloquent\Builder|RssData whereAdDenyFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssData whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssData whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssData whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssData whereRssUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RssData whereUserId($value)
 * @mixin \Eloquent
 */
class RssData extends Model
{
    protected $table = 'rss_datas';
    protected $fillable = ['user_id', 'rss_url', 'comment', 'category_id', 'keywords', 'ad_deny_flg'];
    /**
     * RSSに対するカテゴリを取得
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id')->withDefault();
    }
    /**
     * RSSに対する表示属性を取得
     */
    public function rss_view_attribute()
    {
        return $this->hasOne('App\Models\RssViewAttribute', 'rss_id')->withoutGlobalScopes();
    }
    /**
     * RSSに対するメール配信属性を取得
     */
    public function rss_delivery_attribute()
    {
        return $this->hasOne('App\Models\RssDeliveryAttribute', 'rss_id')->withoutGlobalScopes();
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
