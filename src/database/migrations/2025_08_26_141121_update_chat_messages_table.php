<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
        $table->text('message')->nullable(false)->change();

        $table->boolean('is_edited')->default(false);
        $table->boolean('is_deleted')->default(false);
        $table->timestamp('edited_at')->nullable();
        $table->timestamp('deleted_at')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn(['is_edited', 'is_deleted', 'edited_at', 'deleted_at']);
        });
    }
}