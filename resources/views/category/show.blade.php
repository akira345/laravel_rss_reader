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

                                    <span class="d-inline-block">カテゴリ</span>
                                    <form class="ml-auto" method="POST" action="{{ route('category.destroy',['category' => $category]) }}" accept-charset="UTF-8" style="display: inline;" onsubmit="if(confirm('削除してよいですか?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        @csrf
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-sm btn-warning" href="{{ route('category.edit',['category' => $category]) }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="material-icons d-block">編集</i></a>
                                            <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="material-icons d-block">削除</i></button>
                                        </div>
                                    </form>
                                </h1>

                                <ul class="list-group list-group-flush mt-4">

                                    <li class="list-group-item d-inline-flex flex-wrap"><div><strong>カテゴリ名 : </strong></div><div>{{ $category->category }}</div></li>

                                </ul>
                                <div class="d-flex justify-content-end mt-3">
                                    <a class="btn btn-secondary d-inline-flex mr-3" href="{{route('category.index')}}">一覧へ戻る</a>
                                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
