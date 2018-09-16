<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyRssDeliveryAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rss_delivery_attributes', function (Blueprint $table) {
            $table->unique('rss_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rss_delivery_attributes', function (Blueprint $table) {
            $table->dropUnique('rss_delivery_attributes_rss_id_unique');
        });
    }
}
