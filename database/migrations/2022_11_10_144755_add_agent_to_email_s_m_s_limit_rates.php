<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgentToEmailSMSLimitRates extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('email_s_m_s_limit_rates', function (Blueprint $table) {
            $table->string('agent')->after('sms')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('email_s_m_s_limit_rates', function (Blueprint $table) {
            //
        });
    }
}
