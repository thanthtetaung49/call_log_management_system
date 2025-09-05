<?php

use App\Livewire\CallLogManagement;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\User;
use App\Livewire\UserReviewPage;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard')
    ->can('userAccess', ModelsUser::class);

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('users/review', UserReviewPage::class)->name('userReview');

    Route::get('users', User::class)->name('userManagement');
    Route::get('callLog', CallLogManagement::class)->name('callLogManagement');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
