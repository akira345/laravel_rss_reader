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

class LoginHistoryTest extends TestCase
{
    //これでDBをすべて吹っ飛ばす
    use RefreshDatabase;

    public function testログイン履歴()
    {
        $pass='password';
        $response = $this->post('/register', [
            'name'                  => 'hoge',
            'email'                 => 'hoge@exsample.com',
            'password'              => $pass,
            'password_confirmation' => $pass
        ]);
        // ホームへ遷移
        $response->assertStatus(302);
        $response->assertRedirect('/home');

        // 認証されていることを確認
        $this->assertTrue(Auth::check());
        //ログイン履歴へ遷移
        $response = $this->get('user/history');
        $response->assertStatus(200);
        //ビューの文字列チェック
        $response->assertSeeText('ログイン履歴');
        $response->assertSeeText('ユーザ登録完了');
        $response->assertSeeText('ログイン');
    }

}
