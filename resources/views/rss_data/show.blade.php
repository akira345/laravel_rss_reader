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
                        <div class="card">
                            <div class="card-body">

                                <h1 class="d-flex mb-3">

                                    <span class="d-inline-block">RSSデータ</span>
                                    <form class="ml-auto" method="POST" action="{{ route('rss_data.destroy',['rss_data' => $rss_data]) }}" accept-charset="UTF-8" style="display: inline;" onsubmit="if(confirm('削除してよいですか?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        @csrf
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-sm btn-warning" href="{{ route('rss_data.edit',['rss_data' => $rss_data]) }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="material-icons d-block">編集</i></a>
                                            <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="material-icons d-block">削除</i></button>
                                        </div>
                                    </form>
                                </h1>

                                <ul class="list-group list-group-flush mt-4">

                                    <li class="list-group-item d-inline-flex flex-wrap">
                                        <div><strong>RSSフィードURL : </strong></div><div>{{ $rss_data->rss_url }}</div>
                                    </li>
                                    <li class="list-group-item d-inline-flex flex-wrap">
                                        <div><strong>コメント : </strong></div><div>{{ $rss_data->comment }}</div>
                                    </li>
                                    <li class="list-group-item d-inline-flex flex-wrap">
                                        <div><strong>カテゴリ : </strong></div><div>{{ $rss_data->category->category }}</div>
                                    </li>
                                    <li class="list-group-item d-inline-flex flex-wrap">
                                        <div><strong>配信キーワード : </strong></div><div>{{ str_replace (array("\r\n", "\n", "\r"),'　
                                        ',$rss_data->keywords) }}</div>
                                    </li>
                                    <li class="list-group-item d-inline-flex flex-wrap">
                                        <div><strong>広告拒否 : </strong></div><div>{{ $rss_data->ad_deny_flg ? '拒否する' : '拒否しない' }}</div>
                                    </li>
                                    <li class="list-group-item d-inline-flex flex-wrap">
                                        <div><strong>メール配信 : </strong></div><div>{{$rss_data->rss_delivery_attribute->deliv_flg ? '配信する' : '配信しない' }}</div>
                                    </li>
                                    <li class="list-group-item d-inline-flex flex-wrap">
                                        <div><strong>再配送拒否 : </strong></div><div>{{$rss_data->rss_delivery_attribute->repeat_deliv_deny_flg ? '再配送しない' : '再配送する' }}</div>
                                    </li>
                                    <li class="list-group-item d-inline-flex flex-wrap">
                                        <div><strong>RSS記事表示数 : </strong></div><div>@if ($rss_data->rss_view_attribute->rss_contents_list_cnt === 0) 全件表示 @else {{$rss_data->rss_view_attribute->rss_contents_list_cnt}}@endif</div>
                                    </li>
                                    <li class="list-group-item d-inline-flex flex-wrap">
                                        <div><strong>RSS一覧表示 : </strong></div><div>{{$rss_data->rss_view_attribute->hidden_flg ? '表示しない' : '表示する' }}</div>
                                    </li>
                                </ul>

                                <div class="d-flex justify-content-end mt-3">
                                    <a class="btn btn-secondary d-inline-flex mr-3" href="{{route('rss_data.index')}}">一覧へ戻る</a>
                                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
