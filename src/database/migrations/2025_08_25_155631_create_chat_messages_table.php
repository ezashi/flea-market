<?php
// src/database/migrations/2025_08_25_155631_create_chat_messages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->string('image_path')->nullable();
            $table->enum('message_type', ['text', 'both'])->default('text');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_edited')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->index(['item_id', 'created_at']);
            $table->index(['sender_id', 'is_read']);
            $table->index(['item_id', 'is_deleted']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
}