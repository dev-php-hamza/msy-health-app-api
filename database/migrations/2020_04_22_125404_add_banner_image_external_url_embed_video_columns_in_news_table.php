<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBannerImageExternalUrlEmbedVideoColumnsInNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('image');
            $table->text('external_url')->nullable()->after('banner_image');
            $table->text('embeded_video')->nullable()->after('external_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['banner_image', 'external_url', 'embeded_video']);
        });
    }
}
