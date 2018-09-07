@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ログイン履歴</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>日付</th>
                                <th>アクション</th>
                                <th>IPアドレス</th>
                                <th>ユーザエージェント</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{{ $data->updated_at }}}</td>
                                    <td>{{{ $data->memo }}}</td>
                                    <td>{{{ $data->ipaddr }}}
                                        @php
                                        //ホスト名が引けたら表示
                                        $host = gethostbyaddr($data->ipaddr);
                                        @endphp
                                        @if($data->ipaddr <> $host)
                                        <BR>({{  $host }})
                                        @endif
                                    </td>
                                    <td>{{{ $data->user_agent }}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $datas->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
