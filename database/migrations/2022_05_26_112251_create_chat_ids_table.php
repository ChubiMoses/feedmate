<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_ids', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('receiver_id');
            $table->integer('visible');
            $table->string('type');
            $table->timestamp('last_chat');
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
        Schema::dropIfExists('chat_ids');
    }
}
