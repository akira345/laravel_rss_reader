<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\AuthUserScope;

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
