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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkms')->cascadeOnDelete();
            $table->foreignId('kasir_id')->constrained('users')->cascadeOnDelete();
            $table->string('kode_transaksi', 50);
            $table->decimal('total', 12, 2);
            $table->enum('metode_pembayaran', ['tunai', 'qris', 'transfer', 'debit']);
            $table->dateTime('tanggal');
            $table->timestamps();

            $table->unique(['umkm_id', 'kode_transaksi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
