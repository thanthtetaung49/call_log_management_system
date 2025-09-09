<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
   $files = Storage::disk('public')->files('callLogsExport');

    $oldFiles = collect($files)
        ->filter(fn($file) => now()->diffInMinutes(Carbon::createFromTimestamp(Storage::disk('public')->lastModified($file))) > 1)
        ->all();

    if (!empty($oldFiles)) {
        Storage::disk('public')->delete($oldFiles);
    }
})->everyMinute();
