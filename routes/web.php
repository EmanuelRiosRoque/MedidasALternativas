<?php

use Livewire\Volt\Volt;
use App\Livewire\Convenio;
use App\Livewire\Calendario;
use App\Livewire\Solicitudes;
use App\Livewire\Facilitadores;
use App\Livewire\Solicitud\Index;
use App\Livewire\Solicitud\VerPersonas;
use Illuminate\Support\Facades\Route;

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
    
    Route::get('/convenio/form', Convenio::class)->name('convenio.index');
    Route::get('/convenio/edit/{id}', Convenio::class)->name('convenio.edit');
    Route::get('/facilitadores/form', Facilitadores::class)->name('facilitadores.index');
    Route::get('/solicitudes/table', Solicitudes::class)->name('solicitudes.table');
    
    Route::get('/solicitud/{solicitudId}', Index::class)->name('solicitud.index');
    Route::get('/solicitud/{solicitudId}/personas', VerPersonas::class)->name('personas.update');

    Route::get('/calendario', Calendario::class)->name("calendario.index");

});


require __DIR__.'/auth.php';
