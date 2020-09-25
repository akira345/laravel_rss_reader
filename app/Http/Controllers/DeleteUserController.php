<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RedirectIfAuthenticated;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\DeleteUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class DeleteUserController extends Controller
{
    public function __construct()
    {
        //認証ページ
        $this->middleware('auth');
    }

    public function showDeleteUserFrom()
    {

        return view('auth.delete');
    }
    public function deleteUser(Request $request)
    {
        // 確認画面でキャンセルボタンが押された場合、自身にPostしているので戻れないので、
        //TOPへ飛ばす
        if ($request->get('action') === 'back') {
            //
            return redirect()->route('login');
        }
        //ユーザ削除

        if ($request->get('action') === 'delete') {
            event(new DeleteUser(Auth::user()));
            DB::beginTransaction();
            try {
                User::where('id', Auth::user()->id)
                    ->delete();
                DB::commit();
            } catch (\PDOException $e) {
                DB::rollBack();
                Log::error('ユーザ削除時にエラー', ['user:' => Auth::user()->id, 'exception' => $e->getMessage()]);
                return redirect()->route('delete_user_from')->with('alert', 'ユーザ削除に失敗しました。');
            }
            //ログアウトさせ、ログイン画面表示
            Auth::logout();
            return redirect()->route('login');
        }
    }
}
