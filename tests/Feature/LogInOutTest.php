<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Auth;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Notification;
use App\Notifications\CustomPasswordReset;

//ログイン、ログアウト、パスワードリセット、ログイン履歴テスト
class LogInOutTest extends TestCase
{
    //これでDBをすべて吹っ飛ばす
    use RefreshDatabase;

    public function testログイン失敗()
    {
        // ユーザーを１つ作成
        $user = User::factory()->create([
            'password'  => bcrypt('test1111')
        ]);

        // まだ、認証されていないことを確認
        $this->assertFalse(Auth::check());

        // 異なるパスワードでログインを実行
        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'test2222'
        ]);

        // 認証失敗で、認証されていないことを確認
        $this->assertFalse(Auth::check());

        // セッションにエラーを含むことを確認
        $response->assertSessionHasErrors(['email']);

        // エラメッセージを確認
        $this->assertEquals(
            '認証に失敗しました',
            session('errors')->first('email')
        );
    }

    public function testログイン成功()
    {
        // ユーザーを１つ作成
        $user = User::factory()->create([
            'password'  => bcrypt('test1111')
        ]);

        // まだ、認証されていない
        $this->assertFalse(Auth::check());

        // ログインを実行
        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'test1111'
        ]);

        // 認証されている
        $this->assertTrue(Auth::check());

        // ログイン後にホームページにリダイレクトされるのを確認
        $response->assertRedirect('/home');
    }

    public function testログアウト()
    {
        // ユーザーを１つ作成
        $user = User::factory()->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        // 認証されていることを確認
        $this->assertTrue(Auth::check());

        // ログアウトを実行
        $response = $this->post('/logout');

        // 認証されていない
        $this->assertFalse(Auth::check());

        // Welcomeページにリダイレクトすることを確認
        $response->assertRedirect('/');
    }

    public function testログインしていないのにログアウト()
    {
        // ログアウトを実行
        $response = $this->get('/logout');

        // 認証されていない
        $this->assertFalse(Auth::check());

        // Welcomeページにリダイレクトすることを確認
        $response->assertRedirect('/');
    }

    public function testログイン試行ブロック()
    {
        //５回失敗でロック
        for ($i = 0; $i < 6; $i++) {
            // 異なるパスワードでログインを実行
            $response = $this->post('/login', [
                'email'    => 'hoge@exsample.com',
                'password' => 'password'
            ]);

            // 認証失敗で、認証されていないことを確認
            $this->assertFalse(Auth::check());

            // セッションにエラーを含むことを確認
            $response->assertSessionHasErrors(['email']);

            if ($i < 5) {
                // エラメッセージを確認
                $this->assertEquals(
                    '認証に失敗しました',
                    session('errors')->first('email')
                );
            } else {
                // エラメッセージを確認
                $this->assertMatchesRegularExpression(
                    '/[0-9]+ 秒以上開けて再度お試しください/',
                    session('errors')->first('email')
                );
            }
        }
    }

    public function testログイン履歴()
    {
        $pass = 'password';
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
        $response = $this->get('/user/history');
        $response->assertStatus(200);
        //ビューの文字列チェック
        $response->assertSeeText('ログイン履歴');
        $response->assertSeeText('ユーザ登録完了');
        $response->assertSeeText('ログイン');
    }

    public function testパスワードリセット()
    {
        // ユーザーを1つ作成
        $user = User::factory()->create();

        //パスワードリセット画面表示
        $response = $this->get('/password/reset');
        $response->assertStatus(200);

        // パスワードリセットをリクエスト
        $response = $this->from('/password/email')->post('/password/email', [
            'email' => $user->email,
        ]);

        // 同画面にリダイレクト
        $response->assertStatus(302);
        $response->assertRedirect('/password/email');
        // 成功のメッセージ
        $response->assertSessionHas(
            'status',
            'パスワードリセットリンクが電子メールで送信されました'
        );
    }

    public function from(string $url)
    {
        session()->setPreviousUrl(url($url));
        return $this;
    }

    public function testリセット失敗()
    {
        // ユーザーを1つ作成
        $user = User::factory()->create();

        //パスワードリセット画面表示
        $response = $this->get('/password/reset');
        $response->assertStatus(200);

        // 存在しないユーザーのメールアドレスでパスワードリセットをリクエスト
        $response = $this->from('/password/email')->post('/password/email', [
            'email' => 'nobody@example.com'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/password/email');
        // セッションにエラーを含むことを確認
        $response->assertSessionHasErrors(['email']);

        // エラメッセージを確認
        $this->assertEquals(
            'ユーザーは存在しません',
            session('errors')->first('email')
        );
    }

    public function testパスワードリセット可能か()
    {
        Notification::fake();
        Notification::assertNothingSent();

        // ユーザーを1つ作成
        $user = User::factory()->create();

        //パスワードリセット画面表示
        $response = $this->get('/password/reset');
        $response->assertStatus(200);

        // パスワードリセットをリクエスト
        $response = $this->post('/password/email', [
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
        $response = $this->get('/password/reset/' . $token);
        $response->assertStatus(200);

        // パスワードをリセット
        $new = 'reset1111';
        $response = $this->post('/password/reset', [
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
