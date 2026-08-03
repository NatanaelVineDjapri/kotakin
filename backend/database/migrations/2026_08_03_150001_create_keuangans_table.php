<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel ini mencatat arus kas UMKM di luar transaksi POS.
     * Laporan arus kas total = transaksis (POS/pemasukan)
     *                        + keuangans (pemasukan & pengeluaran umum)
     *                        + gajis (pengeluaran, saat status_pembayaran = 'dibayar')
     */
    public function up(): void
    {
        Schema::create('keuangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkms')->cascadeOnDelete();
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->string('kategori', 100); // misal: sewa, listrik, gaji, lainnya
            $table->decimal('jumlah', 12, 2);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['umkm_id', 'tanggal']);
            $table->index(['umkm_id', 'jenis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangans');
    }
};
