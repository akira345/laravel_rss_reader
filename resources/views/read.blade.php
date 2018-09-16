@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$comment}} 記事一覧</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @foreach($rss_datas as $rss_data)
                        <h5 class="d-flex mb-3">

                            <span class="d-inline-block"><a href="{{$rss_data['link']}}">{{$rss_data['title']}}</a></span>
                        </h5>
                        <!--明細開始-->
                        <ul class="list-group list-group-flush mt-4">

                            <li class="list-group-item d-inline-flex flex-wrap">
                                <div>{!! nl2br(e($rss_data['description'])) !!}</div>
                                <div>({{$rss_data['time']}})</div>
                            </li>
                        </ul>
                    @endforeach
                    @if($paging_flg)
                        {{ $rss_datas->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
