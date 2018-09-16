@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <h1 class="d-flex mb-3">
                            <span class="d-inline-block">RSS登録</span>
                        </h1>
                        <div class="row">
                            <div class="col-md-12">
                                <form method="POST" action="{{route('rss_data.store')}}" accept-charset="UTF-8" class="needs-validation" novalidate>
                                    @csrf

                                    <div class="form-group">
                                        <label for="rss_url-field">RSSフィードURL</label>

                                        <input id="rss_url-field" type="text" class="form-control{{ $errors->has('rss_url') ? ' is-invalid' : '' }}"  name="rss_url" value="{{ old('rss_url') }}" required autofocus>

                                        @if ($errors->has('rss_url'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('rss_url') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="comment-field">コメント</label>

                                        <input id="comment-field" type="text" class="form-control{{ $errors->has('comment') ? ' is-invalid' : '' }}"  name="comment" value="{{ old('comment') }}" required >

                                        @if ($errors->has('comment'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('comment') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="category_id-field">カテゴリ</label>
                                        {{Form::select('category_id', $categories, null, ['id' => 'category_id-field','class' => 'form-control', 'placeholder' => '指定なし'])}}
                                        @if ($errors->has('category_id'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('category_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="keywords-field">配信キーワード<br>
                                            (改行で区切ります。大文字小文字、ひらがなカタカナ、半角全角は区別せずなるべくマッチさせます。）
                                        </label>

                                        <textarea id="keywords-field" type="text" class="form-control{{ $errors->has('keywords') ? ' is-invalid' : '' }}"  name="keywords" rows="3" required >{{ old('keywords') }}</textarea>

                                        @if ($errors->has('keywords'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('keywords') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="checkbox">
                                        <label for="ad_deny_flg-field">広告拒否</label>

                                        {{Form::checkbox('ad_deny_flg', '1')}}
                                        @if ($errors->has('ad_deny_flg'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('ad_deny_flg') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="checkbox">
                                        <label for="deliv_flg-field">メール配信</label>
                                        {{Form::checkbox('deliv_flg', '1')}}
                                        @if ($errors->has('deliv_flg'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('deliv_flg') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="checkbox">
                                        <label for="repeat_deliv_deny_flg-field">前県表示<br>
                                        有効にすると、同じタイトルの記事を一週間再送しません。
                                        </label>
                                        {{Form::checkbox('repeat_deliv_deny_flg', '1')}}
                                        @if ($errors->has('repeat_deliv_deny_flg'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('repeat_deliv_deny_flg') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="rss_contents_list_cnt-field">RSS記事表示数<br>
                                            記事一覧表示に表示する記事数。この数でページを分割します。空欄またはゼロで分割しません。
                                        </label>

                                        <input id="rss_contents_list_cnt-field" type="text" class="form-control{{ $errors->has('rss_contents_list_cnt') ? ' is-invalid' : '' }}"  name="rss_contents_list_cnt" value="{{ old('rss_contents_list_cnt') }}" size="4" >

                                        @if ($errors->has('rss_contents_list_cnt'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('rss_contents_list_cnt') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="checkbox">
                                        <label for="hiddel_flg-field">非表示<br>
                                            有効にすると、RSSを表示しません。
                                        </label>
                                        {{Form::checkbox('hidden_flg', '1')}}
                                        @if ($errors->has('hidden_flg'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('hidden_flg') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <a class="btn btn-secondary d-inline-flex mr-3" href="{{route('rss_data.index')}}">一覧へ戻る</a>
                                        <button type="submit" class="btn btn-primary">登録</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
