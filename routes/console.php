<?php

use App\Console\Commands\GenerateDailyQuestionCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(GenerateDailyQuestionCommand::class)
    ->dailyAt('00:00')
    ->timezone('Europe/Rome')
    ->withoutOverlapping(30)
    ->onOneServer();
