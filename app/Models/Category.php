<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * RSSを取得
     */
    public function rss_data()
    {
        return $this->hasMany('App\Models\RssData','category_id');
    }
}
