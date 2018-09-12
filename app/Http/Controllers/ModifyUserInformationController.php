<?php

namespace App\Http\Controllers;

use App\Events\ModifyUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ModifyUserInformationController extends Controller
{
    /**
     * ユーザ情報変更画面初期表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showModifyUserInformationFrom(){
        $user = Auth::user();
        $user_name = $user->name;
        $user_email = $user->email;
        $user_passwd = "";

        return view('auth.modify',['name' => $user_name,'email' => $user_email,'password' => $user_passwd]);
    }

    /**
     * ModifyUserInformationController constructor.
     */
    public function __construct()
    {
        //認証ページ
        $this->middleware('auth');
    }

    /**
     * ユーザ情報変更
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modifyUserInformation(Request $request){
        $user = Auth::user();
        $hash_password = $user->password;
        //$user_name = $user->username;
        $user_email = $user->email;

        //ユーザ名チェック
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ])->validate();
        $user_name = $request->name;

        //メアドチェック
        if($request->email){
            if($user_email <> $request->email) {
                Validator::make($request->all(), [
                    'email' => 'required|string|email|max:255|unique:users',
                ])->validate();
                $user_email = $request->email;
            }

        }
        //パスワードチェック
        if($request->password){
            Validator::make($request->all(), [
                'password' => 'required|string|min:6|confirmed',
            ])->validate();
            $hash_password = Hash::make($request->password);
        }
        //ユーザテーブルに変更
        //イベントを発生される
        event(new ModifyUser($this->updateUser([
           'name' => $user_name,
           'email' => $user_email,
           'hash_password' => $hash_password,
        ])));

        //ログアウトさせ、ログイン画面表示
        Auth::logout();
        return redirect()->route('login');
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function updateUser(Array $data){
        User::where('id',Auth::user()->id)
            ->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['hash_password'],
            ]);
        //イベントへ渡すためにUserを返す
        return Auth::user();
    }
}
