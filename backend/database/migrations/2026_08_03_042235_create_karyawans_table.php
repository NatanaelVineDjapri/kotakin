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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkms')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nip', 30)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->date('tanggal_bergabung')->nullable();
            $table->string('foto')->nullable();
            $table->text('face_id_encoding')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'resign'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('user_id');
            $table->unique(['umkm_id', 'nip']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
