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
            $table->string('site')->index(); // e.g., 'SATUI', 'BATULICIN'
            $table->string('category'); // e.g., 'KARYAWAN', 'KONTRAKTOR', 'MAGANG'
            $table->string('company'); // e.g., 'PT UNITED TRACTORS TBK'
            $table->string('nrp')->nullable();
            $table->string('name');
            $table->string('role')->nullable(); // Jabatan
            $table->date('join_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('manhours', 10, 2)->default(0); // Sum this for Manhours
            $table->string('status')->default('ACTIVE'); // ACTIVE, RESIGN, MUTASI
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manpowers');
    }
};