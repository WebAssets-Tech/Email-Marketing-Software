<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Clue\StreamFilter\remove;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        addForeignKey('sms_services', 'sms_sender_ids', 'id', 'sms_service_id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        removeForeignKey('sms_sender_ids', 'sms_service_id');
    }
};
