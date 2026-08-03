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
        Schema::create('perbaikans', function (Blueprint $table) {

            $table->id();

            // Relasi ke tabel kendaraan
            $table->foreignId('kendaraan_id')
                ->constrained('kendaraans')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Tanggal masuk bengkel
            $table->date('tanggal_perbaikan');

            // Kendala kendaraan
            $table->text('kendala');

            // Tindakan yang dilakukan
            $table->text('tindakan')->nullable();

            // Nama bengkel

            // Estimasi selesai
            $table->dateTime('estimasi_selesai')->nullable();
            // Tanggal selesai
            $table->dateTime('tanggal_selesai')->nullable();


            // Catatan tambahan
            $table->text('catatan')->nullable();

            // Status perbaikan
            $table->enum('status', [
                'Proses',
                'Selesai'
            ])->default('Proses');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbaikans');
    }
};