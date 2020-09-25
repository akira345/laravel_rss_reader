<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\AuthUserScope;

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
