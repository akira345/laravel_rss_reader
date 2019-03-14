<?php

namespace App\Http\Controllers;


use App\Models\RssData;
use App\Services\RssFeedUtil;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $rss_datas = RssData::query()
            ->whereIn('id',
                function ($query)
                {
                    $query->select('rss_id')
                        ->from('rss_view_attributes')
                        ->where('hidden_flg',False);
                })
            ->orderBy('category_id')
            ->orderBy('id')
            ->paginate(25);

        return view('home',['rss_datas'=>$rss_datas]);
    }
    public function read(RssData $rssData,Request $request){
        $rss_id = $rssData->id;
        $ad_deny_flg = $rssData->ad_deny_flg;
        $repeat_deliv_deny_flg = $rssData->rss_delivery_attribute->repeat_deliv_deny_flg;
        $keywords = $rssData->keywords;
        $rss_url = $rssData->rss_url;
        $comment = $rssData->comment;
        $rss_contents = [];
        //キャッシュ確認
        $key = sha1($rss_url);
        if(Cache::has($key)) {
        //    dump("cached");
            $rss_contents = Cache::get($key);
        }else{
        //    dump("notcached");
            //RSSのパーズ
            $feed = \Feeds::make($rss_url);
            foreach ($feed->get_items() as $item) {
                $rss_feed = new RssFeedUtil(Auth::user()->id, $rss_id, $ad_deny_flg, $repeat_deliv_deny_flg, $keywords, $item);
                $rss_feed->feedProsessingList();
                if (is_null($rss_feed->getSendRssFeedTitle()) !== True) {
                    array_push($rss_contents, [
                        'title' => $rss_feed->getSendRssFeedTitle(),
                        'time' => $rss_feed->getSendRssFeedTime(),
                        'description' => $rss_feed->getSendRssFeedDescription(),
                        'link' => $rss_feed->getSendRssFeedLink(),
                    ]);
                }
            }
            //5分間キャッシュさせる
            Cache::put($key, $rss_contents, now()->addMinutes(5));
        }

        $page = $rssData->rss_view_attribute->rss_contents_list_cnt;
        $list_rss_data = null;
        $paging_flg = False;
        if ($page >0){
            $paging_flg = True;
            $div_rss_data = array_chunk($rss_contents, $page);
            $get_page = $request->input('page', 1);

            $list_rss_data = new LengthAwarePaginator(
                $div_rss_data[$get_page - 1],
                count($rss_contents),
                $page,
                $get_page,
                array('path' => $request->url())
            );
        }else{
            $list_rss_data = $rss_contents;
        }
        return view('read',['rss_datas'=>$list_rss_data,'comment'=>$comment,'paging_flg'=>$paging_flg]);
    }
}
