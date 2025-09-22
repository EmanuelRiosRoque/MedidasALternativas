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
use App\Livewire\Solicitudes\SolicitudesLista;

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
    Route::get('/facilitadores/list', Lista::class)->name('facilitadores.list');

    Route::get('/solicitudes/table', SolicitudesLista::class)->name('solicitudes.lista');
    
    Route::get('/solicitud/{solicitudId}', SolicitudDetalle::class)->name('solicitud.detalle');
    Route::get('/solicitud/{solicitudId}/personas', VerPersonas::class)->name('personas.update');

    Route::get('/calendario', CalendarJs::class)->name("calendario.index");
    Route::get('/reasignacion/{solicitudID?}', CalendarJs::class)->name("reasignacion.index");

    //**Documentos */
    Route::get('/descargar-correoMexico/{id}'   , [PDFsController::class, 'correoMexico'])->name('descargar-correoMexico');
    Route::get('/descargar-servicioPostal/{id}' , [PDFsController::class, 'servicioPostal'])->name('descargar-servicioPostal');
    Route::get('/descargar-amparoRepre' , [PDFsController::class, 'amparoRepre'])->name('descargar-amparoRepre');
    Route::get('/descargar-amparo'      , [PDFsController::class, 'amparo'])->name('descargar-amparo');
    Route::get('/manifestacion/{id}'    , [PDFsController::class, 'manifestacion'])->name('manifestacion.download');
    Route::get('/seguimiento/{fecha}'   , [PDFsController::class, 'seguimiento'])->name('seguimiento.download');
    Route::get('/sobre-sepomex/{id}'    , [PDFsController::class, 'sobreSepomex'])->name('sobreSepomex.download');
    Route::get('/sobre-personal/{id}'   , [PDFsController::class, 'sobrePersonal'])->name('sobrePersonal.download');
    Route::get('/invitacion/1/{id}'   , [PDFsController::class, 'invitacionUno'])->name('invitacionUno.download');
    Route::get('/invitacion/2/{id}'   , [PDFsController::class, 'invitacionDos'])->name('invitacionDos.download');

    //**Documentos DOCX */
    Route::get('/descargar-inv1/{id}', [DOCxController::class, 'invitacion_uno'])->name('descargar-inv1');
    Route::get('/descargar-inv2/{id}', [DOCxController::class, 'invitacion_dos'])->name('descargar-inv2');
});




require __DIR__.'/auth.php';
