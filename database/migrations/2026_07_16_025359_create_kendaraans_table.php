<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraans', function (Blueprint $table) {

            $table->id();

            $table->string('no_polisi')->unique();

            $table->string('merk');

            $table->string('tipe');

            $table->year('tahun');

            $table->string('warna');

            $table->integer('kilometer')->default(0);

            $table->enum('status', [
                'Ready',
                'Dipakai',
                'Perbaikan',
                'Servis'
            ])->default('Ready');

            $table->date('tanggal_servis')->nullable();

            $table->string('foto')->nullable();

            $table->text('keterangan')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};