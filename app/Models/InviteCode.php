<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['code', 'max_uses', 'uses_count'])]
class InviteCode extends Model
{
    public static function generate(int $maxUses): self
    {
        return self::create([
            'code' => strtoupper(Str::random(4).'-'.Str::random(4)),
            'max_uses' => $maxUses,
        ]);
    }

    public function isExhausted(): bool
    {
        return $this->uses_count >= $this->max_uses;
    }

    public function remaining(): int
    {
        return max(0, $this->max_uses - $this->uses_count);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->whereColumn('uses_count', '<', 'max_uses');
    }
}
