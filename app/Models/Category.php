<?php

namespace App\Models;

use App\Scopes\AuthUserScope;
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
