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

//ユーザ情報関連テスト
class UserTest extends TestCase
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
        //Topへ遷移
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
        //Topへ遷移
        $response->assertStatus(302);
        $response->assertRedirect('/');

        // セッションにエラーを含むことを確認
        $response->assertSessionHasErrors(['password']);

        // エラメッセージを確認
        $this->assertEquals('パスワード を確認用と一致させてください',
            session('errors')->first('password'));
    }
    public function testユーザ情報修正()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();
        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);
        //ユーザ変更画面へ移動
        $response = $this->get('user/modify');
        $response->assertStatus(200);
        // ユーザ変更をリクエスト
        $pass='p@ssword';
        $name='test';
        $email='test@exsample.com';
        $response = $this->post('user/modify', [
            'name'                  => $name,
            'email'                 => $email,
            'password'              => $pass,
            'password_confirmation' => $pass
        ]);
        // ログインへ遷移
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        // 認証されていないことを確認
        $this->assertFalse(Auth::check());

        // 変更されたユーザが保存されていることを確認
        $this->assertSame($name, $user->fresh()->name);
        //変更されたメアドが保存されていることを確認
        $this->assertSame($email, $user->fresh()->email);
        //変更されたパスワードが保存されていることを確認
        $this->assertTrue(Hash::check($pass, $user->fresh()->password));

    }
    public function testユーザ削除()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //ユーザ削除画面へ移動
        $response = $this->get('user/delete');
        $response->assertStatus(200);
        // ユーザ削除をリクエスト
        $response = $this->post('user/delete', [
            'action' => 'delete',
        ]);

        // 認証されていない
        $this->assertFalse(Auth::check());

        // Welcomeページにリダイレクトすることを確認
        $response->assertRedirect('/login');
    }

    public function testユーザ削除キャンセル()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();
        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);
        // ユーザ削除をリクエストするがキャンセル
        $response = $this->post('user/delete', [
            'action' => 'back',
        ]);

        // 認証されている
        $this->assertTrue(Auth::check());

        // Welcomeページにリダイレクトすることを確認
        $response->assertRedirect('/login');
    }

}

