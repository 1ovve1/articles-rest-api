<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'title', 'content', 'image_path', 'active'
    ];

    /**
     * @return BelongsToMany
     */
    function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, Publication::class);
    }

    /**
     * @return BelongsToMany
     */
    function rubrics(): BelongsToMany
    {
        return $this->belongsToMany(Rubric::class, Publication::class);
    }

    /**
     * Search models by slug field
     *
     * @return string
     */
    function getRouteKey(): string
    {
        return 'slug';
    }
}
