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

class CommandTest extends TestCase
{
    public function testコマンド実行()
    {
        //ユーザ登録、RSS登録した後で実行。RFC違反メールのテスト
        $this->artisan('getrss')
            ->assertExitCode(0);
    }
}
