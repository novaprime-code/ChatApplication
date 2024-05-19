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
        Schema::create('video_calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('initiator_id');
            $table->unsignedBigInteger('recipient_id');
            $table->string('status');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('initiator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_calls');
    }
};
