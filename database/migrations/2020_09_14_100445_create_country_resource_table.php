<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryResourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('country_resource', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('resource_id');
            $table->unsignedBigInteger('country_id');
             $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
             $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->timestamps();
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
        Schema::dropIfExists('country_resource');
    }
}
