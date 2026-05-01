<?php

use App\Actions\GenerateDailyQuestion;
use App\Ai\Agents\DailyWouldYouRatherAgent;
use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\DailyQuestionPublishedNotification;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('explicit subjects are injected into the prompt sent to the agent', function () {
    User::factory()->create();

    $subjects = collect([
        Subject::factory()->create([
            'name' => 'Mario Rossi',
            'description' => 'amico fissato col padel',
        ]),
        Subject::factory()->create([
            'name' => 'Papa Francesco',
            'description' => null,
        ]),
    ]);

    DailyWouldYouRatherAgent::fake([
        [
            'option_a' => 'giocare a padel con Mario per otto ore di fila',
            'option_b' => 'confessarti settimanalmente con Papa Francesco',
        ],
    ]);

    Notification::fake();

    (new GenerateDailyQuestion)->handle(
        date: CarbonImmutable::parse('2026-05-02'),
        subjects: $subjects,
    );

    DailyWouldYouRatherAgent::assertPrompted(
        fn ($prompt): bool => str_contains($prompt->prompt, 'Soggetti da coinvolgere')
            && str_contains($prompt->prompt, 'Mario Rossi (amico fissato col padel)')
            && str_contains($prompt->prompt, 'Papa Francesco')
    );

    expect(Question::query()->count())->toBe(1);
});

test('an empty subjects collection produces a prompt without the subjects block', function () {
    User::factory()->create();

    DailyWouldYouRatherAgent::fake([
        [
            'option_a' => 'opzione a senza soggetti',
            'option_b' => 'opzione b senza soggetti',
        ],
    ]);

    Notification::fake();

    (new GenerateDailyQuestion)->handle(
        date: CarbonImmutable::parse('2026-05-03'),
        subjects: collect(),
    );

    DailyWouldYouRatherAgent::assertPrompted(
        fn ($prompt): bool => ! str_contains($prompt->prompt, 'Soggetti da coinvolgere')
    );
});

test('automatic selection returns an empty collection when no subjects exist', function () {
    expect((new GenerateDailyQuestion)->selectSubjectsForPrompt())->toHaveCount(0);
});

test('automatic selection never returns more than the configured maximum', function () {
    Subject::factory()->count(10)->create();

    $action = new GenerateDailyQuestion;

    foreach (range(1, 20) as $_) {
        expect($action->selectSubjectsForPrompt()->count())->toBeLessThanOrEqual(3);
    }
});

test('the action notifies users when generating with subjects', function () {
    User::factory()->count(2)->create();

    $subjects = collect([
        Subject::factory()->create(['name' => 'Tizio', 'description' => 'amico storico']),
    ]);

    DailyWouldYouRatherAgent::fake([
        [
            'option_a' => 'passare un weekend con Tizio',
            'option_b' => 'rinunciare per sempre al caffè',
        ],
    ]);

    Notification::fake();

    (new GenerateDailyQuestion)->handle(
        date: CarbonImmutable::parse('2026-05-04'),
        subjects: $subjects,
    );

    Notification::assertSentTo(
        User::query()->get(),
        DailyQuestionPublishedNotification::class,
    );
});

test('subject promptLine returns name only when description is empty', function () {
    $subject = new Subject(['name' => 'Solo nome', 'description' => null]);

    expect($subject->promptLine())->toBe('Solo nome');
});

test('subject promptLine includes description in parentheses when present', function () {
    $subject = new Subject(['name' => 'Nome', 'description' => '  contesto utile  ']);

    expect($subject->promptLine())->toBe('Nome (contesto utile)');
});
