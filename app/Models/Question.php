<?php

namespace App\Models;

use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['prompt', 'option_a', 'option_b', 'active_on'])]
class Question extends Model
{
    /** @use HasFactory<QuestionFactory> */
    use HasFactory;

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('active_on', $date);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active_on' => 'date',
        ];
    }
}
