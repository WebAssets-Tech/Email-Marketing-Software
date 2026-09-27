<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBccCcToCampaignsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('bcc')->nullable()->after('description');
            $table->string('cc')->nullable()->after('bcc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('campaigns', function (Blueprint $table) {
            //
        });
    }
}
