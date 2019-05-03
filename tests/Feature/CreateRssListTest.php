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

class CreateRssListTest extends TestCase
{
    //これでDBをすべて吹っ飛ばす
    use RefreshDatabase;

    public function recordRss()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //RSS登録へ遷移
        $response = $this->get('rss/create');
        $response->assertStatus(200);

        $response = $this->post('rss', [
            'rss_url'               =>'https://alas.aws.amazon.com/alas.rss',
            'comment'               => 'AmazonLinux',
            'category_id'           => '',
            'keywords'              => "critical\nmedium\nlow\nimportant",
            'ad_deny_flg'           => '1',
            'deliv_flg'             => '1',
            'repeat_deliv_deny_flg' => '1',
            'rss_contents_list_cnt' => '10',
            'hidden_flg'            => '1'
        ]);
        $response->assertStatus(302);

    }
    public function testRSS登録()
    {
        $this->recordRss();

        $this->artisan('getrss')
        ->assertExitCode(0);

    }
}
