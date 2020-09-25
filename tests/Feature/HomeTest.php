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

//Home回りのテスト
class HomeTest extends TestCase
{
    //これでDBをすべて吹っ飛ばす
    use RefreshDatabase;
    public function recordRssCategory()
    {
        //RSSカテゴリ登録へ遷移
        $response = $this->get('/category/create');
        $response->assertStatus(200);

        $response = $this->post('/category', [
            'category'               => 'テストカテゴリ',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/category');
    }
    public function recordRss($category_id = null)
    {
        //RSS登録へ遷移
        $response = $this->get('/rss/create');
        $response->assertStatus(200);

        $response = $this->post('/rss', [
            'rss_url'               => 'https://alas.aws.amazon.com/alas.rss',
            'comment'               => 'AmazonLinux',
            'category_id'           => $category_id,
            'keywords'              => "critical\nmedium\nlow\nimportant",
            'ad_deny_flg'           => '1', //チェックボックスを入れた場合送信される値をセット。
            'deliv_flg'             => '1', //チェックボックスを入れた場合送信される値をセット。
            'repeat_deliv_deny_flg' => '1', //チェックボックスを入れた場合送信される値をセット。
            'rss_contents_list_cnt' => '10',
            //'hidden_flg'            => '0',//チェックボックスを入れた場合送信される値をセット。
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/rss');
    }
    public function test一覧表示()
    {
        // ユーザーを1つ作成
        $user = User::factory()->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //RSSカテゴリ登録
        $this->recordRssCategory();
        // RSSカテゴリデータが保存されていることを確認
        $this->assertSame('テストカテゴリ', $user->category_datas->where('id', '1')->fresh()[0]->category);

        //RSS登録
        $this->recordRss('1');
        //RSSデータが保存されていることを確認
        $this->assertSame(1, $user->rss_datas->where('id', '1')->fresh()[0]->category_id);

        //Homeへ飛ぶ
        $response = $this->get('/home');
        $response->assertStatus(200);

        //ビューの文字列チェック
        $response->assertSeeText('テストカテゴリ');
        $response->assertSeeText('AmazonLinux');
        //RSS表示
        $response = $this->get('/home/read/1');
        //ビューの文字列チェック
        $response->assertSeeText('ALAS');
        $response->assertSeeText('AmazonLinux 記事一覧');
    }
}
