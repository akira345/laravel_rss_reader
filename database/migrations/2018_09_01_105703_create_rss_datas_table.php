<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRssDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rss_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable(false)->comment('登録ユーザID');
            $table->string('rss_url',2000)->nullable(false)->comment('RSS URL');
            $table->string('comment',512)->nullable(false)->comment('コメント');
            $table->smallInteger('rss_contents_list_cnt')->nullable(false)->comment('RSS記事表示数');
            $table->integer('category_id')->nullable(true)->default(null)->comment('カテゴリID');
            $table->boolean('hidden_flg')->nullable(false)->comment('非表示フラグ');
            $table->boolean('deliv_flg')->nullable(false)->comment('メール配信フラグ');
            $table->text('keywords')->nullable(false)->comment('配信キーワード');
            $table->boolean('ad_deny_flg')->nullable(false)->comment('広告拒否フラグ');
            $table->boolean('repeat_deliv_deny_flg')->nullable(false)->comment('配送拒否フラグ');

            $table->timestamps();
            //外部キー制約
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('category_id')
                ->references('id')
                ->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rss_datas');
    }
}
