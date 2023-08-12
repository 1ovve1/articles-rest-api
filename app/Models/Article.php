<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
    function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, Publication::class);
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
    function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return Builder
     */
    static function onlyActive(): Builder
    {
        return Article::where('active', true);
    }

    /**
     * @return void
     */
    function destroyWithPublication(): void
    {
        Publication::where('article_id', $this->id)->delete();
        $this->delete();
    }
}
