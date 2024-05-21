<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->foreignId('chat_id')->constrained()->onDelete(null);
            $table->text('message');
            $table->string('status')->default('unread');
            $table->timestamp('read_at')->nullable();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete(null);
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete(null);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
