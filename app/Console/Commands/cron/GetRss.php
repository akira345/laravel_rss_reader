<?php

namespace App\Console\Commands\cron;

use App\Models\WkSendRssData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Mail\SendRssMail;
use Illuminate\Support\Facades\Mail;
use Swift_RfcComplianceException;


class GetRss extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getrss';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GetRSS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = DB::table('users')
            ->orderBy('id')
            ->get();
        foreach ($users as $user) {
            //配信対象RSSデータ取得
            $rss_datas = DB::table('rss_datas')
                ->join('rss_delivery_attributes', 'rss_datas.id', '=', 'rss_delivery_attributes.rss_id')
                ->select('rss_datas.id', 'rss_datas.rss_url', 'rss_datas.keywords', 'rss_datas.ad_deny_flg', 'rss_datas.comment', 'rss_delivery_attributes.repeat_deliv_deny_flg')
                ->where('rss_delivery_attributes.deliv_flg', True)
                ->where('rss_datas.user_id', $user->id)
                ->orderBy('rss_datas.user_id')
                ->orderBy('rss_datas.id')
                ->get();

            $wk_body2 = '';//RSS単位の文面
            foreach ($rss_datas as $rss) {
                //キーワードを取得
                //念のため改行コードをそろえておく
                $keyword = explode("\n", str_replace(array("\r\n", "\n", "\r"), "\n", $rss->keywords));
                //表示でつかうので退避
                $wk_keyword = $keyword;
                //キーワード文字の正規化を行う(すべて半角化)
                $keyword = $this->mb_convert_kana_variables($keyword, 'rnaskh', 'UTF-8');
                //キーワードの重複を除外
                $uniq_keyword = array_unique($keyword);
                //RSSのURL
                $url = html_entity_decode($rss->rss_url, ENT_QUOTES);
                //コメント
                $comment = $rss->comment;
                //RSSのパーズ
                $feed = \Feeds::make($url);
                $wk_body = '';//記事のタイトル、本文
                foreach ($feed->get_items() as $item) {
                    //ここの文字コードも取得できたら置換する
                    //記事単位
                    $title = strip_tags(mb_convert_encoding($item->get_title(), 'UTF-8', 'auto'));
                    $summary = strip_tags(mb_convert_encoding($item->get_description(), 'UTF-8', 'auto'));
                    $wk_url = htmlspecialchars(mb_convert_encoding($item->get_link(), 'UTF-8', 'auto'));
                    $wk_time = $item->get_date('Y-m-d H:i:s'); //2009-04-24 22:25:34

                    $match_keywords = '';//マッチングキーワードクリア
                    //広告NG処理
                    $ad_words = array("[PR]", "【PR】", "AD:", "［PR］", "AD：", "広告：", "PR:", "PR：", "Info:");

                    //タイトルにNGワードがあったらスキップ
                    if (($rss->ad_deny_flg == True) && ($this->array_strpos($title, $ad_words) == True)) {
                        continue;    //以下の処理スキップ
                    }

                    //タイトルを取得し、すでにメール送信していないか確認
                    $send_rss_count = DB::table('wk_send_rss_datas')
                        ->where('user_id', $user->id)
                        ->where('rss_id', $rss->id)
                        ->where('title', $title);

                    //再配信拒否チェック
                    if ($rss->repeat_deliv_deny_flg != True) {
                        //同じRSS,同じタイトルで配信する猶予期間は１週間
                        $limit_time = date("Ymd", strtotime("-1 week"));
                        $send_rss_count = $send_rss_count->where('updated_at', '>=', $limit_time);
                    }
                    $send_rss_count = $send_rss_count->count();

                    if ($send_rss_count == 0) {
                        //記事の中にキーワードにマッチしたものがあるか？
                        foreach ($uniq_keyword as $key) {
                            if (stripos(mb_convert_kana($title . $summary, 'rnaskh', 'UTF-8'), $key) === FALSE) {
                                //不一致はなにもしない
                            } else {
                                //一致したキーワードをセット
                                //まずは正規化したキーワードの配列キー取得
                                $match_key = array_search($key, $keyword);
                                //次に該当する配列キーから登録されたキーワードを取得する
                                $match_keywords .= '[' . $wk_keyword[$match_key] . ']';
                            }
                        }
                    }
                    if (($match_keywords) != '') {
                        //マッチしたキーワードがあった
                        //再送の場合はタイトルに(再)をつける
                        $send_rss_count = DB::table('wk_send_rss_datas')
                            ->where('user_id', $user->id)
                            ->where('rss_id', $rss->id)
                            ->where('title', $title)
                            ->count();

                        if ($send_rss_count != 0) {
                            $resend_flg = 1;
                        } else {
                            $resend_flg = 0;
                        }
                        //メール送信テーブルに記録
                        $send_rss_data = new WkSendRssData();
                        $send_rss_data->user_id = $user->id;
                        $send_rss_data->rss_id = $rss->id;
                        $send_rss_data->title = $title;

                        $send_rss_data->save();

                        if ($resend_flg != 0) {
                            $title = "(再)" . $title;
                        }
                        //メール用文面構築
                        $wk_body .= "タイトル：" . $title . "\n";    //タイトル
                        $wk_body .= "(" . $wk_time . ")" . "\n";    //記事の日付
                        $wk_body .= "マッチしたキーワード：" . $match_keywords . "\n";
                        $wk_body .= $summary . "\n";    //本文
                        $wk_body .= "link:" . $wk_url . "\n";
                        $wk_body .= "--------------------------------------------------------------------" . "\n\n";
                    } else {
                        //キーワードアンマッチ
                        null;
                    }
                }
                if (($wk_body) != '') {
                    //RSS単位
                    $wk_body2 .= "RSS:「" . $comment . "」" . "\n\n";
                    $wk_body2 .= $wk_body;
                }
            }
            //メール送信
            if (($wk_body2) != '') {
                echo "メール送信！！";
                mb_language("Ja");
                mb_internal_encoding("UTF-8");
                $mailto = $user->email;
                $subject = "RSSマッチングレポート(" . date('Ymd') . ")";
                $content = "キーワードにマッチングした記事を送信いたします。" . "\n\n" . $wk_body2;
                $mailfrom = "info@exsample.com";
                //dump($content);
                try {
                    Mail::to($mailto)->send(new SendRssMail($subject, $content, $mailfrom));
                } catch (Swift_RfcComplianceException $e) {
                    dump("RFC違反のメールです。:" . $mailto);
                }
            }
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
    private function array_strpos(string $in_str, array $in_array_keyword)
    {
        $ret = False;
        foreach ($in_array_keyword as $key) {
            $ret = stripos($in_str, $key);
            if ($ret !== False) {
                $ret = True;
                break;
            }
        }
        return $ret;
    }
}
