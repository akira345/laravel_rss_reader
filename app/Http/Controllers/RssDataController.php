<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\RssData;
use App\Models\RssDeliveryAttribute;
use App\Models\RssViewAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RssDataController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rss_datas = RssData::query()
            ->orderBy('id')
            ->paginate(25);
        return view('rss_data.index',['rss_datas'=>$rss_datas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::query()
            ->orderBy('id')
            ->pluck('category', 'id');
        return view('rss_data.create',['categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        //バリデーションチェック
        //ユーザID単位でユニークにする。
        Validator::make($request->all(), [
            'rss_url' => [
                'required',
                'string',
                'max:2000',
                'url',
                'active_url'
                ],
            'comment'=>[
                'required',
                'string',
                'max:512'
            ],
            'category_id'=>[
                'integer',
                'min:0',
                'max:2147483647',
                'nullable'
            ],
            'keywords'=>[
                'required',
                'string',
                'max:2000'
            ],
            'ad_deny_flg'=>['boolean'],
            'deliv_flg'=>['boolean'],
            'repeat_deliv_deny_flg'=>['boolean'],
            'rss_contents_list_cnt'=>[
                'integer',
                'min:0',
                'max:32767',
                'nullable'
            ],
            'hidden_flg'=>['boolean']
        ])->validate();

        //保存
        DB::beginTransaction();
        try {
            $rss_data = RssData::create([
                'user_id' => $user->id,
                'rss_url' => $request->rss_url,
                'comment' => $request->comment,
                'category_id' => $request->category_id,
                'keywords' => $request->keywords,
                'ad_deny_flg' => $this->isCheck($request->ad_deny_flg),
            ]);
            dump($rss_data);
            RssDeliveryAttribute::create([
                'rss_id' => $rss_data->id,
                'deliv_flg' => $this->isCheck($request->deliv_flg),
                'repeat_deliv_deny_flg' =>$this->isCheck($request->repeat_deliv_deny_flg),
            ]);
            RssViewAttribute::create([
                'rss_id' => $rss_data->id,
                'rss_contents_list_cnt' => is_null($request->rss_contents_list_cnt) ? 0 : $request->rss_contents_list_cnt,
                'hidden_flg' => $this->isCheck($request->hidden_flg),
            ]);
            DB::commit();
        }catch (\PDOException $e){
            DB::rollBack();
            Log::error('RSS追加時にエラー',['user:'=> $user->id ,'exception'=> $e->getMessage()]);
            return redirect()->route('rss_data.index')->with('alert','RSS['.$request->comment.']の追加に失敗しました。');
        }
        //二重投稿防止
        $request->session()->regenerateToken();
        return redirect()->route('rss_data.index')->with('status','RSS['.$request->comment.']を追加しました');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RssData  $rssData
     * @return \Illuminate\Http\Response
     */
    public function show(RssData $rssData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RssData  $rssData
     * @return \Illuminate\Http\Response
     */
    public function edit(RssData $rssData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RssData  $rssData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RssData $rssData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RssData  $rssData
     * @return \Illuminate\Http\Response
     */
    public function destroy(RssData $rssData)
    {
        //
    }
    private function isCheck($checkbox_value){
        return (isset($checkbox_value) == '1' ? '1' : '0');
    }
}
