<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Storage::disk('public')->delete(
        collect(Storage::disk('public')->files('callLogsExport'))
            ->filter(fn($file) => now()->diffInMinutes(Storage::disk('public')->lastModified($file)) > 15)
            ->all()
    );
})->everyMinute();
