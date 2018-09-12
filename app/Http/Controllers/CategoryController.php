<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\RssData;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class CategoryController extends Controller
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
        $categories = Category::query()
            ->orderBy('id')
            ->paginate(25);
        return view('category.index',['categories'=>$categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create');
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
            'category' => [
                                'required',
                                'string',
                                'max:255',
                                Rule::unique('categories')->where(function($query){
                return $query->where('user_id',Auth::user()->id);
            })],
        ])->validate();


        //保存
        $category = Category::create([
            'category' =>$request->category,
            'user_id' => $user->id,
        ])->save();
        //
        return redirect()->route('category.index')->with('status','カテゴリ名['.$request->category.']を追加しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('category.show',['category'=>$category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('category.edit',['category'=>$category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $user = Auth::user();
        if($request->category <> $category->category) {
            //バリデーションチェック
            //ユーザID単位でユニークにする。
            Validator::make($request->all(), [
                'category' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories')->where(function ($query) {
                        return $query->where('user_id', Auth::user()->id);
                    })],
            ])->validate();
            $category_old = $category->category;
            $categorl_new = $request->category;
            //保存
            $category->category = $request->category;
            $category->save();

            return redirect()->route('category.index')->with('status','カテゴリ名[' . $category_old . ']を['.$categorl_new.']に変更しました');
        }else{
            return redirect()->route('category.index')->with('status','カテゴリ名に変更はありませんでした。');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //削除前に参照がないかチェック
        if (RssData::query()->where('category_id', $category->id)->exists()) {
            return redirect()->route('category.index')->with('alert', 'カテゴリ[' . $category->category . ']はRSSより参照されています。削除できません。');
        } else {
           //削除
            $category->delete();
            //リダイレクト
            return redirect()->route('category.index')->with('status', 'カテゴリ名[' . $category->category . ']を削除しました。');
        }
    }
}
