@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">RSS一覧</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @php
                        $sv_category_id = "key";
                        $first_flg = True;
                    @endphp
                    @foreach($rss_datas as $rss_data)
                        @if ($sv_category_id !== $rss_data->category_id)
                            @php
                                $sv_category_id = $rss_data->category_id;
                            @endphp
                            @if($first_flg)
                                @php
                                    $first_flg = False;
                                @endphp
                            @else
                        <!--明細終了-->
                        </ul>
                            @endif
                        <!--ヘッダ-->
                        <h5 class="d-flex mb-3">

                            <span class="d-inline-block">{{ is_null($rss_data->category_id)? '指定なし:':$rss_data->category->category }}</span>

                        </h5>
                        <!--明細開始-->
                        <ul class="list-group list-group-flush mt-4">
                             @endif

                            <li class="list-group-item d-inline-flex flex-wrap">
                                <div><a href="{{ route('home.read',['rss_data' => $rss_data->id]) }}">{{ $rss_data->comment }}</a></div>
                            </li>

                        @endforeach
                        <!--明細終了-->
                        </ul>
                        {{ $rss_datas->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
