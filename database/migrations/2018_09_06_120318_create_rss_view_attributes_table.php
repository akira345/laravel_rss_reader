<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRssViewAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rss_view_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rss_id')->nullable(false)->comment('RSS_ID');
            $table->smallInteger('rss_contents_list_cnt')->nullable(false)->comment('RSS記事表示数');
            $table->boolean('hidden_flg')->nullable(false)->comment('非表示フラグ');

            $table->timestamps();
            //外部キー制約
            $table->foreign('rss_id')
                ->references('id')
                ->on('rss_datas')
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
        Schema::dropIfExists('rss_view_attributes');
    }
}
