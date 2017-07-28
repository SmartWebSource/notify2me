<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('type',20)->default('personal');
            $table->string('title');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('location')->nullable();
            $table->longtext('description')->nullable();
            $table->string('color',10)->default('#3ED715');
            $table->string('concern_person_name')->nullable();
            $table->string('concern_person_phone')->nullable();
            $table->string('concern_person_designation')->nullable();
            $table->string('priority')->default('normal');
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
        Schema::drop('events');
    }
}
