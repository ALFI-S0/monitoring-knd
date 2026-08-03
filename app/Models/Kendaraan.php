<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $fillable = [

        'no_polisi',

        'merk',

        'tipe',

        'tahun',

        'warna',

        'kilometer',

        'status',

        'tanggal_servis',

        'foto',

        'keterangan'

    ];

    public function perbaikans()
{
    return $this->hasMany(Perbaikan::class);
}
}