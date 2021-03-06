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
                            <span class="d-inline-block">カテゴリ登録</span>
                        </h1>
                        <div class="row">
                            <div class="col-md-12">
                                <form method="POST" action="{{route('category.store')}}" accept-charset="UTF-8" class="needs-validation" novalidate>
                                    @csrf

                                    <div class="form-group">
                                        <label for="category-field">カテゴリ名</label>

                                        <input id="category-field" type="text" class="form-control{{ $errors->has('category') ? ' is-invalid' : '' }}"  name="category" value="{{ old('category') }}" required autofocus>

                                        @if ($errors->has('category'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('category') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <a class="btn btn-secondary d-inline-flex mr-3" href="{{route('category.index')}}">一覧へ戻る</a>
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
