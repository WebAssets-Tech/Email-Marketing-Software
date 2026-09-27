<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatGPTMessagesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('chat_g_p_t_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->text('question');
            $table->longText('reply');
            $table->boolean('parent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('chat_g_p_t_messages');
    }
}
