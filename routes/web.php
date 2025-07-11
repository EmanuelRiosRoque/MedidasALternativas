<?php

use App\Http\Controllers\PDFs\PDFsController;
use Livewire\Volt\Volt;
use App\Livewire\Convenio;
use App\Livewire\Calendario;
use App\Livewire\Facilitadores;
use Illuminate\Support\Facades\Route;
use App\Livewire\Solicitud\VerPersonas;
use App\Livewire\Solicitud\Index as SolicitudIndex;
use App\Livewire\Solicitudes\Index as SolicitudesIndex;

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

    Route::get('/solicitudes/table', SolicitudesIndex::class)->name('solicitudes.index');
    
    Route::get('/solicitud/{solicitudId}', SolicitudIndex::class)->name('solicitud.index');
    Route::get('/solicitud/{solicitudId}/personas', VerPersonas::class)->name('personas.update');

    Route::get('/calendario', Calendario::class)->name("calendario.index");

    //**Documentos */
    Route::get('/descargar-amparo', [PDFsController::class, 'amparo'])->name('descargar-amparo');
    Route::get('/descargar-correoMexico', [PDFsController::class, 'correoMexico'])->name('descargar-correoMexico');
    Route::get('/descargar-servicioPostal', [PDFsController::class, 'servicioPostal'])->name('descargar-servicioPostal');

});


require __DIR__.'/auth.php';
