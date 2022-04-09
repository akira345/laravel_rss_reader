<?php

namespace Tests\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;

class SessionTimeOutTest extends DuskTestCase
{
    // DBマイグレーション
    //use DatabaseMigrations;
    /**
     * セッションタイムアウト時リダイレクトされるか？
     *
     * @return void
     */
    public function test()
    {
        // ユーザーを１つ作成
        $user = User::factory()->create([
            'password'  => bcrypt('test1111')
        ]);
        print_r($user);
        // ログイン
        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'test1111')
                    ->press('ログイン')
                    ->assertPathIs('/home');
            $browser->screenshot('login');
        });
        // カテゴリを登録
        $this->browse(function ($browser){
            $browser->visit('/category/create')
                    ->type('category', 'test');
            $browser->screenshot('entry');
            $browser->driver->manage()->deleteAllCookies();
            $browser->press('登録')
                    ->assertPathIs('/home');
            $browser->screenshot('exit');
        });
    }
}
