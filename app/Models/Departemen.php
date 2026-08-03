<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departemen extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_departemen',
        'nama_departemen',
    ];

    /**
     * Relasi One-to-Many ke Model User
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'departemen_id');
    }
}