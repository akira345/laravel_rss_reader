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

}
