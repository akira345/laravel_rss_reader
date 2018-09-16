@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Delete User') }}</div>

                <div class="card-body">
                    @if (session('alert'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('alert') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('delete_user') }}" aria-label="{{ __('Delete User') }}">
                        @csrf

                        ユーザを削除します。すべてのデータが消去されます。復活はできません。<br>
                        本当に削除しますか？


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-warning" name="action" value="delete">
                                    ユーザ削除
                                </button>
                                <button type="submit" class="btn btn-primary"  name="action" value="back" >
                                    キャンセル
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
