<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('active_manpowers', function (Blueprint $table) {
            $table->id();
            $table->string('site')->nullable();
            $table->string('worker_category')->nullable();
            $table->string('company')->nullable();
            $table->string('nrp')->nullable();
            $table->string('name')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->string('start_contract')->nullable();
            $table->string('end_contract')->nullable();
            $table->integer('effective_days')->default(0);
            $table->unsignedBigInteger('manhours')->default(0);
            $table->timestamps();
        });

        Schema::create('inactive_manpowers', function (Blueprint $table) {
            $table->id();
            $table->string('site')->nullable();
            $table->string('worker_category')->nullable();
            $table->string('company')->nullable();
            $table->string('nrp')->nullable();
            $table->string('name')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->string('date_out')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inactive_manpowers');
        Schema::dropIfExists('active_manpowers');
    }
};