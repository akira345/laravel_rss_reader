<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyRssDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rss_datas', function (Blueprint $table) {
            //テーブルを分割するので項目削除
            $table->dropColumn('rss_contents_list_cnt');
            $table->dropColumn('hidden_flg');
            $table->dropColumn('deliv_flg');
            $table->dropColumn('repeat_deliv_deny_flg');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //ロールバック時項目を追加する。
        Schema::table('rss_datas', function (Blueprint $table) {
            $table->smallInteger('rss_contents_list_cnt')->nullable(false)->comment('RSS記事表示数');
            $table->boolean('hidden_flg')->nullable(false)->comment('非表示フラグ');
            $table->boolean('deliv_flg')->nullable(false)->comment('メール配信フラグ');
            $table->boolean('repeat_deliv_deny_flg')->nullable(false)->comment('配送拒否フラグ');
        });
    }
}
