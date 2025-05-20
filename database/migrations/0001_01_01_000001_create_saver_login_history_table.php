<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saver_login_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('ip_address');
            $table->text('user_agent');
            $table->string('location')->nullable();
            $table->timestamp('login_at');
            $table->timestamp('logout_at')->nullable();
            $table->enum('status', ['success', 'failed', 'blocked']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('saver_users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_history');
    }
};
