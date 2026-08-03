<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi secara massal (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'departemen_id',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi (misal: response JSON/API).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe data yang di-cast otomatis oleh Eloquent.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* =========================================================================
     | RELASI DATABASE
     | ========================================================================= */

    /**
     * Relasi Ke Model Departemen (Many-to-One / BelongsTo)
     */
public function departemen(): BelongsTo
{
    return $this->belongsTo(Departemen::class, 'departemen_id');
}

    /* =========================================================================
     | HELPER / UTILITY METHODS
     | ========================================================================= */

    /**
     * Helper untuk mengecek departemen user berdasarkan kode atau nama departemen.
     * Contoh penggunaan: auth()->user()->isDepartemen('EDP')
     */
    public function isDepartemen(string $kodeOrNama): bool
    {
        if (!$this->departemen) {
            return false;
        }

        $search = strtolower($kodeOrNama);

        return strtolower($this->departemen->kode_departemen) === $search ||
               strtolower($this->departemen->nama_departemen) === $search;
    }
}