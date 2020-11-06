<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models {
    /**
     * App\Models\Category
     *
     * @property int $id
     * @property int $user_id 登録ユーザID
     * @property string $category カテゴリ名
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RssData[] $rss_datas
     * @property-read int|null $rss_datas_count
     * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Category query()
     * @method static \Illuminate\Database\Eloquent\Builder|Category whereCategory($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Category whereUserId($value)
     */
    class Category extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     * App\Models\LoginHistory
     *
     * @property int $id
     * @property int $user_id 登録ユーザID
     * @property string $memo 備考
     * @property string $ipaddr アクセス元IPアドレス
     * @property string $user_agent ユーザエージェント
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory query()
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereIpaddr($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereMemo($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereUserAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereUserId($value)
     */
    class LoginHistory extends \Eloquent
    {
    }
}

namespace App\Models {
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
     */
    class RssData extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     * App\Models\RssDeliveryAttribute
     *
     * @property int $id
     * @property int $rss_id RSS_ID
     * @property bool $deliv_flg メール配信フラグ
     * @property bool $repeat_deliv_deny_flg 再配送拒否フラグ
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\RssData $rss_data
     * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute query()
     * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute whereDelivFlg($value)
     * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute whereRepeatDelivDenyFlg($value)
     * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute whereRssId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|RssDeliveryAttribute whereUpdatedAt($value)
     */
    class RssDeliveryAttribute extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     * App\Models\RssViewAttribute
     *
     * @property int $id
     * @property int $rss_id RSS_ID
     * @property int $rss_contents_list_cnt RSS記事表示数
     * @property bool $hidden_flg 非表示フラグ
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\RssData $rss_data
     * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute query()
     * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute whereHiddenFlg($value)
     * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute whereRssContentsListCnt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute whereRssId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|RssViewAttribute whereUpdatedAt($value)
     */
    class RssViewAttribute extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     * App\Models\WkSendRssData
     *
     * @property int $id
     * @property int $user_id 登録ユーザID
     * @property int $rss_id RSS ID
     * @property string $title 配信済みRSSタイトル
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData query()
     * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData whereRssId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WkSendRssData whereUserId($value)
     */
    class WkSendRssData extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\User
     *
     * @property int $id
     * @property string $name
     * @property string $email
     * @property string $password
     * @property string|null $remember_token
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property string|null $last_login_at 最終ログイン
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $category_datas
     * @property-read int|null $category_datas_count
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LoginHistory[] $login_histories
     * @property-read int|null $login_histories_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RssData[] $rss_datas
     * @property-read int|null $rss_datas_count
     * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User query()
     * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
     */
    class User extends \Eloquent
    {
    }
}
