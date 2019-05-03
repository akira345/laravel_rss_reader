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

//RSSカテゴリ登録回りのテスト
class RssCategolyListTest extends TestCase
{
    //これでDBをすべて吹っ飛ばす
    use RefreshDatabase;

    public function recordRssCategory()
    {
        //RSSカテゴリ登録へ遷移
        $response = $this->get('category/create');
        $response->assertStatus(200);

        $response = $this->post('category', [
            'category'               =>'テストカテゴリ',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('category');
    }
    public function testRSSカテゴリ登録()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //RSSカテゴリ登録
        $this->recordRssCategory();
        // 変更されたRSSカテゴリデータが保存されていることを確認
        $this->assertSame('テストカテゴリ', $user->category_datas->where('id', '1')->fresh()[0]->category);
    }
    public function testRSSカテゴリ編集()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //RSSカテゴリ登録
        $this->recordRssCategory();

        //RSSカテゴリ変更画面へ移動
        $response = $this->get('category/2/edit');
        $response->assertStatus(200);

        // RSSカテゴリ変更をリクエスト
        $response = $this->put('category/2', [
            'category'               =>'テスト２',
        ]);
        // RSSカテゴリへ遷移
        $response->assertStatus(302);
        $response->assertRedirect('category');

        // 変更されたRSSカテゴリデータが保存されていることを確認
        $this->assertSame('テスト２', $user->category_datas->where('id', '2')->fresh()[0]->category);
    }
    public function testRSSカテゴリ削除()
    {
        // ユーザーを1つ作成
        $user = factory(User::class)->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //RSSカテゴリ登録
        $this->recordRssCategory();

        //削除前にデータがあることを確認
        $this->assertDatabaseHas('categories',['id' => 3]);

        //RSSカテゴリ削除画面へ移動
        $response = $this->delete('category/3');
        $response->assertStatus(302);
        $response->assertRedirect('category');

        //削除後データがないことを確認
        $this->assertDatabaseMissing('categories',['id' => 3]);

    }
}
