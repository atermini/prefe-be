<?php

namespace App\Models;

use Database\Factories\SubjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description'])]
class Subject extends Model
{
    /** @use HasFactory<SubjectFactory> */
    use HasFactory;

    public function promptLine(): string
    {
        $name = trim($this->name);
        $description = trim((string) $this->description);

        if ($description === '') {
            return $name;
        }

        return sprintf('%s (%s)', $name, $description);
    }
}
