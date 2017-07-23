<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('title');
            $table->longtext('details');
            $table->timestamp('next_meeting_date');
            $table->string('concern_person_name')->nullable();
            $table->string('concern_person_phone')->nullable();
            $table->string('concern_person_designation')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('meeting');
    }
}
