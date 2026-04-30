<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect(range(0, 6))->each(function (int $offset): void {
            Question::factory()->create([
                'active_on' => today()->addDays($offset)->toDateString(),
            ]);
        });
    }
}
