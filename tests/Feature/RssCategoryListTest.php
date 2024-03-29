<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

//RSSカテゴリ登録回りのテスト
class RssCategoryListTest extends TestCase
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
    public function testRSSカテゴリ登録()
    {
        // ユーザーを1つ作成
        $user = User::factory()->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //RSSカテゴリ登録
        $this->recordRssCategory();
        // 変更されたRSSカテゴリデータが保存されていることを確認
        $this->assertSame('テストカテゴリ', $user->category_datas->where('id', '2')->fresh()[0]->category);

        //カテゴリへ遷移
        $response = $this->get('/category');
        $response->assertStatus(200);
        //ビューの文字列チェック
        $response->assertSeeText('テストカテゴリ');
        $response = $this->get('/category/2');
        $response->assertStatus(200);
        //ビューの文字列チェック
        $response->assertSeeText('テストカテゴリ');
    }
    public function testRSSカテゴリ編集()
    {
        // ユーザーを1つ作成
        $user = User::factory()->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //RSSカテゴリ登録
        $this->recordRssCategory();

        //RSSカテゴリ変更画面へ移動
        $response = $this->get('/category/3/edit');
        $response->assertStatus(200);

        // RSSカテゴリ変更をリクエスト
        $response = $this->put('/category/3', [
            'category'               => 'テスト２',
        ]);
        // RSSカテゴリへ遷移
        $response->assertStatus(302);
        $response->assertRedirect('/category');

        // 変更されたRSSカテゴリデータが保存されていることを確認
        $this->assertSame('テスト２', $user->category_datas->where('id', '3')->fresh()[0]->category);
    }
    public function testRSSカテゴリ削除()
    {
        // ユーザーを1つ作成
        $user = User::factory()->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //RSSカテゴリ登録
        $this->recordRssCategory();

        //削除前にデータがあることを確認
        $this->assertDatabaseHas('categories', ['id' => 4]);

        //RSSカテゴリ削除画面へ移動
        $response = $this->delete('/category/4');
        $response->assertStatus(302);
        $response->assertRedirect('/category');

        //削除後データがないことを確認
        $this->assertDatabaseMissing('categories', ['id' => 4]);
    }
    public function testRSSカテゴリ二重登録()
    {
        // ユーザーを1つ作成
        $user = User::factory()->create();

        // 認証済み、つまりログイン済みしたことにする
        $this->actingAs($user);

        //RSSカテゴリ登録へ遷移
        $this->recordRssCategory();

        // 変更されたRSSカテゴリデータが保存されていることを確認
        $this->assertSame('テストカテゴリ', $user->category_datas->where('id', '5')->fresh()[0]->category);
        //RSS二重登録
        $response = $this->get('/category/create');
        $response->assertStatus(200);

        $response = $this->post('/category', [
            'category'               => 'テストカテゴリ',
        ]);
        //戻される
        $response->assertStatus(302);

        // セッションにエラーを含むことを確認
        $response->assertSessionHasErrors(['category']);

        // エラメッセージを確認
        $this->assertEquals(
            'カテゴリ名 は既に存在します',
            session('errors')->first('category')
        );
    }
}
