<?php

use App\Livewire\Convenio;
use App\Livewire\Facilitadores;
use App\Livewire\PreMediacion;
use App\Livewire\Solictud\Index;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::get('/convenio/form', Convenio::class)->name('convenio.index');
Route::get('/convenio/edit/{id}', Convenio::class)->name('convenio.edit');
Route::get('/facilitadores/form', Facilitadores::class)->name('facilitadores.index');
Route::get('/solicitudes', PreMediacion::class)->name('solicitudes.index');
Route::get('/solicitud', Index::class)->name('solicitud.index');

require __DIR__.'/auth.php';
