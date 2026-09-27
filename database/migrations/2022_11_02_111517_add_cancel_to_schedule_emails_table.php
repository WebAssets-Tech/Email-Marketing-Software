<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddCancelToScheduleEmailsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement("ALTER TABLE `schedule_emails` CHANGE `status` `status` ENUM('PENDING', 'SENT', 'CANCEL')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('schedule_emails', function (Blueprint $table) {
            //
        });
    }
}
