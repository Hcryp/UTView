<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manpowers', function (Blueprint $table) {
            $table->id();
            $table->string('site')->index();
            $table->string('category');
            $table->string('company');
            $table->string('nrp')->nullable();
            $table->string('name');
            $table->string('department')->nullable();
            $table->string('role')->nullable();
            $table->date('join_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('effective_days')->default(0);
            $table->decimal('manhours', 10, 2)->default(0);
            $table->date('date_out')->nullable();
            $table->string('out_reason')->nullable();
            $table->string('status')->default('ACTIVE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manpowers');
    }
};