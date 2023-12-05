<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThreeColumnsIntoNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->after('is_completed');
            $table->integer('total_notification_users')->default(0)->after('country_id');
            $table->integer('total_push_notification_recipients')->default(0)->after('total_notification_users');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign('notifications_country_id_foreign');
            $table->dropColumn('country_id', 'total_notification_users', 'total_push_notification_recipients');
        });
    }
}
