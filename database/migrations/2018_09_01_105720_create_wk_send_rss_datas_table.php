<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWkSendRssDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wk_send_rss_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable(false)->comment('登録ユーザID');
            $table->integer('rss_id')->nullable(false)->comment('RSS ID');
            $table->longText('title')->nullable(false)->comment('配信済みRSSタイトル');
            $table->timestamps();

            //外部キー制約
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wk_send_rss_datas');
    }
}
