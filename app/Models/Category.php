<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable= ['category','user_id'];

    /**
     * RSSを取得
     */
    public function rss_datas()
    {
        return $this->hasMany('App\Models\RssData','category_ids');
    }
}
