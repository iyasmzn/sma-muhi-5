<?php

namespace App\Models;

use Database\Factories\SpmbRegistrationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpmbRegistration extends Model
{
    /** @use HasFactory<SpmbRegistrationFactory> */
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'birth_date',
        'birth_place',
        'previous_school',
        'previous_school_city',
        'address',
        'jalur',
        'parent_name',
        'parent_phone',
        'notes',
        'status',
        'verified_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'verified_at' => 'datetime',
    ];

    // ── Jalur options ────────────────────────────────────────────

    /** @return array<string, string> */
    public static function jalurOptions(): array
    {
        return [
            'zonasi' => 'Zonasi',
            'prestasi' => 'Prestasi',
            'afirmasi' => 'Afirmasi',
            'mutasi' => 'Mutasi',
        ];
    }

    /** @return array<string, string> */
    public static function statusOptions(): array
    {
        return [
            'pending' => 'Menunggu',
            'verified' => 'Terverifikasi',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
        ];
    }

    // ── Scopes ──────────────────────────────────────────────────

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', 'accepted');
    }
}
