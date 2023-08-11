<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'active'
    ];

    /**
     * Return user entity of current author
     *
     * @return BelongsTo
     */
    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Find all publications of current author
     *
     */
    function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    /**
     * Return all articles of current user
     *
     * @return BelongsToMany
     */
    function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, Publication::class);
    }
}
