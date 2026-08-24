<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Autopilot Weekly Tournament Scheduler
Schedule::command('tournament:schedule-weekly')->weeklyOn(5, '00:00');
Schedule::command('tournament:schedule-weekly')->weeklyOn(0, '23:59');
