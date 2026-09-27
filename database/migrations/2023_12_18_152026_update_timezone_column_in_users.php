<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTimezoneColumnInUsers extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            $file = public_path('timezone.json');
            if (File::exists($file)) {
                $timezones = file_get_contents($file);
                $timezones = json_decode($timezones, true);
                $timezones = array_keys($timezones);
                $table->enum('timezone', $timezones)
                    ->after('user_type')
                    ->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
