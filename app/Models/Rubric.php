<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rubric extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'active'
    ];

    /**
     * @return BelongsToMany
     */
    function publications(): BelongsToMany
    {
        return $this->belongsToMany(Publication::class);
    }

    /**
     * @return BelongsToMany
     */
    function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, Publication::class);
    }
}
