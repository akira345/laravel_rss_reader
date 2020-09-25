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
use Illuminate\Validation\Rule;

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
        return view('rss_data.index', ['rss_datas' => $rss_datas]);
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
        return view('rss_data.create', ['categories' => $categories]);
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
                'active_url',
                Rule::unique('rss_datas')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
            ],
        ])->validate();

        Validator::make($request->all(), [
            'comment' => [
                'required',
                'string',
                'max:512'
            ],
            'category_id' => [
                'integer',
                'min:0',
                'max:2147483647',
                'nullable'
            ],
            'keywords' => [
                'required',
                'string',
                'max:2000'
            ],
            'ad_deny_flg' => ['boolean'],
            'deliv_flg' => ['boolean'],
            'repeat_deliv_deny_flg' => ['boolean'],
            'rss_contents_list_cnt' => [
                'integer',
                'min:0',
                'max:32767',
                'nullable'
            ],
            'hidden_flg' => ['boolean']
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
            RssDeliveryAttribute::create([
                'rss_id' => $rss_data->id,
                'deliv_flg' => $this->isCheck($request->deliv_flg),
                'repeat_deliv_deny_flg' => $this->isCheck($request->repeat_deliv_deny_flg),
            ]);
            RssViewAttribute::create([
                'rss_id' => $rss_data->id,
                'rss_contents_list_cnt' => is_null($request->rss_contents_list_cnt) ? 0 : $request->rss_contents_list_cnt,
                'hidden_flg' => $this->isCheck($request->hidden_flg),
            ]);
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            Log::error('RSS追加時にエラー', ['user:' => $user->id, 'exception' => $e->getMessage()]);
            return redirect()->route('rss_data.index')->with('alert', 'RSS[' . $request->comment . ']の追加に失敗しました。');
        }
        //二重投稿防止
        $request->session()->regenerateToken();
        return redirect()->route('rss_data.index')->with('status', 'RSS[' . $request->comment . ']を追加しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RssData  $rssData
     * @return \Illuminate\Http\Response
     */
    public function show(RssData $rssData)
    {
        return view('rss_data.show', ['rss_data' => $rssData]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RssData  $rssData
     * @return \Illuminate\Http\Response
     */
    public function edit(RssData $rssData)
    {
        $categories = Category::query()
            ->orderBy('id')
            ->pluck('category', 'id');
        return view('rss_data.edit', ['categories' => $categories, 'rss_data' => $rssData]);
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
        $user = Auth::user();
        if ($request->rss_url <> $rssData->rss_url) {
            //バリデーションチェック
            //ユーザID単位でユニークにする。
            Validator::make($request->all(), [
                'rss_url' => [
                    'required',
                    'string',
                    'max:2000',
                    'url',
                    'active_url',
                    Rule::unique('rss_datas')->where(function ($query) {
                        return $query->where('user_id', Auth::user()->id);
                    })
                ],
            ])->validate();
        }
        Validator::make($request->all(), [
            'comment' => [
                'required',
                'string',
                'max:512'
            ],
            'category_id' => [
                'integer',
                'min:0',
                'max:2147483647',
                'nullable'
            ],
            'keywords' => [
                'required',
                'string',
                'max:2000'
            ],
            'ad_deny_flg' => ['boolean'],
            'deliv_flg' => ['boolean'],
            'repeat_deliv_deny_flg' => ['boolean'],
            'rss_contents_list_cnt' => [
                'integer',
                'min:0',
                'max:32767',
                'nullable'
            ],
            'hidden_flg' => ['boolean']
        ])->validate();

        //保存
        DB::beginTransaction();
        try {
            $rssData->rss_url = $request->rss_url;
            $rssData->comment = $request->comment;
            $rssData->category_id = $request->category_id;
            $rssData->keywords = $request->keywords;
            $rssData->ad_deny_flg = $this->isCheck($request->ad_deny_flg);
            $rssData->save();

            RssDeliveryAttribute::where('rss_id', $rssData->id)
                ->withoutGlobalScopes()
                ->update([
                    'deliv_flg' => $this->isCheck($request->deliv_flg),
                    'repeat_deliv_deny_flg' => $this->isCheck($request->repeat_deliv_deny_flg),
                ]);
            RssViewAttribute::where('rss_id', $rssData->id)
                ->withoutGlobalScopes()
                ->update([
                    'rss_contents_list_cnt' => is_null($request->rss_contents_list_cnt) ? 0 : $request->rss_contents_list_cnt,
                    'hidden_flg' => $this->isCheck($request->hidden_flg),
                ]);
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            Log::error('RSS変更時にエラー', ['user:' => $user->id, 'rss:' => $request->comment, 'exception' => $e->getMessage()]);
            return redirect()->route('rss_data.index')->with('alert', 'RSS[' . $request->comment . ']を変更失敗しました。');
        }
        //二重投稿防止
        $request->session()->regenerateToken();
        return redirect()->route('rss_data.index')->with('status', 'RSS[' . $request->comment . ']を変更しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RssData  $rssData
     * @return \Illuminate\Http\Response
     */
    public function destroy(RssData $rssData)
    {
        //削除
        DB::beginTransaction();
        try {
            $rssData->delete();
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            Log::error('RSS削除時にエラー', ['user:' => Auth::user()->id, 'rss_id:' => $rssData->id, 'exception' => $e->getMessage()]);
            return redirect()->route('rss_data.index')->with('alert', 'RSS[' . $rssData->comment . ']の削除に失敗しました。');
        }
        //リダイレクト
        return redirect()->route('rss_data.index')->with('status', 'RSS[' . $rssData->comment . ']を削除しました。');
    }
    private function isCheck($checkbox_value)
    {
        return (isset($checkbox_value) == '1' ? '1' : '0');
    }
}
