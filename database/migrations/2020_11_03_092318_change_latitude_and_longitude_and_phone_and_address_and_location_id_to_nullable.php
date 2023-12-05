<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLatitudeAndLongitudeAndPhoneAndAddressAndLocationIdToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('health_centers', function (Blueprint $table) {
            $table->string('latitude')->nullable()->change();
            $table->string('longitude')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->unsignedBigInteger('location_id')->nullable()->change();
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
        Schema::table('health_centers', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            Schema::table('health_centers', function (Blueprint $table) {
                $table->string('latitude');
                $table->string('longitude');
                $table->string('phone')->unique();
                $table->string('address');
                $table->unsignedBigInteger('location_id');
            });
        });
    }
}
