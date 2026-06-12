<?php

namespace App\Models;

use Database\Factories\AlumniFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    /** @use HasFactory<AlumniFactory> */
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'full_name',
        'nickname',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'major',
        'graduation_year',
        'certificate_number',
        'instagram',
        'twitter',
        'facebook',
        'youtube',
        'occupation',
        'entered_ptn',
        'ptn_name',
    ];

    /**
     * Mirror the migration default so an array-built model behaves like a saved one.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'entered_ptn' => false,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'graduation_year' => 'integer',
            'entered_ptn' => 'boolean',
        ];
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeEnteredPtn(Builder $query): Builder
    {
        return $query->where('entered_ptn', true);
    }
}
