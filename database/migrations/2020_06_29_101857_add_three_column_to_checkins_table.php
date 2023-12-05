<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThreeColumnToCheckinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checkins', function (Blueprint $table) {
            $table->string('s1_question')->nullable()->after('user_checkin_long');
            $table->boolean('additional_help')->nullable()->after('s1_question');
            $table->text('additional_feedback')->nullable()->after('additional_help');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checkins', function (Blueprint $table) {
            $table->dropColumn('s1_question', 'additional_help', 'additional_feedback');
        });
    }
}
