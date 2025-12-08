<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manpower_logs', function (Blueprint $table) {
            $table->id();
            $table->date('log_date')->unique();
            $table->json('content');
            $table->integer('total_mp')->default(0);
            $table->decimal('total_mh', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manpower_logs');
    }
};