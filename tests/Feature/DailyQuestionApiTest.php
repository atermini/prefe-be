<?php

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('a user can fetch todays question before answering', function () {
    $user = User::factory()->create();
    $question = Question::factory()->create([
        'option_a' => 'Mare',
        'option_b' => 'Montagna',
        'active_on' => today()->toDateString(),
    ]);

    Sanctum::actingAs($user);

    $this->getJson('/api/questions/today')
        ->assertSuccessful()
        ->assertJsonPath('data.id', $question->id)
        ->assertJsonPath('data.prompt', 'Preferiresti...')
        ->assertJsonPath('data.has_answered', false)
        ->assertJsonPath('data.results', null);
});

test('a user can answer todays question only once and then see the results', function () {
    $user = User::factory()->create();
    $question = Question::factory()->create([
        'option_a' => 'Caffe',
        'option_b' => 'Tè',
        'active_on' => today()->toDateString(),
    ]);

    Sanctum::actingAs($user);

    $this->postJson("/api/questions/{$question->id}/answer", [
        'selected_option' => 'A',
        'is_shared' => true,
    ])
        ->assertCreated()
        ->assertJsonPath('data.has_answered', true)
        ->assertJsonPath('data.answer.selected_option', 'A')
        ->assertJsonPath('data.results.total_answers', 1)
        ->assertJsonPath('data.results.option_a_count', 1);

    $this->postJson("/api/questions/{$question->id}/answer", [
        'selected_option' => 'B',
    ])->assertConflict();
});
