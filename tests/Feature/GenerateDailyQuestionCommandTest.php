<?php

use App\Ai\Agents\DailyWouldYouRatherAgent;
use App\Models\Question;
use App\Models\User;
use App\Notifications\DailyQuestionPublishedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('the daily question command generates a question and notifies all users', function () {
    User::factory()->count(2)->create();

    DailyWouldYouRatherAgent::fake([
        [
            'option_a' => 'dover brindare con il succo ACE a ogni discorso serio',
            'option_b' => 'sentire soltanto applausi lenti ogni volta che entri in una stanza',
        ],
    ]);

    Notification::fake();

    $this->artisan('questions:generate-daily', [
        '--date' => '2026-05-01',
    ])->assertSuccessful();

    $question = Question::query()->first();

    expect($question)->not->toBeNull();
    expect($question->fullText())->toStartWith('Preferiresti...');
    expect($question->active_on?->toDateString())->toBe('2026-05-01');

    Notification::assertSentTo(
        User::query()->get(),
        DailyQuestionPublishedNotification::class,
    );
});

test('the daily question command does not create duplicates for the same day', function () {
    Question::factory()->create([
        'active_on' => '2026-05-01',
    ]);

    DailyWouldYouRatherAgent::fake()->preventStrayPrompts();
    Notification::fake();

    $this->artisan('questions:generate-daily', [
        '--date' => '2026-05-01',
    ])->assertSuccessful();

    expect(Question::query()->count())->toBe(1);

    Notification::assertNothingSent();
    DailyWouldYouRatherAgent::assertNeverPrompted();
});
