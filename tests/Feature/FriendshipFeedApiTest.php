<?php

use App\Models\Answer;
use App\Models\Friendship;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('accepted friends can see shared answers in their feed', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
    ]);
    $friend = User::factory()->create([
        'name' => 'Giulia',
        'email' => 'giulia@example.com',
    ]);
    $question = Question::factory()->create([
        'option_a' => 'Pizza',
        'option_b' => 'Sushi',
        'active_on' => today()->toDateString(),
    ]);

    Sanctum::actingAs($user);

    $storeResponse = $this->postJson('/api/friendships', [
        'friend_email' => $friend->email,
    ]);

    $storeResponse->assertCreated();

    $friendshipId = $storeResponse->json('data.id');

    Sanctum::actingAs($friend);

    $this->patchJson("/api/friendships/{$friendshipId}", [
        'status' => Friendship::STATUS_ACCEPTED,
    ])->assertSuccessful();

    Answer::factory()->shared()->create([
        'question_id' => $question->id,
        'user_id' => $friend->id,
        'selected_option' => 'B',
    ]);

    Sanctum::actingAs($user);

    $this->getJson('/api/feed')
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.user.name', 'Giulia')
        ->assertJsonPath('data.0.selected_option', 'B')
        ->assertJsonPath('data.0.question.prompt', 'Preferiresti...');
});
