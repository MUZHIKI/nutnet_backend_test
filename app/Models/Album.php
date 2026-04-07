<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artist',
        'description',
        'cover_url',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(AlbumLog::class)->latest();
    }
}
