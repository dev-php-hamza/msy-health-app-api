<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('key_contact_email')->unique();
            $table->string('phone');
            $table->string('address');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('location_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('branches');
    }
}
