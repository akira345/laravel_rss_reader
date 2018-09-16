<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyRssViewAttributes2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rss_view_attributes', function (Blueprint $table) {
            $table->smallInteger('rss_contents_list_cnt')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rss_view_attributes', function (Blueprint $table) {
            $table->smallInteger('rss_contents_list_cnt')->default(NULL)->change();
        });
    }
}
