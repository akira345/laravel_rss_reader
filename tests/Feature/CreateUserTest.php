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


class CreateUserTest extends TestCase
{
    //これでDBをすべて吹っ飛ばす
    use RefreshDatabase;

    public function testユーザ登録()
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
    }

    public function testユーザ登録パスワード短い()
    {
       $pass='12345';
       $response = $this->post('/register', [
           'name'                  => 'hoge',
           'email'                 => 'hoge@exsample.com',
           'password'              => $pass,
           'password_confirmation' => $pass
       ]);
        // 認証失敗で、認証されていないことを確認
        $this->assertFalse(Auth::check());

       $response->assertStatus(302);
       $response->assertRedirect('/');

      // セッションにエラーを含むことを確認
      $response->assertSessionHasErrors(['password']);

      // エラメッセージを確認
      $this->assertEquals('パスワード は 6 文字以上のみ有効です',
          session('errors')->first('password'));
    }

    public function testユーザ登録パスワード間違い()
    {
       $pass='123456789';
       $pass2='987654321';
       $response = $this->post('/register', [
           'name'                  => 'hoge',
           'email'                 => 'hoge@exsample.com',
           'password'              => $pass,
           'password_confirmation' => $pass2
       ]);
        // 認証失敗で、認証されていないことを確認
        $this->assertFalse(Auth::check());

       $response->assertStatus(302);
       $response->assertRedirect('/');

      // セッションにエラーを含むことを確認
      $response->assertSessionHasErrors(['password']);

      // エラメッセージを確認
      $this->assertEquals('パスワード を確認用と一致させてください',
          session('errors')->first('password'));
    }
}

