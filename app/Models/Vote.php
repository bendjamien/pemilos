<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Vote extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'candidate_id',
        'candidate_type',
    ];

    /**
     * Mendapatkan model induk (OsisCandidate atau MpkCandidate)
     * dari sebuah suara.
     */
    public function candidate(): MorphTo
    {
        return $this->morphTo();
    }
}
