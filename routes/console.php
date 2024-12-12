<?php

declare(strict_types=1);


use Illuminate\Support\Facades\Artisan;

Artisan::command('app:execute-recurring-transfer')->dailyAt('02:00');