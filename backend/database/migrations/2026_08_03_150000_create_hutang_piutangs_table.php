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
        Schema::create('hutang_piutangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkms')->cascadeOnDelete();
            $table->enum('jenis', ['hutang', 'piutang']);
            $table->string('nama_pihak', 150); // nama supplier atau customer
            $table->decimal('jumlah', 12, 2);
            $table->date('jatuh_tempo')->nullable();
            $table->enum('status', ['belum_lunas', 'lunas', 'sebagian'])->default('belum_lunas');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['umkm_id', 'jenis', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutang_piutangs');
    }
};
