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
        // ユーザ登録
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/register')
                    ->type('name', 'test@example.com')
                    ->type('email','test@example.com')
                    ->type('password', 'test1111')
                    ->type('password_confirmation','test1111')
                    ->screenshot('beforeLogin')
                    ->click('@register_button')
                    ->screenshot('afterLogin')
                    ->assertPathIs('/home');
            $browser->screenshot('login');
        });
/*
        // ログイン
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'test1111')
                    ->screenshot('beforeLogin')
                    ->press('login_button')
                    ->screenshot('afterLogin')
                    ->assertPathIs('/home');
            $browser->screenshot('login');
        });
*/
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
