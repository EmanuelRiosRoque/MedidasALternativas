<?php

use Livewire\Volt\Volt;
use App\Livewire\Convenio;
use App\Livewire\Calendario;
use App\Livewire\Facilitadores;
use App\Livewire\Facilitadores\Lista;
use Illuminate\Support\Facades\Route;
use App\Livewire\Solicitud\VerPersonas;

use App\Http\Controllers\DOCs\DOCxController;
use App\Http\Controllers\PDFs\PDFsController;
use App\Livewire\CalendarJs;
use App\Livewire\Solicitud\SolicitudDetalle;
use App\Livewire\Solicitudes\MediacionesLista;
use App\Livewire\Solicitudes\SolicitudesLista;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // === Settings ===
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile',    'settings.profile')->name('settings.profile');
    Volt::route('settings/password',   'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');


    // === Solicitud ===
    Route::prefix('solicitud')->name('solicitud.')->group(function () {
        Route::get('/create', Convenio::class)->name('create');
        Route::get('/list', SolicitudesLista::class)->name('list');
        Route::get('/{solicitudId}', SolicitudDetalle::class)->name('show');
        Route::get('/{solicitudId}/personas', VerPersonas::class)->name('personas');
    });

    Route::prefix('mediacion')->name('mediacion.')->group(function () {
        Route::get('/list', MediacionesLista::class)->name('list');     
    });

    // === Facilitadiores ===
    Route::prefix('facilitadores')->name('facilitadores.')->group(function () {
        Route::get('/create', Facilitadores::class)->name('create');
        Route::get('/list', Lista::class)->name('list');
    });

    // === Calendario ===
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', CalendarJs::class)->name('index');
        Route::get('/reasignacion/{solicitudID?}',  CalendarJs::class)->name("reasignacion");
    });

    // === Documentos ===
    Route::get('/descargar-correoMexico/{id}'   , [PDFsController::class, 'correoMexico'  ])->name('descargar-correoMexico');
    Route::get('/descargar-servicioPostal/{id}' , [PDFsController::class, 'servicioPostal'])->name('descargar-servicioPostal');
    Route::get('/descargar-amparoRepre' , [PDFsController::class, 'amparoRepre'  ])->name('descargar-amparoRepre');
    Route::get('/descargar-amparo'      , [PDFsController::class, 'amparo'       ])->name('descargar-amparo');
    Route::get('/manifestacion/{id}'    , [PDFsController::class, 'manifestacion'])->name('manifestacion.download');
    Route::get('/seguimiento/{fecha}'   , [PDFsController::class, 'seguimiento'  ])->name('seguimiento.download');
    Route::get('/sobre-sepomex/{id}'    , [PDFsController::class, 'sobreSepomex' ])->name('sobreSepomex.download');
    Route::get('/sobre-personal/{id}'   , [PDFsController::class, 'sobrePersonal'])->name('sobrePersonal.download');
    Route::get('/invitacion/1/{id}'     , [PDFsController::class, 'invitacionUno'])->name('invitacionUno.download');
    Route::get('/invitacion/2/{id}'     , [PDFsController::class, 'invitacionDos'])->name('invitacionDos.download');
});




require __DIR__.'/auth.php';
