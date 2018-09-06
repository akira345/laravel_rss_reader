<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RssData extends Model
{
    /**
     * RSSに対するカテゴリを取得
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category','category_id')->withDefault();;
    }
    /**
     * RSSに対する表示属性を取得
     */
    public function rss_view_attribute()
    {
        return $this->hasOne('App\Models\RssViewAttribute','rss_id');
    }
    /**
     * RSSに対するメール配信属性を取得
     */
    public function rss_delivery_attribute()
    {
        return $this->hasOne('App\Models\RssDeliveryAttribute','rss_id');
    }
}
