<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShceduleEmailStatus extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('schedule_emails', function (Blueprint $table) {
            //Add a new option to status
            DB::statement("ALTER TABLE schedule_emails CHANGE COLUMN status status ENUM('PENDING', 'QUEUED', 'SENT')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }
}
