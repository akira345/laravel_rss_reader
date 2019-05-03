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

//RSS登録回りのテスト
class RssListTest extends TestCase
{
    //これでDBをすべて吹っ飛ばす
    use RefreshDatabase;

    public function recordRss()
    {
        //RSS登録へ遷移
        $response = $this->get('/rss/create');
        $response->assertStatus(200);

        $response = $this->post('/rss', [
            'rss_url'               =>'https://alas.aws.amazon.com/alas.rss',
            'comment'               => 'AmazonLinux',
            'category_id'           => '',
            'keywords'              => "critical\nmedium\nlow\nimportant",
            'ad_deny_flg'           => '1',//チェックボックスを入れた場合送信される値をセット。
            'deliv_flg'             => '1',//チェックボックスを入れた場合送信される値をセット。
            'repeat_deliv_deny_flg' => '1',//チェックボックスを入れた場合送信される値をセット。
            'rss_contents_list_cnt' => '10',
            'hidden_flg'            => '1',//チェックボックスを入れた場合送信される値をセット。
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/rss');
    }
    public function testRSS登録()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //RSS登録
        $this->recordRss();
        // 変更されたRSSデータが保存されていることを確認
        $this->assertSame('https://alas.aws.amazon.com/alas.rss', $user->rss_datas->where('id', '2')->fresh()[0]->rss_url);
        $this->assertSame('AmazonLinux', $user->rss_datas->where('id', '2')->fresh()[0]->comment);
        $this->assertSame(null, $user->rss_datas->where('id', '2')->fresh()[0]->category_id);
        $this->assertSame("critical\nmedium\nlow\nimportant", $user->rss_datas->where('id', '2')->fresh()[0]->keywords);
        $this->assertSame(true, $user->rss_datas->where('id', '2')->fresh()[0]->ad_deny_flg);

        //変更されたRSS配信属性が保存されていることを確認。rss_dataとは1:1
        $this->assertSame(true, $user->rss_datas->where('id','2')->fresh()[0]->rss_delivery_attribute->deliv_flg);
        $this->assertSame(true, $user->rss_datas->where('id','2')->fresh()[0]->rss_delivery_attribute->repeat_deliv_deny_flg);

        //変更された表示属性が保存されていることを確認。rss_dataとは1:1
        $this->assertSame(10, $user->rss_datas->where('id','2')->fresh()[0]->rss_view_attribute->rss_contents_list_cnt);
        $this->assertSame(true, $user->rss_datas->where('id','2')->fresh()[0]->rss_view_attribute->hidden_flg);

        //RSSへ遷移
        $response = $this->get('/rss');
        $response->assertStatus(200);
        //ビューの文字列チェック
        $response->assertSeeText('AmazonLinux');
        $response = $this->get('/rss/2');
        $response->assertStatus(200);
        //ビューの文字列チェック
        $response->assertSeeText('AmazonLinux');
        //メール送信
        $this->artisan('getrss')
            ->assertExitCode(0);
    }
    public function testRSS編集()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //RSS登録
        $this->recordRss();

        //RSS変更画面へ移動
        $response = $this->get('/rss/3/edit');
        $response->assertStatus(200);

        // RSS変更をリクエスト
        $response = $this->put('/rss/3', [
            'rss_url'               =>'https://alas.aws.amazon.com/alas.rss',
            'comment'               => 'AmazonLinux2',//modified
            'category_id'           => '',
            'keywords'              => "critical\nmedium\nlow\nimportant",
            //'ad_deny_flg'           => '0', //チェックボックスを外した場合値が送信されないので、値をセットしない。
            //'deliv_flg'             => '0',//チェックボックスを外した場合値が送信されないので、値をセットしない。
            'repeat_deliv_deny_flg' => '1',
            'rss_contents_list_cnt' => '20',
            //'hidden_flg'            => '0',//チェックボックスを外した場合値が送信されないので、値をセットしない。
        ]);
        // RSSへ遷移
        $response->assertStatus(302);
        $response->assertRedirect('/rss');

        // 変更されたRSSデータが保存されていることを確認
        $this->assertSame('AmazonLinux2', $user->rss_datas->where('id', '3')->fresh()[0]->comment);
        $this->assertSame(false, $user->rss_datas->where('id', '3')->fresh()[0]->ad_deny_flg);

        //変更されたRSS配信属性が保存されていることを確認。rss_dataとは1:1
        $this->assertSame(false, $user->rss_datas->where('id','3')->fresh()[0]->rss_delivery_attribute->deliv_flg);

        //変更された表示属性が保存されていることを確認。rss_dataとは1:1
        $this->assertSame(false, $user->rss_datas->where('id','3')->fresh()[0]->rss_view_attribute->hidden_flg);

    }
    public function testRSS削除()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //RSS登録
        $this->recordRss();

        //削除前にデータがあることを確認
        $this->assertDatabaseHas('rss_datas',['id' => 4]);
        $this->assertDatabaseHas('rss_view_attributes',['rss_id' => 4]);
        $this->assertDatabaseHas('rss_delivery_attributes',['rss_id' => 4]);
        //RSS削除画面へ移動
        $response = $this->delete('/rss/4');
        $response->assertStatus(302);
        $response->assertRedirect('/rss');

        //削除後データがないことを確認
        $this->assertDatabaseMissing('rss_datas',['id' => 4]);
        $this->assertDatabaseMissing('rss_view_attributes',['rss_id' => 4]);
        $this->assertDatabaseMissing('rss_delivery_attributes',['rss_id' => 4]);

    }
    public function testRSS二重登録()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //RSS登録へ遷移
        $this->recordRss();

        // 変更されたRSSデータが保存されていることを確認
        $this->assertSame('https://alas.aws.amazon.com/alas.rss', $user->rss_datas->where('id', '5')->fresh()[0]->rss_url);
        //RSS二重登録
        $response = $this->get('/rss/create');
        $response->assertStatus(200);

        $response = $this->post('/rss', [
            'rss_url'               =>'https://alas.aws.amazon.com/alas.rss',
            'comment'               => 'AmazonLinux',
            'category_id'           => '',
            'keywords'              => "critical\nmedium\nlow\nimportant",
            'ad_deny_flg'           => '1',//チェックボックスを入れた場合送信される値をセット。
            'deliv_flg'             => '1',//チェックボックスを入れた場合送信される値をセット。
            'repeat_deliv_deny_flg' => '1',//チェックボックスを入れた場合送信される値をセット。
            'rss_contents_list_cnt' => '10',
            'hidden_flg'            => '1',//チェックボックスを入れた場合送信される値をセット。
        ]);

        //戻される
        $response->assertStatus(302);
        $response->assertRedirect('/rss/create');

        // セッションにエラーを含むことを確認
        $response->assertSessionHasErrors(['rss_url']);

        // エラメッセージを確認
        $this->assertEquals('RSSフィードURL は既に存在します',
            session('errors')->first('rss_url'));
    }
    public function testRSS編集その２()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //RSS登録
        $this->recordRss();

        //RSS変更画面へ移動
        $response = $this->get('/rss/6/edit');
        $response->assertStatus(200);

        // RSS変更をリクエスト
        // いったん別URLにかえる。
        $response = $this->put('/rss/6', [
            'rss_url'               =>'https://example.com',
            'comment'               => 'example',//modified
            'category_id'           => '',
            'keywords'              => "critical\nmedium\nlow\nimportant",
            //'ad_deny_flg'           => '0', //チェックボックスを外した場合値が送信されないので、値をセットしない。
            //'deliv_flg'             => '0',//チェックボックスを外した場合値が送信されないので、値をセットしない。
            'repeat_deliv_deny_flg' => '1',
            'rss_contents_list_cnt' => '20',
            //'hidden_flg'            => '0',//チェックボックスを外した場合値が送信されないので、値をセットしない。
        ]);
        // RSSへ遷移
        $response->assertStatus(302);
        $response->assertRedirect('/rss');

        // 変更されたRSSデータが保存されていることを確認
        $this->assertSame('example', $user->rss_datas->where('id', '6')->fresh()[0]->comment);
        $this->assertSame(false, $user->rss_datas->where('id', '6')->fresh()[0]->ad_deny_flg);

        //変更されたRSS配信属性が保存されていることを確認。rss_dataとは1:1
        $this->assertSame(false, $user->rss_datas->where('id','6')->fresh()[0]->rss_delivery_attribute->deliv_flg);

        //変更された表示属性が保存されていることを確認。rss_dataとは1:1
        $this->assertSame(false, $user->rss_datas->where('id','6')->fresh()[0]->rss_view_attribute->hidden_flg);
        //RSS登録。ここは登録できる。
        $this->recordRss();
        //RSS変更画面へ移動
        $response = $this->get('/rss/6/edit');
        $response->assertStatus(200);
        // RSS変更をリクエスト
        // もとのURLに戻す。同一URLの二重登録チェックではねられる。
        $response = $this->put('/rss/6', [
            'rss_url'               =>'https://alas.aws.amazon.com/alas.rss',
            'comment'               => 'example',//modified
            'category_id'           => '',
            'keywords'              => "critical\nmedium\nlow\nimportant",
            //'ad_deny_flg'           => '0', //チェックボックスを外した場合値が送信されないので、値をセットしない。
            //'deliv_flg'             => '0',//チェックボックスを外した場合値が送信されないので、値をセットしない。
            'repeat_deliv_deny_flg' => '1',
            'rss_contents_list_cnt' => '20',
            //'hidden_flg'            => '0',//チェックボックスを外した場合値が送信されないので、値をセットしない。
        ]);
        // はねられる
        $response->assertStatus(302);
        $response->assertRedirect('/rss/6/edit');
        // セッションにエラーを含むことを確認
        $response->assertSessionHasErrors(['rss_url']);

        // エラメッセージを確認
        $this->assertEquals('RSSフィードURL は既に存在します',
            session('errors')->first('rss_url'));
    }
}
