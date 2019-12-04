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
                        @if (session('alert'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('alert') }}
                            </div>
                        @endif

                        <h1 class="d-flex mb-3">

                            <span class="d-inline-block">RSS一覧</span>
                            <a class="btn btn-success d-inline-flex ml-auto" href="{{ route('rss_data.create') }}">

                                <span class="align-self-center">登録</span>
                            </a>
                        </h1>
                        <div class="row">
                            <div class="col-md-12">
                        <table class="table table-sm table-striped sp-omit">
                            <thead>
                            <tr>
                                <th scope="col"><div class="d-flex">RSSアドレス</div></th>
                                <th scope="col"><div class="d-flex">コメント</div></th>
                                <th scope="col"><div class="d-flex">カテゴリ名</div></th>
                                <th scope="col"><div class="d-flex">一覧非表示</div></th>
                                <th scope="col"><div class="d-flex">メール配信</div></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rss_datas as $rss_data)
                                <tr>
                                    <td scope="row"><a href="{{ route('rss_data.show',['rss_data' => $rss_data->id]) }}">{{{ $rss_data->rss_url }}}</a></td>
                                    <td scope="row">{{{ $rss_data->comment }}}</td>
                                    <td scope="row">{{{ $rss_data->category->category }}}</td>
                                    <td scope="row">
                                        <input type="checkbox" name="rss_view_attribute" value="1" {{ $rss_data->rss_view_attribute->hidden_flg ? 'checked="checked"' : '' }} disabled>
                                    </td>
                                    <td scope="row">
                                        <input type="checkbox" name="rss_view_attribute" value="1" {{ $rss_data->rss_delivery_attribute->deliv_flg ? 'checked="checked"' : '' }} disabled>
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-sm btn-warning" href="{{ route('rss_data.edit',['rss_data' => $rss_data->id]) }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="material-icons d-block">編集</i></a>
                                            <form method="POST" action="{{ route('rss_data.destroy',['rss_data' => $rss_data->id]) }}" accept-charset="UTF-8" style="display: inline;" onsubmit="if(confirm('削除してよいですか?')) { return true } else {return false };">
                                                <input type="hidden" name="_method" value="DELETE">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="material-icons d-block">削除</i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $rss_datas->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
