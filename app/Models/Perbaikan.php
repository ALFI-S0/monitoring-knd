<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;

    protected $table = 'perbaikans';

    protected $fillable = [
        'kendaraan_id',
        'tanggal_perbaikan',
        'kendala',
        'tindakan',
        'estimasi_selesai',
        'tanggal_selesai',
        'catatan',
        'status',
        // 'bengkel', // Tambahkan ini jika di migration Anda ada kolom bengkel
        // 'biaya',   // Tambahkan ini jika di migration Anda ada kolom biaya
    ];

    /**
     * Relasi ke model Kendaraan
     */
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}