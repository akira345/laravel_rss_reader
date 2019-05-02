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

class ModifyUserTest extends TestCase
{
   //これでDBをすべて吹っ飛ばす
   use RefreshDatabase;

   public function testユーザ情報修正()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();
        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

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
}
