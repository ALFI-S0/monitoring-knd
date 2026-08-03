<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan foreign key departemen_id
            $table->foreignId('departemen_id')
                  ->nullable()
                  ->after('email')
                  ->constrained('departemens')
                  ->nullOnDelete(); // Jika master departemen dihapus, user tidak ikut terhapus
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['departemen_id']);
            $table->dropColumn('departemen_id');
        });
    }
};