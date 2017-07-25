
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('meeting_id');
            $table->string('timezone');
            $table->timestamp('trigger_at')->nullable();
            $table->longtext('email_payload')->nullable();
            $table->longtext('sms_payload')->nullable();
            $table->string('status')->default('pending');
            $table->string('status_message')->nullable();
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
        Schema::drop('reminders');
    }
}
