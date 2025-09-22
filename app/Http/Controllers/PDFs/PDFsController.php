<?php

namespace App\Http\Controllers\PDFs;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Solicitante;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Solicitud;

class PDFsController extends Controller
{
    private function datosBase()
    {
        Carbon::setLocale('es');
        $fechaActual = Carbon::now();

        return [
            'fecha' => $fechaActual->format('d/m/Y'),
            'dia' => $fechaActual->format('d'),
            'mes' => $fechaActual->translatedFormat('F'),
            'ultimoDigitoAnio' => substr($fechaActual->format('Y'), -1),
            'nombre' => 'EMANUEL RIOS ROQUE',
            'calle' =>  'NIÑOS HÉROES',
            'numero' => '133',
            'colonia' => 'DOCTORES',
            'poblacion' => 'CIUDAD DE MÉXICO',
            'domicilio' => 'NIÑOS HÉROES 133 5TO PISO',
            'lugar' => 'COL.DOCTORES ALCALDIA CUAUHTÉMOC',
            'materia' => 'CIVIL',
            'cp' => '06720',
            'cdm' => 'C O N D E S A',
            'direccion' => 'NIÑOS HEROES 133, COL.DOCTORES, ALCALDIA CUAUHTEMOC, C.P. 06720, CIUDAD DE MÉXICO',
        ];
    }

    public function amparo()
    {
        $pdf = Pdf::loadView('pdfs.amparo', $this->datosBase());
        $pdf->setPaper('letter', 'portrait');
        return $pdf->download('amparo.pdf');
    }

    public function correoMexico($id)
    {
        $invitados = Solicitante::with('solicitud:id,materia')
            ->where('solicitud_id', $id)
            ->where('tipo_solicitante', 'invitado')
            ->get([
                'solicitud_id',
                'nombre',
                'apellido_p',
                'apellido_m',
                'calle',
                'colonia',
                'municipio',
                'entidad_federativa',
                'cp'
            ]);

        if ($invitados->isEmpty()) {
            return back()->with('error', 'No hay invitados para esta solicitud.');
        }

        $documentos = $invitados->map(function ($i) {
            // Nombre
            $partesNom = collect([
                trim((string)$i->nombre),
                trim((string)$i->apellido_p),
                trim((string)$i->apellido_m)
            ])->filter(fn($v) => $v !== '');
            $nombre = $partesNom->isNotEmpty() ? $partesNom->implode(' ') : '';

            // Domicilio
            $partesDom = collect([
                trim((string)$i->calle),
                trim((string)$i->colonia),
                trim((string)$i->municipio),
                trim((string)$i->entidad_federativa)
            ])->filter(fn($v) => $v !== '');
            $domicilio = $partesDom->isNotEmpty() ? $partesDom->implode(', ') : '';

            // CP
            $raw = preg_replace('/\D/', '', (string)($i->cp ?? ''));
            $cp  = $raw !== '' ? str_pad($raw, 5, '0', STR_PAD_LEFT) : '';

            return [
                'nombre'    => $nombre,
                'domicilio' => $domicilio,
                'cp'        => $cp,
                'lugar'     => 'COL.DOCTORES ALCALDIA CUAUHTÉMOC',
                'materia'   => $i->solicitud->materia = 'civi' ? 'CIVIL' : 'FAMILIAR',
            ];
        })->all();

        $pdf = Pdf::loadView('pdfs.correosDeMx', compact('documentos'))
            ->setPaper('letter', 'landscape');

        return $pdf->download('correosDeMx.pdf');
    }

    public function servicioPostal($id)
    {
        $invitados = Solicitante::with('solicitud:id,materia')
            ->where('solicitud_id', $id)
            ->where('tipo_solicitante', 'invitado')
            ->get([
                'solicitud_id',
                'nombre',
                'apellido_p',
                'apellido_m',
                'calle',
                'colonia',
                'municipio',
                'entidad_federativa',
                'cp',
            ]);

        if ($invitados->isEmpty()) {
            return back()->with('error', 'No hay invitados para esta solicitud.');
        }

        $documentos = $invitados->map(function ($i) {
            // Nombre
            $partesNom = collect([$i->nombre, $i->apellido_p, $i->apellido_m])
                ->map(fn($v) => trim((string)$v))
                ->filter();
            $nombre = $partesNom->isNotEmpty() ? $partesNom->implode(' ') : '';

            // Domicilio
            $partesDom = collect([$i->calle, $i->colonia, $i->municipio, $i->entidad_federativa])
                ->map(fn($v) => trim((string)$v))
                ->filter();
            $domicilio = $partesDom->isNotEmpty() ? $partesDom->implode(', ') : '';

            // CP
            $raw = preg_replace('/\D/', '', (string)($i->cp ?? ''));
            $cp  = $raw !== '' ? str_pad($raw, 5, '0', STR_PAD_LEFT) : '';


            return [
                'nombre'    => $nombre,
                'calle'     => $domicilio,
                'cp'        => $cp,
                'colonia'   => $i->colonia,
                'poblacion' => $i->entidad_federativa,
                'lugar'     => 'COL.DOCTORES ALCALDIA CUAUHTÉMOC',
                'materia'   => $i->solicitud->materia ?? 'N/D',
            ];
        })->all();

        $pdf = Pdf::loadView('pdfs.serviciosPostal', compact('documentos'))
            ->setPaper('letter', 'landscape');

        return $pdf->download('serviciosPostal.pdf');
    }

    public function manifestacion($id)
    {
        $solicitante = Solicitante::where('solicitud_id', $id)
            ->where('tipo_solicitante', 'solicitante')
            ->first();

        $invitados = Solicitante::where('solicitud_id', $id)
            ->where('tipo_solicitante', 'invitado')
            ->get();

        // Nombre completo del solicitante
        $nombreSolicitante = $solicitante
            ? trim("{$solicitante->nombre} {$solicitante->apellido_p} {$solicitante->apellido_m}")
            : '—';

        // Nombres completos de invitados (pueden ser varios)
        $nombresInvitados = $invitados->map(function ($inv) {
            return trim("{$inv->nombre} {$inv->apellido_p} {$inv->apellido_m}");
        })->implode(', '); // 👈 todos juntos en un string

        // Generar PDFs según existan
        $pdfFiles = [];

        // === PDF del solicitante (si existe) ===
        if ($solicitante) {
            $pdfSol = Pdf::loadView('pdfs.manifestacionSol', [
                'nombreSolicitante' => $nombreSolicitante,
                'nombreInvitado'    => $nombresInvitados ?: '—', // uno o todos concatenados
            ])->setPaper('letter', 'portrait');

            $pdfFiles['manifestacion_solicitante.pdf'] = $pdfSol->output();
        }

        // === PDFs de cada invitado (si existen) ===
        if ($invitados->isNotEmpty()) {
            foreach ($invitados as $index => $inv) {
                $nombreInvitado = trim("{$inv->nombre} {$inv->apellido_p} {$inv->apellido_m}");

                $pdfInv = Pdf::loadView('pdfs.manifestacionInv', [
                    'nombreSolicitante' => $nombreSolicitante,
                    'nombreInvitado'    => $nombreInvitado,
                ])->setPaper('letter', 'portrait');

                $filename = 'manifestacion_invitado_' . ($index + 1) . '.pdf';
                $pdfFiles[$filename] = $pdfInv->output();
            }
        }

        // === Si solo hay un archivo, devolverlo directo ===
        if (count($pdfFiles) === 1) {
            $filename = array_key_first($pdfFiles);
            return response($pdfFiles[$filename])
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
        }

        // === Si hay más de un archivo, generar ZIP ===
        $zipFile = storage_path("app/manifestaciones_{$id}.zip");
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($pdfFiles as $filename => $content) {
                $zip->addFromString($filename, $content);
            }
            $zip->close();
        }

        return response()->download($zipFile)->deleteFileAfterSend(true);
    }

    public function sobreSepomex($id)
    {
        $invitados = Solicitante::with('solicitud:id,materia')
            ->where('solicitud_id', $id)
            ->where('tipo_solicitante', 'invitado')
            ->get([
                'solicitud_id',
                'nombre',
                'apellido_p',
                'apellido_m',
                'calle',
                'colonia',
                'municipio',
                'entidad_federativa',
                'cp',
            ]);

        if ($invitados->isEmpty()) {
            return back()->with('error', 'No hay invitados para esta solicitud.');
        }

        $documentos = $invitados->map(function ($i) {
            // Nombre completo
            $nombre = collect([$i->nombre, $i->apellido_p, $i->apellido_m])
                ->map(fn($v) => trim((string)$v))
                ->filter()
                ->implode(' ');
            $nombre = $nombre !== '' ? $nombre : 'SIN NOMBRE';

            // CP limpio
            $cp = preg_replace('/\D/', '', (string)($i->cp ?? ''));
            $cp = $cp !== '' ? str_pad($cp, 5, '0', STR_PAD_LEFT) : 'SIN CP';

            // Domicilio completo con CP
            $partesDom = collect([
                $i->calle,
                $i->colonia,
                $i->municipio,
                $i->entidad_federativa,
                "C.P. $cp"
            ])->map(fn($v) => trim((string)$v))->filter();

            $domicilio = $partesDom->isNotEmpty()
                ? $partesDom->implode(', ')
                : 'SIN DOMICILIO';

            return [
                'nombre'    => $nombre,
                'calle'     => $i->calle ?? 'SIN CALLE',
                'col'       => $i->colonia ?? 'SIN COLONIA',
                'municipio' => $i->municipio ?? 'SIN MUNICIPIO',
            ];
        })->all();

        $pdf = Pdf::loadView('pdfs.sobre_sepomex', compact('documentos'))
            ->setPaper('letter', 'landscape');

        return $pdf->stream('sobres_sepomex.pdf');
    }

    public function sobrePersonal($id)
    {

        $invitados = Solicitante::with('solicitud:id,materia')
            ->where('solicitud_id', $id)
            ->where('tipo_solicitante', 'invitado')
            ->get([
                'solicitud_id',
                'nombre',
                'apellido_p',
                'apellido_m',
            ]);

        if ($invitados->isEmpty()) {
            return back()->with('error', 'No hay invitados para esta solicitud.');
        }

        $documentos = $invitados->map(function ($i) {
            // Nombre completo
            $nombre = collect([$i->nombre, $i->apellido_p, $i->apellido_m])
                ->map(fn($v) => trim((string)$v))
                ->filter()
                ->implode(' ');
            $nombre = $nombre !== '' ? $nombre : 'SIN NOMBRE';

            return [
                'nombre'    => $nombre,
            ];
        })->all();

        $pdf = Pdf::loadView('pdfs.sobre_personal', compact('documentos'))
            ->setPaper('letter', 'landscape');


        return $pdf->stream('sobre_persoanl.pdf');
    }

     public function invitacionUno($id)
    {
        // reutilizamos la función para obtener placeholders
        $documentos = $this->buildPlaceholdersForSolicitud($id);

        // ahora lo pasamos a la vista
        $pdf = Pdf::loadView('pdfs.invitacionUno', $documentos)
            ->setPaper('letter', 'portrait');

        return $pdf->stream('invitacion_uno.pdf');
    }

    public function seguimiento($fecha)
    {
        // Pasas la variable a la vista con compact()
        $pdf = Pdf::loadView('pdfs.seguimiento', compact('fecha'))
            ->setPaper('letter', 'portrait');

        return $pdf->download('seguimiento.pdf');
    }

    public function amparoRepre()
    {
        $pdf = Pdf::loadView('pdfs.amparoRepresentante', $this->datosBase());
        $pdf->setPaper('letter', 'portrait');
        return $pdf->download('amparoRepresentante.pdf');
    }

    private function buildPlaceholdersForSolicitud($id): array
{
    $personas = Solicitante::with(['telefonos', 'correos'])
        ->where('solicitud_id', $id)
        ->get()
        ->groupBy('tipo_solicitante'); // 'solicitante' | 'invitado'

    $invitado     = optional($personas->get('invitado'))->first();
    $solicitante  = optional($personas->get('solicitante'))->first();

    $nombreInv = $invitado
        ? trim("{$invitado->nombre} {$invitado->apellido_p} {$invitado->apellido_m}")
        : 'Sin invitado';
    $telInv = $invitado
        ? $invitado->telefonos->pluck('numero')->filter()->unique()->implode(', ')
        : '—';
    $mailInv = $invitado
        ? $invitado->correos->pluck('email')->filter()->unique()->implode(', ')
        : '—';

    $nombreSol = $solicitante
        ? trim("{$solicitante->nombre} {$solicitante->apellido_p} {$solicitante->apellido_m}")
        : 'Sin solicitante';
    $telSol = $solicitante
        ? $solicitante->telefonos->pluck('numero')->filter()->unique()->implode(', ')
        : '—';
    $mailSol = $solicitante
        ? $solicitante->correos->pluck('email')->filter()->unique()->implode(', ')
        : '—';

    return [
        'nombre_invitado'     => $nombreInv,
        'num_invitado'        => $telInv,
        'email_invitado'      => $mailInv,
        'nombre_solicitante'  => $nombreSol,
        'num_solicitante'     => $telSol,
        'email_solicitante'   => $mailSol,
    ];
}
}
