<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RssDeliveryAttribute extends Model
{
    /**
     * メール配信対象RSSからRSS情報を取得
     */
    public function rss_data()
    {
        return $this->belongsTo('App\Models\RssData','rss_id');
    }
}
