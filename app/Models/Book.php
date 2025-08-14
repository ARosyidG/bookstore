<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'author_id', 'category_id'];
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
    public function summary(): HasOne
    {
        return $this->hasOne(BookSummary::class);
    }
    public function getAvgRatingAttribute(): float
    {
        return $this->summary?->avg_rating ?? 0.0;
    }
    public function getVotersCountAttribute(): int
    {
        return $this->summary?->voters_count ?? 0;
    }
}
