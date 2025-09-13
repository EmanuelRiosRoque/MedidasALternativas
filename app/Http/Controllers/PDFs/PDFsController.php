<?php

namespace App\Http\Controllers\PDFs;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Solicitante;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

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
            'numero'=> '133',
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

public function correoMexico()
{
    $documentos = [
        [
            'nombre' => 'Emanuel Rios Roque',
            'domicilio' => 'NIÑOS HÉROES 133 5TO PISO',
            'lugar' => 'COL.DOCTORES ALCALDIA CUAUHTÉMOC',
            'materia' => 'CIVIL',
            'cp' => '06720'
        ],
        [
            'nombre' => 'Lucía Martínez',
            'domicilio' => 'NIÑOS HÉROES 133 5TO PISO',
            'lugar' => 'COL.DOCTORES ALCALDIA CUAUHTÉMOC',
            'materia' => 'CIVIL',
            'cp' => '06720'
        ],
    ];

    $pdf = Pdf::loadView('pdfs.correosDeMx', compact('documentos'));
    $pdf->setPaper('letter', 'landscape');
    // return $pdf->stream('correosDeMx.pdf');

    return $pdf->download('correosDeMx.pdf');
}

    
public function servicioPostal()
{
    $documentos = [
        [
            'nombre' => 'Emanuel Rios',
            'calle' => 'Av. Reforma',
            'numero' => '123',
            'colonia' => 'Centro',
            'poblacion' => 'CDMX',
            'cp' => '06000',
            'hora' => '10:30 AM',
        ],
        [
            'nombre' => 'Lucía Martínez',
            'calle' => 'Insurgentes Sur',
            'numero' => '456',
            'colonia' => 'Del Valle',
            'poblacion' => 'CDMX',
            'cp' => '03100',
            'hora' => '12:00 PM',
        ],
        [
            'nombre' => 'Lucía Martínez',
            'calle' => 'Insurgentes Sur',
            'numero' => '456',
            'colonia' => 'Del Valle',
            'poblacion' => 'CDMX',
            'cp' => '03100',
            'hora' => '12:00 PM',
        ],
        // Puedes agregar más...
    ];

    $pdf = Pdf::loadView('pdfs.serviciosPostal', compact('documentos'));
    $pdf->setPaper('letter', 'portrait');
    
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




    public function amparoRepre()
    {
        $pdf = Pdf::loadView('pdfs.amparoRepresentante', $this->datosBase());
        $pdf->setPaper('letter', 'portrait');
        return $pdf->download('amparoRepresentante.pdf');   
    }



}
