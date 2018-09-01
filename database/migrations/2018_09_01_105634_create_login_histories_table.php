<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable(false)->comment('登録ユーザID');
            $table->string('memo')->nullable(false)->comment('備考');
            $table->ipAddress('ipaddr')->nullable(false)->comment('アクセス元IPアドレス');
            $table->longText('user_agent')->nullable(false)->comment('ユーザエージェント');
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
        Schema::dropIfExists('login_histories');
    }
}
