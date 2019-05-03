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

class LogOutTest extends TestCase
{
    //これでDBをすべて吹っ飛ばす
    use RefreshDatabase;

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

    public function testログインしていないのにログアウト()
    {
        // ログアウトを実行
        $response = $this->get('logout');

        // 認証されていない
        $this->assertFalse(Auth::check());

        // Welcomeページにリダイレクトすることを確認
        $response->assertRedirect('/');
    }
}
