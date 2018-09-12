<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\AuthUserScope;

class RssDeliveryAttribute extends Model
{
    /**
     * メール配信対象RSSからRSS情報を取得
     */
    public function rss_data()
    {
        return $this->belongsTo('App\Models\RssData','rss_id');
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
