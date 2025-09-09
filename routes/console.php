<?php

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    $files = Storage::disk('public')->files('callLogsExport');

    $oldFiles = collect($files)
        ->filter(fn($file) => Carbon::createFromTimestamp(Storage::disk('public')->lastModified($file))->diffInMinutes(Carbon::now()) > 15)
        ->all();

    if (!empty($oldFiles)) {
        logger('Info', ['oldFilesPath' => $oldFiles]);
        Storage::disk('public')->delete($oldFiles);
    }
})->everyMinute();
