<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;

class RssUtilService
{
    private $user;

    /**
     *
     */
    public function __construct()
    {
        //
    }

    private $send_rss_data_contents = [];

    /**
     * ユーザごとのRSS情報を取得します。
     * @return \Illuminate\Support\Collection
     */
    private function getRssDb(){
        $rss_datas = DB::table('rss_datas')
            ->join('rss_delivery_attributes', 'rss_datas.id', '=', 'rss_delivery_attributes.rss_id')
            ->select('rss_datas.id', 'rss_datas.rss_url', 'rss_datas.keywords', 'rss_datas.ad_deny_flg', 'rss_datas.comment', 'rss_delivery_attributes.repeat_deliv_deny_flg')
            ->where('rss_delivery_attributes.deliv_flg', True)
            ->where('rss_datas.user_id', $this->user->id)
            ->orderBy('rss_datas.user_id')
            ->orderBy('rss_datas.id')
            ->get();
        return $rss_datas;
    }

    public function RssProsessing(\stdClass $user){
        $this->user = $user;
        Logs('rss_send_log')->info('RSS取得開始',['user_id:' => $this->user->id ]);

        $rss_datas = $this->getRssDb();
        foreach ($rss_datas as $rss_data) {
            $rss_id = $rss_data->id;
            $ad_deny_flg = $rss_data->ad_deny_flg;
            $repeat_deliv_deny_flg = $rss_data->repeat_deliv_deny_flg;
            $keywords = $rss_data->keywords;
            $rss_url = $rss_data->rss_url;
            $comment = $rss_data->comment;
            //RSSのパーズ
            Logs('rss_send_log')->info('RSSパース',['rss_url:' => $rss_url ]);
            $feed = \Feeds::make($rss_url);
            $rss_contents = [];
            foreach ($feed->get_items() as $item) {
                $rss_feed = new RssFeedUtil($this->user->id, $rss_id, $ad_deny_flg, $repeat_deliv_deny_flg, $keywords, $item);
                $rss_feed->feedProsessingDelivery();
                if (is_null($rss_feed->getSendRssFeedTitle()) !== True) {
                    array_push($rss_contents , [
                        'title' => $rss_feed->getSendRssFeedTitle(),
                        'time' => $rss_feed->getSendRssFeedTime(),
                        'match_keywords' => $rss_feed->getSendRssFeedMatchKeywords(),
                        'description' => $rss_feed->getSendRssFeedDescription(),
                        'link' => $rss_feed->getSendRssFeedLink(),
                    ]);
                }
            }
            if (count($rss_contents) > 0) {
                array_push($this->send_rss_data_contents , [
                    'rss_comments' => $comment,
                    'rss_contents' => $rss_contents,
                ]);
            }
        }
        Logs('rss_send_log')->info('RSS取得終了',['user_id:' => $this->user->id ]);
        return $this->send_rss_data_contents;
    }

}