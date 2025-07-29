<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OsisCandidate extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_ketua',
        'name_wakil',
        'photo_ketua',
        'photo_wakil',
        'vision',
        'mission',
    ];

    /**
     * Mendefinisikan relasi polimorfik ke model Vote.
     * Satu kandidat OSIS bisa memiliki banyak suara.
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'candidate');
    }
}
