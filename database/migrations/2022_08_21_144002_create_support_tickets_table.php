<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportTicketsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('subject')->nullable();
            $table->longText('ticket_no')->nullable();
            $table->longText('name')->nullable();
            $table->longText('email')->nullable();
            $table->longText('phone_number')->nullable();
            $table->longText('desc')->nullable();
            $table->unsignedBigInteger('assinged_to')->nullable();
            $table->boolean('mark_as_read')->nullable();
            $table->boolean('important')->nullable();
            $table->longText('priority')->nullable();
            $table->boolean('solved')->nullable();
            $table->unsignedBigInteger('solved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('support_tickets');
    }
}
