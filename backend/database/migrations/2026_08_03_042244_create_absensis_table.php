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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkms')->cascadeOnDelete();
            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwals')->nullOnDelete();
            $table->date('tanggal');
            $table->dateTime('waktu_masuk')->nullable();
            $table->dateTime('waktu_pulang')->nullable();
            $table->decimal('latitude_masuk', 10, 7)->nullable();
            $table->decimal('longitude_masuk', 10, 7)->nullable();
            $table->decimal('latitude_pulang', 10, 7)->nullable();
            $table->decimal('longitude_pulang', 10, 7)->nullable();
            $table->string('foto_masuk')->nullable();
            $table->string('foto_pulang')->nullable();
            $table->enum('status', ['hadir', 'telat', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['karyawan_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
