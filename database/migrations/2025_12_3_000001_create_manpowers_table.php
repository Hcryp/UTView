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
            
            // Kolom existing yang penting untuk sistem
            $table->string('site')->index(); // Tetap diperlukan untuk filter Satui/Batulicin
            $table->string('category');      // KARYAWAN, KONTRAKTOR, MAGANG
            
            // Kolom Audit (Sesuai Request)
            $table->string('company');       // PERUSAHAAN
            $table->string('nrp')->nullable(); // NRP
            $table->string('name');          // NAMA
            $table->string('department')->nullable(); // DEPARTEMEN (Baru)
            $table->string('role')->nullable();       // JABATAN
            
            $table->date('join_date')->nullable();    // MULAI KONTRAK
            $table->date('end_date')->nullable();     // AKHIR KONTRAK
            
            $table->integer('effective_days')->default(0); // HARI KERJA EFEKTIF (Baru)
            $table->decimal('manhours', 10, 2)->default(0); // MANHOURS
            
            $table->date('date_out')->nullable();     // TANGGAL OUT (Baru)
            $table->string('out_reason')->nullable(); // KETERANGAN PEKERJA OUT (Baru)
            
            // Status sistem (Active/Resign/Mutasi) - bisa diisi otomatis dari out_reason jika perlu
            $table->string('status')->default('ACTIVE'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manpowers');
    }
};