<?php
namespace App\Services;
use App\Models\WkSendRssData;
use Illuminate\Support\Facades\DB;

class RssFeedUtil
{
    private $item;
    private $user_id;
    private $rss_id;
    private $ad_deny_flg;
    private $repeat_deliv_deny_flg;
    private $keywords;
    private $normalization_keywords;
    private $uniq_keywords;

    private $send_rss_feed_title;
    private $send_rss_feed_time;
    private $send_rss_feed_match_keywords;
    private $send_rss_feed_description;
    private $send_rss_feed_link;

    public function getSendRssFeedTitle() {
        return $this->send_rss_feed_title;
    }
    public function getSendRssFeedTime(){
        return $this->send_rss_feed_time;
    }
    public function getSendRssFeedMatchKeywords(){
        return $this->send_rss_feed_match_keywords;
    }
    public function getSendRssFeedDescription(){
        return $this->send_rss_feed_description;
    }
    public function getSendRssFeedLink(){
        return $this->send_rss_feed_link;
    }

    /**
     * RssFeedUtil constructor.
     * @param int $user_id ユーザID
     * @param int $rss_id RSS ID
     * @param bool $ad_deny_flg
     * @param bool $repeat_deliv_deny_flg
     * @param array $keywords
     * @param \SimplePie_Item $item RSSフィード
     */
    public function __construct(
        int $user_id,
        int $rss_id,
        bool $ad_deny_flg,
        bool $repeat_deliv_deny_flg,
        array $keywords,
        \SimplePie_Item $item)
    {
        $this->item = $item;
        $this->user_id=$user_id;
        $this->rss_id=$rss_id;
        $this->ad_deny_flg=$ad_deny_flg;
        $this->repeat_deliv_deny_flg=$repeat_deliv_deny_flg;
        $this->
        //念のため改行コードをそろえておく
        $this->keywords=explode("\n", str_replace(array("\r\n", "\n", "\r"), "\n", $keywords));
        //キーワード文字の正規化を行う(すべて半角化)
        $this->normalization_keywords = $this->mb_convert_kana_variables($this->keywords, 'rnaskh', 'UTF-8');
        //キーワードの重複を除外
        $this->uniq_keywords=array_unique($this->normalization_keywords);

        $this->send_rss_feed_title = null;
        $this->send_rss_feed_time = null;
        $this->send_rss_feed_match_keywords = null;
        $this->send_rss_feed_description = null;
        $this->send_rss_feed_link = null;

    }

    /**
     * タイトルに広告が含まれているか判定。広告配信拒否されていない場合はFalseを返す。
     * @param string $title
     * @return bool|int
     */
    private function isAd(string $title){
        if($this->ad_deny_flg){
            return ($this->array_strpos($title,$this->ad_words()));
        } else {
            return False;
        }
    }

    /**
     * タイトルを取得し、すでにメール送信していないか確認
     * @param string $title RSSタイトル
     * @param bool $repeat_deliv_deny_flg 再配信拒否フラグ
     * @return bool
     */
    private function isSendRss(string $title,bool $repeat_deliv_deny_flg){
        $send_rss_count = DB::table('wk_send_rss_datas')
            ->where('user_id', $this->rss_id)
            ->where('rss_id', $this->rss_id)
            ->where('title', $title);

        //再配信拒否チェック
        if ($repeat_deliv_deny_flg !== True) {
            //同じRSS,同じタイトルで配信する猶予期間は１週間
            $limit_time = date("Ymd", strtotime("-1 week"));
            $send_rss_count = $send_rss_count->where('updated_at', '>=', $limit_time);
        }
        return $send_rss_count->exists();
    }

    /**
     * 記事の中でマッチしたキーワードを返します。
     * @param string $title RSSタイトル
     * @param string $description RSS本文
     * @return null|string マッチしたキーワード
     */
    private function getMatchKeywords(string $title,string $description){
        //記事の中にキーワードにマッチしたものがあるか？
        $match_keywords = null;
        foreach ($this->uniq_keywords as $uniq_keyword) {
            if (stripos(mb_convert_kana($title . $description, 'rnaskh', 'UTF-8'), $uniq_keyword) === FALSE) {
                //不一致はなにもしない
                return null;
            } else {
                //一致したキーワードをセット
                //まずは正規化したキーワードの配列キー取得
                $match_key = array_search($uniq_keyword, $this->normalization_keywords);
                //次に該当する配列キーから登録されたキーワードを取得する
                $match_keywords .= '[' . $this->keywords[$match_key] . ']';
            }
        }
        return $match_keywords;
    }

    /**
     * RSS配信テーブルに記録する。
     * @param string $title
     * @return bool|int
     */
    private function recordSendRssData(string $title){
        DB::beginTransaction();
        try {
            $send_rss_data = new WkSendRssData();
            $send_rss_data->user_id = $this->user_id;
            $send_rss_data->rss_id = $this->rss_id;
            $send_rss_data->title = $title;

            $send_rss_data->save();
            DB::commit();
            return True;
        }catch (\PDOException $e){
            DB::rollBack();
            Logs('rss_send_log')->error('RSS配信テーブル書き込みエラー',['user:' . $this->user_id . ' rss_id:' . $this->rss_id . ' title:' . $title ]);
            return False;
        }
    }
    /**
     *
     */
    public function feedProsessing(){
        $title = $this->feed_title();
        $description = $this->feed_description();
        $feed_link = $this->feed_link();
        $feed_time = $this->feed_time();

        $match_keywords = null;
        //タイトルに広告が入っているか？
        if($this->isAd($title)){
            return;
        }
        //すでに配信済みの記事か？
        if($this->isSendRss($title,$this->repeat_deliv_deny_flg) !==False){
            //未配信ならキーワードマッチングチェック
            $match_keywords = $this->getMatchKeywords($title,$description);
        }
        if(is_null($match_keywords) !==False){
            //キーワードにマッチ

            //再送信記事かチェック
            $repeat_deliv_flg = $this->isSendRss($title,False);

            //配信DBに記録
            $this->recordSendRssData($title);
            //プロパティセット
            //再送ならタイトルに(再)をつける。
            if($repeat_deliv_flg){
                $this->send_rss_feed_title='(再)' . $title;
            }else{
                $this->send_rss_feed_title=$title;
            }
            $this->send_rss_feed_time = $feed_time;
            $this->send_rss_feed_match_keywords=$match_keywords;
            $this->send_rss_feed_description=$description;
            $this->send_rss_feed_link=$feed_link;
        }
        return;
    }
    /**
     * 再帰的にmb_convert_kanaを呼び出す関数定義
     * 引数はmb_convert_kanaと同じ（第一引数が変数でも配列でもOK）
     * @param array|string $value
     * @param String $option
     * @param String $encoding
     * @return array|string
     */
    private function mb_convert_kana_variables($value, String $option, String $encoding)
    {
        //http://soft.fpso.jp/develop/php/entry_1891.htmlを参考に作成してみた
        if (is_array($value)) {
            //配列なら
            foreach ($value as $key => $val) {
                //配列を展開する
                if (is_array($val)) {
                    //展開した値が配列だった
                    //再帰的に呼び出す
                    $val = $this->mb_convert_kana_variables($value[$key], $option, $encoding);
                } else {
                    $val = mb_convert_kana($val, $option, $encoding);
                }
                //展開した配列を元に戻す
                $value[$key] = $val;
            }
            return $value;
        } else {
            //配列ではない
            return mb_convert_kana($value, $option, $encoding);
        }
    }

    /**
     * 配列に格納されたキーワードが存在したかどうかを判定する関数定義
     * @param string $in_str 検索対象文字列
     * @param array $in_array_keyword キーワードの入った連想配列
     * @return bool|int キーワード存在あり（True)なし（False）
     */
    private function array_strpos(string $in_str, array $in_array_keywords)
    {
        foreach ($in_array_keywords as $keyword) {
            if (stripos($in_str, $keyword)) {
                return True;
            }
        }
        return False;
    }

    /**
     * 広告ワード配列
     * @return array
     */
    private function ad_words(){
        return array("[PR]", "【PR】", "AD:", "［PR］", "AD：", "広告：", "PR:", "PR：", "Info:");
    }

    /**
     * RSSフィールドのタイトルを取得
     * 改行コードと文字コードの統一のみ行い、それ以上の扱いは上部にゆだねる
     * @return mixed
     */
    private function feed_title(){
        $title = mb_convert_encoding($this->item->get_title(), 'UTF-8', 'auto');
        return str_replace(array("\r\n", "\n", "\r"), "\n", $title);
    }

    /**
     * RSSフィードの内容を取得
     * 改行コードと文字コードの統一のみ行い、それ以上の扱いは上部にゆだねる
     * @return mixed
     */
    private function feed_description(){
        $description = mb_convert_encoding($this->item->get_description(), 'UTF-8', 'auto');
        return str_replace(array("\r\n", "\n", "\r"), "\n", $description);
    }

    /**
     * RSSフィードのリンクを取得
     * 改行コードと文字コードの統一のみ行い、それ以上の扱いは上部にゆだねる
     * @return mixed
     */
    private function feed_link(){
        $link = mb_convert_encoding($this->item->get_link(), 'UTF-8', 'auto');
        return str_replace(array("\r\n", "\n", "\r"), "\n", $link);
    }

    /**
     * RSSフィードの時刻を取得
     * @return int|null|string
     */
    private function feed_time(){
        return $this->item->get_date('Y-m-d H:i:s'); //2009-04-24 22:25:34
    }
}