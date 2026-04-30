<?php

namespace App\Models;

use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'option_a', 'option_b', 'active_on'])]
class Question extends Model
{
    /** @use HasFactory<QuestionFactory> */
    use HasFactory;

    public static function introText(): string
    {
        return 'Preferiresti...';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function fullText(): string
    {
        $optionA = rtrim($this->option_a, " \t\n\r\0\x0B?.!");
        $optionB = rtrim($this->option_b, " \t\n\r\0\x0B?.!");

        return sprintf('%s %s oppure %s?', self::introText(), $optionA, $optionB);
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
