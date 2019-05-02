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


class LogInTest extends TestCase
{
    //これでDBをすべて吹っ飛ばす
    use RefreshDatabase;

    public function testログイン失敗()
    {
        // ユーザーを１つ作成
        $user = factory(User::class)->create([
            'password'  => bcrypt('test1111')
        ]);

        // まだ、認証されていないことを確認
        $this->assertFalse(Auth::check());

        // 異なるパスワードでログインを実行
        $response = $this->post('login', [
            'email'    => $user->email,
            'password' => 'test2222'
        ]);

        // 認証失敗で、認証されていないことを確認
        $this->assertFalse(Auth::check());

        // セッションにエラーを含むことを確認
        $response->assertSessionHasErrors(['email']);

        // エラメッセージを確認
        $this->assertEquals('認証に失敗しました',
            session('errors')->first('email'));
    }

    public function testログイン成功()
    {
        // ユーザーを１つ作成
        $user = factory(User::class)->create([
            'password'  => bcrypt('test1111')
        ]);

        // まだ、認証されていない
        $this->assertFalse(Auth::check());

        // ログインを実行
        $response = $this->post('login', [
            'email'    => $user->email,
            'password' => 'test1111'
        ]);

        // 認証されている
        $this->assertTrue(Auth::check());

        // ログイン後にホームページにリダイレクトされるのを確認
        $response->assertRedirect('home');
    }
}
