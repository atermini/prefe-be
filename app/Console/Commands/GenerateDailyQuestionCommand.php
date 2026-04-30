<?php

namespace App\Console\Commands;

use App\Actions\GenerateDailyQuestion;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Throwable;

class GenerateDailyQuestionCommand extends Command
{
    protected $signature = 'questions:generate-daily {--date=} {--force}';

    protected $description = 'Generate the daily question using the xAI agent and notify all users.';

    public function handle(GenerateDailyQuestion $generateDailyQuestion): int
    {
        $date = $this->option('date')
            ? CarbonImmutable::parse($this->option('date'), 'Europe/Rome')
            : null;

        try {
            $question = $generateDailyQuestion->handle(
                date: $date,
                force: (bool) $this->option('force'),
            );
        } catch (Throwable $throwable) {
            report($throwable);

            $this->error($throwable->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf(
            'Daily question ready for %s: %s',
            $question->active_on?->toDateString(),
            $question->fullText(),
        ));

        return self::SUCCESS;
    }
}
