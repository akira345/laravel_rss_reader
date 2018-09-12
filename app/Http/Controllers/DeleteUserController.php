<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RedirectIfAuthenticated;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\DeleteUser;
use Illuminate\Support\Facades\Redirect;

class DeleteUserController extends Controller
{
    public function __construct()
    {
        //認証ページ
        $this->middleware('auth');
    }

    public function showDeleteUserFrom(){

        return view('auth.delete');
    }
    public function deleteUser(Request $request){
        // 確認画面でキャンセルボタンが押された場合、自身にPostしているので戻れないので、
        //TOPへ飛ばす
        if ($request->get('action') === 'back') {
            //
            return redirect()->route('login');
        }
        //ユーザ削除


        event(new DeleteUser(Auth::user()));
        User::where('id',Auth::user()->id)
            ->delete();

        //ログアウトさせ、ログイン画面表示
        Auth::logout();
        return redirect()->route('login');
    }

}
