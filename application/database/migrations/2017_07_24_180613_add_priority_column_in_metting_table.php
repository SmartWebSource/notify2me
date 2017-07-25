<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriorityColumnInMettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meeting', function (Blueprint $table) {
            $table->string('priority')->default('normal')->after('concern_person_designation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meeting', function (Blueprint $table) {
            $table->dropColumn('priority');
        });
    }
}
