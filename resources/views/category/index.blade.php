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

                            <span class="d-inline-block">カテゴリ一覧</span>
                            <a class="btn btn-success d-inline-flex ml-auto" href="{{ route('category.create') }}">

                                <span class="align-self-center">登録</span>
                            </a>
                        </h1>
                        <div class="row">
                            <div class="col-md-12">
                        <table class="table table-sm table-striped sp-omit">
                            <thead>
                            <tr>
                                <th scope="col"><div class="d-flex">カテゴリ名</div></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <td scope="row"><a href="{{ route('category.store',['id' => $data->id]) }}">{{{ $data->category }}}</a></td>
                                    <td class="text-right">
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-sm btn-warning" href="{{ route('category.edit',['id' => $data->id]) }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="material-icons d-block">編集</i></a>
                                            <form method="POST" action="{{ route('category.destroy',['id' => $data->id]) }}" accept-charset="UTF-8" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
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
                        {{ $datas->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
