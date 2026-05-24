<?php

namespace App\Models;

use Database\Factories\TeacherFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    /** @use HasFactory<TeacherFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 'nip', 'position', 'subject',
        'education', 'phone', 'email', 'whatsapp',
        'photo', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * URL foto guru — storage jika ada, fallback ke generated avatar.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/'.$this->photo);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=d97706&color=fff&size=300&bold=true';
    }

    /**
     * @return Builder<static>
     */
    public static function active(): Builder
    {
        return static::where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }
}
