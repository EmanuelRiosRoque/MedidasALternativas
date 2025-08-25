<?php

namespace App\Http\Controllers\PDFs;

use Carbon\Carbon;
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



    public function amparoRepre()
    {
        $pdf = Pdf::loadView('pdfs.amparoRepresentante', $this->datosBase());
        $pdf->setPaper('letter', 'portrait');
        return $pdf->download('amparoRepresentante.pdf');   
    }



}
