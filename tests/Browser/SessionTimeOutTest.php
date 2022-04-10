<?php

namespace Tests\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;

class SessionTimeOutTest extends DuskTestCase
{
    // DBマイグレーション
    use DatabaseMigrations;
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

        // ログイン
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'test1111')
                    ->screenshot('beforeLogin')
                    ->click('button[type="submit"]')
                    ->assertPathIs('/home');
            $browser->screenshot('login');
        });
        // カテゴリを登録
        $this->browse(function (Browser $browser){
            $browser->visit('/category/create')
                    ->type('category', 'test');
            $browser->screenshot('entry');
            $browser->driver->manage()->deleteAllCookies();
            $browser->press('登録')
                    ->assertPathIs('/login');
                    $browser->screenshot('exit');
        });
    }
}
