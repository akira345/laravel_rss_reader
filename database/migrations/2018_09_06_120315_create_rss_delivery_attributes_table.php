<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRssDeliveryAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rss_delivery_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rss_id')->nullable(false)->comment('RSS_ID');
            $table->boolean('deliv_flg')->nullable(false)->comment('メール配信フラグ');
            $table->boolean('repeat_deliv_deny_flg')->nullable(false)->comment('再配送拒否フラグ');

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
        Schema::dropIfExists('rss_delivery_attributes');
    }
}
