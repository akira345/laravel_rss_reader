<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RssViewAttribute extends Model
{
    /**
     * 表示対象RSSからRSS情報を取得
     */
    public function rss_data()
    {
        return $this->belongsTo('App\Models\RssData','rss_id');
    }
}
