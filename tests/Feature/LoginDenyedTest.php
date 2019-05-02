<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Auth;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Notification;
use App\Auth\Notifications\ResetPassword;
use App\Notifications\CustomPasswordReset;


class LoginDenyedTest extends TestCase
{
    public function testログイン試行ブロック()
    {
        //５回失敗でロック
        for ($i=0; $i<6; $i++) {
            // 異なるパスワードでログインを実行
            $response = $this->post('login', [
                'email'    => 'hoge@exsample.com',
                'password' => 'password'
            ]);

            // 認証失敗で、認証されていないことを確認
            $this->assertFalse(Auth::check());

            // セッションにエラーを含むことを確認
            $response->assertSessionHasErrors(['email']);

            if($i<5){
                // エラメッセージを確認
                $this->assertEquals('認証に失敗しました',
                    session('errors')->first('email'));
            }else{
                // エラメッセージを確認
                $this->assertEquals('60 秒以上開けて再度お試しください',
                    session('errors')->first('email'));
            }
        }
    }
}
