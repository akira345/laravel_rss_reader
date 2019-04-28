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


class ResetPasswordTest extends TestCase
{
    //これでDBをすべて吹っ飛ばす
    use RefreshDatabase;

    //パスワードリセット画面表示
    public function testパスワードリセット画面アクセス()
    {
        $response = $this->get('password/reset');
        $response->assertStatus(200);
    }

    public function testパスワードリセット()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();

       // パスワードリセットをリクエスト
       $response = $this->from('password/email')->post('password/email', [
           'email' => $user->email,
       ]);

       // 同画面にリダイレクト
       $response->assertStatus(302);
       $response->assertRedirect('password/email');
       // 成功のメッセージ
       $response->assertSessionHas('status',
           'パスワードリセットリンクが電子メールで送信されました');
    }

    public function from(string $url)
    {
        session()->setPreviousUrl(url($url));
        return $this;
    }

    public function testリセット失敗()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();

       // 存在しないユーザーのメールアドレスでパスワードリセットをリクエスト
       $response = $this->from('password/email')->post('password/email', [
           'email' => 'nobody@example.com'
       ]);

       $response->assertStatus(302);
       $response->assertRedirect('password/email');
      // セッションにエラーを含むことを確認
      $response->assertSessionHasErrors(['email']);

      // エラメッセージを確認
      $this->assertEquals('ユーザーは存在しません',
          session('errors')->first('email'));

    }

    public function testログアウト()
    {
       // ユーザーを１つ作成
       $user = factory(User::class)->create();

       // 認証済み、つまりログイン済みしたことにする
       $this->actingAs($user);

       // 認証されていることを確認
       $this->assertTrue(Auth::check());

       // ログアウトを実行
       $response = $this->post('logout');

       // 認証されていない
       $this->assertFalse(Auth::check());

       // Welcomeページにリダイレクトすることを確認
       $response->assertRedirect('/');
    }

    public function testパスワードリセット可能か()
    {
        Notification::fake();
        Notification::assertNothingSent();

        // ユーザーを1つ作成
        $user = factory(User::class)->create();

        // パスワードリセットをリクエスト
        $response = $this->post('password/email', [
           'email' => $user->email
        ]);

       // トークンを取得

       $token = '';

       Notification::assertSentTo(
           $user,
           CustomPasswordReset::class,
           function ($notification, $channels) use ($user, &$token) {
               $token = $notification->token;
               return true;
           }
       );

       // パスワードリセットの画面へ
       $response = $this->get('password/reset/'.$token);

       $response->assertStatus(200);

       // パスワードをリセット

       $new = 'reset1111';

       $response = $this->post('password/reset', [
           'email'                 => $user->email,
           'token'                 => $token,
           'password'              => $new,
           'password_confirmation' => $new
       ]);

       // ホームへ遷移
       $response->assertStatus(302);
       $response->assertRedirect('/home');
       // リセット成功のメッセージ
       $response->assertSessionHas('status', 'パスワードがリセットされました');

       // 認証されていることを確認
       $this->assertTrue(Auth::check());

       // 変更されたパスワードが保存されていることを確認
       $this->assertTrue(Hash::check($new, $user->fresh()->password));
    }

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
