<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsInRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn(['status','status_message']);
            $table->string('email_status',10)->default('pending')->after('email_payload');
            $table->string('email_status_message')->nullable()->after('email_status');
            $table->string('sms_status',10)->default('pending')->after('sms_payload');
            $table->string('sms_status_message')->nullable()->after('sms_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn([
                'email_status',
                'email_status_message',
                'sms_status',
                'sms_status_message'
            ]);
        });
    }
}
