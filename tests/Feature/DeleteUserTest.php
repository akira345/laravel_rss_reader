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


class DeleteUserTest extends TestCase
{
    //これでDBをすべて吹っ飛ばす
    use RefreshDatabase;

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
